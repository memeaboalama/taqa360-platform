<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;

class FetchFooterSupportEmailAction
{
    public function handle(): ?string
    {
        $placementId = AdPlacement::query()
            ->where('key', 'footer_main_section')
            ->value('id');

        if (!$placementId) {
            return null;
        }

        $content = AdBanner::query()
            ->where('placement_id', $placementId)
            ->where('status', 'active')
            ->first()
            ?->content;

        $email = data_get($content, 'contact.email');

        return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL)
            ? $email
            : null;
    }
}
