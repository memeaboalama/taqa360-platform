<?php

namespace Domain\Educator\Actions\Profile;

use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;

class UpdateBankInfoAction
{
    public function handle(Educator $educator, array $data)
    {
        DB::transaction(function () use ($educator, $data) {
            $educator->bankInfo()->updateOrCreate(
                ['educator_id' => $educator->id],
                $data
            );
        });
    }

    public function delete(Educator $educator)
    {
        $educator->bankInfo()?->delete();
    }
}
