<?php

namespace Domain\Educator\Actions\Profile;

use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;

class UpdateLanguagesBulkAction
{
    public function handle(Educator $educator, array $languages): void
    {
        DB::transaction(function () use ($educator, $languages) {
            $kept = [];
            $hasPrimary = false;
            foreach ($languages as $item) {
                $isPrimary = (bool)($item['is_primary'] ?? false);
                if ($isPrimary) {
                    $hasPrimary = true;
                }
                $data = [
                    'language_id' => $item['language_id'],
                    'level' => $item['level'],
                    'is_primary' => $isPrimary,
                ];
                $id = $item['id'] ?? null;
                $model = $educator->languages()->updateOrCreate(['id' => $id], $data);
                $kept[] = $model->id;
            }
            $educator->languages()->whereNotIn('id', $kept)->delete();
            if (!$educator->languages()->where('is_primary', true)->exists() && $educator->languages()->count() > 0) {
                $first = $educator->languages()->first();
                $first->update(['is_primary' => true]);
            }
            if ($hasPrimary) {
                $educator->languages()->whereNotIn('id', $kept)->update(['is_primary' => false]);
            }
        });
    }
}
