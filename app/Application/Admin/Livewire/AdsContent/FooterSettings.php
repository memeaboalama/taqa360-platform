<?php

namespace Application\Admin\Livewire\AdsContent;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Actions\AdBanner\StoreBannerAction;
use App\Domain\AppBase\Actions\AdBanner\UpdateBannerAction;
use App\Support\Enum\BannerStatusEnum;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class FooterSettings extends Component
{
    use WithFileUploads, LivewireAlert;

    public $placementId;
    public $bannerId;

    public $logo;
    public $existing_logo;
    public $delete_logo = false;

    public $footer_description_en;
    public $footer_description_ar;

    public $copyright_text_en;
    public $copyright_text_ar;

    public $phone;
    public $email;
    public $address_en;
    public $address_ar;

    public $social_facebook;
    public $social_twitter;
    public $social_instagram;
    public $social_linkedin;
    public $social_youtube;
    public $social_whatsapp;

    public $link_terms;
    public $link_privacy;
    public $link_contact;

    public function mount($placementId)
    {
        $this->placementId = $placementId;
        $this->loadContent();
    }

    public function removeImage()
    {
        $this->logo = null;
        $this->existing_logo = null;
        $this->delete_logo = true;
    }

    public function loadContent()
    {
        $banner = AdBanner::where('placement_id', $this->placementId)
            ->where('status', 'active')
            ->first();

        if ($banner) {
            $this->bannerId = $banner->id;

            $content = $banner->content ?? [];
            $this->existing_logo = $banner->image_desktop ?? $content['images']['desktop'] ?? $content['logo'] ?? null;

            $this->footer_description_en = $content['description']['en'] ?? '';
            $this->footer_description_ar = $content['description']['ar'] ?? '';

            $this->copyright_text_en = $content['copyright']['en'] ?? '';
            $this->copyright_text_ar = $content['copyright']['ar'] ?? '';

            $contact = $content['contact'] ?? [];
            $this->phone = $contact['phone'] ?? '';
            $this->email = $contact['email'] ?? '';
            $this->address_en = $contact['address']['en'] ?? '';
            $this->address_ar = $contact['address']['ar'] ?? '';

            $social = $content['social'] ?? [];
            $this->social_facebook = $social['facebook'] ?? '';
            $this->social_twitter = $social['twitter'] ?? '';
            $this->social_instagram = $social['instagram'] ?? '';
            $this->social_linkedin = $social['linkedin'] ?? '';
            $this->social_youtube = $social['youtube'] ?? '';
            $this->social_whatsapp = $social['whatsapp'] ?? '';

            $links = $content['links'] ?? [];
            $this->link_terms = $links['terms'] ?? '';
            $this->link_privacy = $links['privacy'] ?? '';
            $this->link_contact = $links['contact'] ?? '';
        }
    }

    public function save(StoreBannerAction $storeAction, UpdateBannerAction $updateAction)
    {
        $this->validate([
            'logo' => 'nullable|image|max:2048',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $logoPath = $this->delete_logo ? null : ($this->existing_logo ?? null);


        $data = [
            'placement_id' => $this->placementId,
            'name' => 'Footer Content',
            'status' => BannerStatusEnum::ACTIVE->value,
            'content' => [

                'logo_is_image_desktop' => true,

                'description' => [
                    'en' => $this->footer_description_en,
                    'ar' => $this->footer_description_ar,
                ],
                'copyright' => [
                    'en' => $this->copyright_text_en,
                    'ar' => $this->copyright_text_ar,
                ],
                'contact' => [
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'address' => [
                        'en' => $this->address_en,
                        'ar' => $this->address_ar,
                    ]
                ],
                'social' => [
                    'facebook' => $this->social_facebook,
                    'twitter' => $this->social_twitter,
                    'instagram' => $this->social_instagram,
                    'linkedin' => $this->social_linkedin,
                    'youtube' => $this->social_youtube,
                    'whatsapp' => $this->social_whatsapp,
                ],
                'links' => [
                    'terms' => $this->link_terms,
                    'privacy' => $this->link_privacy,
                    'contact' => $this->link_contact,
                ]
            ],
        ];

        if ($this->logo) {
            $data['image_desktop'] = $this->logo;
        }



        if ($this->bannerId) {
            $updatedBanner = $updateAction->handle($this->bannerId, $data);
            if ($this->delete_logo) {
                $updatedBanner->update(['image_desktop' => null]);
            }
        } else {
            $storeAction->handle($data);
        }

        Cache::forget('footer_settings');
        Cache::forget('footer_main_section');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_ar');

        $this->alert('success', __('admin::lang.Updated Successfully'));

        $this->logo = null;
        $this->loadContent();
    }

    public function render()
    {
        return view('admin::livewire.ads-content.footer-settings');
    }
}
