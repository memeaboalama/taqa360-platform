<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaudiCityPrioritySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $countries = Country::whereRaw('LOWER(iso) IN (?, ?)', ['sa', 'sau'])->get();
            if ($countries->count() !== 1) {
                throw new RuntimeException('Expected exactly one Saudi country with ISO sa or sau.');
            }

            $country = $countries->first();
            $cities = City::where('country_id', $country->id)->get();
            // These are source elm_id values, never the database primary IDs.
            $priorities = [
                ['elm_id' => '3', 'names' => ['الرياض', 'Riyadh'], 'priority' => 1],
                ['elm_id' => '18', 'names' => ['جدة', 'Jeddah'], 'priority' => 2],
                ['elm_id' => '6', 'names' => ['مكة المكرمة', 'مكة', 'Makkah', 'Mecca'], 'priority' => 3],
                ['elm_id' => '14', 'names' => ['المدينة المنورة', 'Madinah', 'Medina'], 'priority' => 4],
                ['elm_id' => '13', 'names' => ['الدمام', 'Dammam'], 'priority' => 5],
            ];

            $updates = [];
            foreach ($priorities as $entry) {
                $matches = $cities->filter(fn ($city) => (string) $city->elm_id === $entry['elm_id']);
                if ($matches->isEmpty()) {
                    $names = array_map(fn ($name) => mb_strtolower(trim($name)), $entry['names']);
                    $matches = $cities->filter(function ($city) use ($names) {
                        if ($city->elm_id !== null && $city->elm_id !== '') return false;
                        foreach (array_merge(array_values($city->getTranslations('title')), [$city->key ?? '']) as $name) {
                            if (in_array(mb_strtolower(trim($name)), $names, true)) return true;
                        }
                        return false;
                    });
                }
                if ($matches->isEmpty()) {
                    throw new RuntimeException('City not found: ' . $entry['names'][0] . '. Import Saudi locations first.');
                }
                $updates[] = ['ids' => $matches->pluck('id')->all(), 'priority' => $entry['priority']];
            }

            // Update priority only; keep IDs, names, UUIDs, statuses and relationships intact.
            DB::table('cities')->where('country_id', $country->id)->update(['priority' => 100]);
            foreach ($updates as $update) {
                DB::table('cities')->where('country_id', $country->id)
                    ->whereIn('id', $update['ids'])->update(['priority' => $update['priority']]);
            }
        });

        if ($this->command) $this->command->info('Saudi city priorities updated: Riyadh, Jeddah, Makkah, Madinah, Dammam, then other cities.');
    }
}
