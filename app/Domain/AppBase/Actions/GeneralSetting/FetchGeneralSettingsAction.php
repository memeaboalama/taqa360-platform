<?php

namespace App\Domain\AppBase\Actions\GeneralSetting;

use App\Domain\AppBase\Repositories\GeneralSettingRepository;

class FetchGeneralSettingsAction
{
    private GeneralSettingRepository $generalSettingRepository;

    public function __construct(GeneralSettingRepository $generalSettingRepository)
    {
        $this->generalSettingRepository = $generalSettingRepository;
    }

    public function handle(array $cols = []): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->generalSettingRepository->selectAndFind(1, $cols);
    }
}
