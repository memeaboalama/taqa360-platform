<?php

namespace App\Console\Commands;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SyncSaudiLocationsCommand extends Command
{
    protected $signature = 'locations:sync-sa
        {--without-districts : Sync regions and cities only}
        {--without-cities : Sync regions only}
        {--dry-run : Preview changes in a transaction and roll them back}
        {--path= : Directory containing the three JSON files}';

    protected $description = 'Import Saudi locations into Taqa countries, regions, cities and distracts';

    public function handle(): int
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        try {
            $directory = $this->option('path') ?: database_path('data/saudi-locations');
            $regions = $this->readRows($directory, 'regions_lite.json', 'region_id');
            $cities = $this->option('without-cities') ? [] : $this->readRows($directory, 'cities_lite.json', 'city_id');
            $districts = $this->option('without-cities') || $this->option('without-districts')
                ? [] : $this->readRows($directory, 'districts_lite.json', 'district_id');
            $this->validateParents($regions, $cities, $districts);

            $counts = ['Regions' => [0, 0, 0], 'Cities' => [0, 0, 0], 'Districts' => [0, 0, 0]];
            $connection = DB::connection();
            $connection->beginTransaction();
            try {
                // Country requires a configured currency and VAT. Reuse it rather than inventing them.
                $countries = Country::whereRaw('LOWER(iso) IN (?, ?)', ['sa', 'sau'])->get();
                if ($countries->count() !== 1) {
                    throw new RuntimeException('Configure exactly one Saudi country (iso sa or SA/SAU) with its currency and VAT before importing.');
                }
                $country = $countries->first();
                $existingRegions = Region::where('country_id', $country->id)->get();
                $existingCities = City::where('country_id', $country->id)->get();
                $existingDistricts = Distract::where('country_id', $country->id)->get();
                $citiesByRegion = $existingCities->groupBy(fn ($city) => $city->region_id ?? 'unassigned');
                $citiesByElmId = $existingCities->filter(fn ($city) => $city->elm_id !== null)->keyBy('elm_id');
                $districtsByCity = $existingDistricts->groupBy('city_id');
                $regionMap = [];
                $cityMap = [];

                foreach ($regions as $row) {
                    $model = $this->match($existingRegions, $row, 'region_id', 'region') ?? new Region();
                    $this->saveLocation($model, $row, [
                        'country_id' => $country->id,
                        'elm_id' => (string) $row['region_id'],
                    ], $counts['Regions']);
                    $regionMap[(string) $row['region_id']] = $model;
                    if (!$existingRegions->contains('id', $model->id)) $existingRegions->push($model);
                }
                foreach ($cities as $row) {
                    $region = $regionMap[(string) $row['region_id']];
                    $candidates = collect($citiesByRegion->get($region->id, collect())->all())
                        ->merge($citiesByRegion->get('unassigned', collect())->filter(fn ($city) => $city->region_id === null));
                    if ($known = $citiesByElmId->get((string) $row['city_id'])) $candidates->push($known);
                    $candidates = $candidates->unique('id');
                    $model = $this->match($candidates, $row, 'city_id', 'city') ?? new City();
                    $this->saveLocation($model, $row, [
                        'country_id' => $country->id,
                        'region_id' => $region->id,
                        'elm_id' => (string) $row['city_id'],
                    ], $counts['Cities']);
                    $cityMap[(string) $row['city_id']] = $model;
                    $citiesByElmId->put((string) $row['city_id'], $model);
                }
                foreach ($districts as $row) {
                    $city = $cityMap[(string) $row['city_id']];
                    $candidates = $districtsByCity->get($city->id, collect());
                    // Distract has neither elm_id nor region_id: match its title within its city.
                    $model = $this->match($candidates, $row, null, 'district') ?? new Distract();
                    $this->saveLocation($model, $row, [
                        'country_id' => $country->id,
                        'city_id' => $city->id,
                    ], $counts['Districts']);
                    if (!$candidates->contains('id', $model->id)) $candidates->push($model);
                    $districtsByCity->put($city->id, $candidates);
                }

                if ($this->option('dry-run')) $connection->rollBack();
                else $connection->commit();
            } catch (\Throwable $exception) {
                $connection->rollBack();
                throw $exception;
            }

            $this->table(['Type', 'Created', 'Updated', 'Unchanged'], collect($counts)
                ->map(fn ($values, $type) => array_merge([$type], $values))->values()->all());
            $this->info($this->option('dry-run') ? 'Preview complete; all database changes were rolled back.' : 'Saudi locations synchronized successfully.');
            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }
    }

    private function readRows(string $directory, string $file, string $id): array
    {
        $path = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $file;
        if (!is_readable($path)) throw new RuntimeException('JSON file is missing or unreadable: ' . $path);
        $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($rows) || !array_is_list($rows) || !$rows) {
            throw new RuntimeException('Expected a non-empty JSON list: ' . $file);
        }
        $seen = [];
        foreach ($rows as $row) {
            if (!is_array($row) || !isset($row[$id]) || !is_scalar($row[$id]) ||
                !is_string($row['name_ar'] ?? null) || trim($row['name_ar']) === '' ||
                !is_string($row['name_en'] ?? null) || trim($row['name_en']) === '') {
                throw new RuntimeException('Invalid location record in ' . $file);
            }
            if (isset($seen[(string) $row[$id]])) throw new RuntimeException('Duplicate source ID in ' . $file . ': ' . $row[$id]);
            $seen[(string) $row[$id]] = true;
        }
        return $rows;
    }

    private function validateParents(array $regions, array $cities, array $districts): void
    {
        $regionIds = array_column($regions, null, 'region_id');
        $cityIds = array_column($cities, null, 'city_id');
        foreach ($cities as $row) {
            if (!isset($regionIds[$row['region_id'] ?? ''])) throw new RuntimeException('Unknown region for city ' . $row['city_id']);
        }
        foreach ($districts as $row) {
            $city = $cityIds[$row['city_id'] ?? ''] ?? null;
            if (!$city || (string) ($row['region_id'] ?? '') !== (string) $city['region_id']) {
                throw new RuntimeException('Invalid city/region for district ' . $row['district_id']);
            }
        }
    }

    private function match(Collection $candidates, array $row, ?string $id, string $type): ?Model
    {
        if ($id !== null) {
            $byId = $candidates->filter(fn ($model) => (string) $model->elm_id === (string) $row[$id]);
            if ($byId->count() > 1) throw new RuntimeException('Duplicate elm_id for ' . $type . ': ' . $row[$id]);
            if ($byId->count() === 1) return $byId->first();
            // Never overwrite a different source record merely because names coincide.
            $candidates = $candidates->filter(fn ($model) => $model->elm_id === null || $model->elm_id === '');
        }
        $names = [$this->normalize($row['name_ar'], $type), $this->normalize($row['name_en'], $type)];
        $matches = $candidates->filter(function ($model) use ($names, $type) {
            $titles = $model->getTranslations('title');
            foreach ([$titles['ar'] ?? '', $titles['en'] ?? '', $model->key ?? ''] as $value) {
                if ($value !== '' && in_array($this->normalize($value, $type), $names, true)) return true;
            }
            return false;
        });
        if ($matches->count() > 1) throw new RuntimeException('Ambiguous existing ' . $type . ': ' . $row['name_ar'] . '. Resolve duplicate names before importing.');
        return $matches->first();
    }

    private function normalize(string $value, string $type): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value);
        if ($type === 'region') $value = preg_replace('/^منطقة\s+|\s+region$/u', '', $value);
        if ($type === 'district') $value = preg_replace('/^حي\s+|\s+(district|dist\.)$/u', '', $value);
        return trim($value);
    }

    private function saveLocation(Model $model, array $row, array $attributes, array &$counts): void
    {
        $new = !$model->exists;
        // Preserve existing translations, status, priority, UUID, keys, and primary IDs.
        $attributes['title'] = array_replace($model->getTranslations('title'), [
            'ar' => $row['name_ar'], 'en' => $row['name_en'],
        ]);
        if ($new) $attributes += ['key' => $row['name_ar'], 'status' => 'active', 'priority' => 1];
        $model->fill($attributes);
        $changed = $model->isDirty();
        if ($new || $changed) $model->save();
        $counts[$new ? 0 : ($changed ? 1 : 2)]++;
    }
}
