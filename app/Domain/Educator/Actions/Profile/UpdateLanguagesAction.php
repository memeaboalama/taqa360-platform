<?php

namespace Domain\Educator\Actions\Profile;

use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateLanguagesAction
{
    public function handle(Educator $educator, array $data, ?int $editingId)
    {
        DB::beginTransaction();
        try {
            $data['language_id'] = (int) $data['language_id'];

            if ($data['is_primary']) {
                $educator->languages()->where('id', '!=', $editingId)->update(['is_primary' => false]);
            }

            $language = $educator->languages()->updateOrCreate(['id' => $editingId], $data);

            if (!$educator->languages()->where('is_primary', true)->exists() && $educator->languages()->count() > 0) {
                $educator->languages()->first()->update(['is_primary' => true]);
            }

            DB::commit();
            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateLanguagesAction Save Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Educator $educator, int $id): void
    {
        DB::beginTransaction();
        try {
            $language = $educator->languages()->find($id);
            if (!$language) return;

            $wasPrimary = $language->is_primary;
            $language->delete();

            if ($wasPrimary && $educator->languages()->count() > 0) {
                if (!$educator->languages()->where('is_primary', true)->exists()) {
                    $educator->languages()->first()->update(['is_primary' => true]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("UpdateLanguagesAction Delete Error: " . $e->getMessage());
            throw $e;
        }
    }
}
