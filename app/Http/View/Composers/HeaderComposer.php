<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Domain\AppBase\Models\AdBanner;
use App\Support\Enum\PlacementTypeEnum;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $headerData = Cache::remember('site_header_settings_v2', 60*60, function () {
            $banner = AdBanner::whereHas('placement', function ($query) {
                $query->where('type', PlacementTypeEnum::HEADER)
                    ->where('key', 'site_header');
            })
            ->where('status', 'active')
            ->first();

            if (!$banner) {
                return null;
            }

            $content = $banner->content ?? [];
            $subtitle = $banner->subtitle;

            return [
                'site_name' => $banner->title ?? config('app.name'),
                'top_message' => !empty($subtitle) ? $banner->getTranslations('subtitle') : '',
                'logo' => $content['images']['desktop'] ?? null,
                'mobile_logo' => $content['images']['mobile'] ?? null,
                'favicon' => $content['images']['favicon'] ?? null,
                'offer_text' => $content['offer_text'] ?? [],
                'offer_link' => $content['offer_action']['url'] ?? ($content['offer_link'] ?? '#'),
            ];
        });

        if ($headerData) {
            $locale = app()->getLocale();
            $topMessage = $headerData['top_message'] ?? '';
            if (is_array($topMessage)) {
                $topMessage = $topMessage[$locale] ?? ($topMessage['ar'] ?? '');
            }
            $headerData['top_message'] = $topMessage;

            $offerText = $headerData['offer_text'] ?? '';
            if (is_array($offerText)) {
                $offerText = $offerText[$locale] ?? ($offerText['en'] ?? '');
            }
            $headerData['offer_text'] = $offerText;
        }

        $view->with('headerData', $headerData);
    }
}
