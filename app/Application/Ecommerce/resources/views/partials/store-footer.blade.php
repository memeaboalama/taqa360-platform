{{--
    Shared store footer. Only renders content configured in the admin footer settings.
--}}
@php
    $storeLocale = $currentLocale ?? app()->getLocale();
    $storeLocalized = static function ($value, $fallback = '') use ($storeLocale) {
        if (is_array($value)) {
            return $value[$storeLocale] ?? $value['en'] ?? reset($value) ?? $fallback;
        }
        return $value ?: $fallback;
    };
    $storeFooter = $footerData ?? app(\App\Domain\AppBase\Actions\Home\FetchHomeDataAction::class)->getStoreFooterData() ?? [];
    $storeFooterLogo = data_get($storeFooter, 'logo');
    $storeDescription = $storeLocalized(data_get($storeFooter, 'description'), '');
    $storeSocialLinks = data_get($storeFooter, 'social', []);
    $hasStoreSocialLinks = collect($storeSocialLinks)->contains(fn ($url) => filled($url));
    $storeTermsUrl = data_get($storeFooter, 'links.terms');
    $storePrivacyUrl = data_get($storeFooter, 'links.privacy');
    $contactLink = data_get($storeFooter, 'links.contact');
    $copyrightText = $storeLocalized(
        $storeFooter['copyright'] ?? null,
        ''
    );
    $copyrightText = preg_replace('/\s*©\s*(?:[0-9٠-٩]{4}\s*)?/u', ' ', (string) $copyrightText);
    $copyrightText = preg_replace('/^\s*[0-9٠-٩]{4}\s*/u', '', $copyrightText);
    $copyrightText = trim($copyrightText);
    $hasFooterContent = filled($storeFooterLogo)
        || filled($storeDescription)
        || $hasStoreSocialLinks
        || filled($storeTermsUrl)
        || filled($storePrivacyUrl)
        || filled($contactLink)
        || filled($copyrightText);
    $hasFooterBrandContent = filled($storeFooterLogo) || filled($storeDescription);
    $hasFooterLinks = filled($storeTermsUrl) || filled($storePrivacyUrl) || filled($contactLink);
    $socialIcons = [
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'instagram' => 'instagram',
        'linkedin' => 'linkedin',
        'youtube' => 'youtube',
        'whatsapp' => 'whatsapp',
    ];
@endphp
@if($hasFooterContent)
<footer>
    <div class="container footer-grid">
        @if($hasFooterBrandContent)
            <div class="footer-col footer-brand">
                @if(filled($storeFooterLogo))
                    <img class="footer-logo" src="{{ $storeFooterLogo }}" alt="{{ __('ecommerce::ecommerce.site_name') }}">
                @endif
                @if(filled($storeDescription))
                    <p>{{ $storeDescription }}</p>
                @endif
            </div>
        @endif
    </div>
    @if($hasFooterLinks || $hasStoreSocialLinks || filled($copyrightText))
        <div class="container copyright">
            @if($hasFooterLinks)
                <div class="footer-links">
                    @if(filled($storePrivacyUrl))
                        <a href="{{ $storePrivacyUrl }}">@lang('ecommerce::ecommerce.privacy_policy')</a>
                    @endif
                    @if(filled($storeTermsUrl))
                        <a href="{{ $storeTermsUrl }}">@lang('ecommerce::ecommerce.terms_conditions')</a>
                    @endif
                    @if(filled($contactLink))
                        <a href="{{ $contactLink }}">@lang('ecommerce::ecommerce.contact_us')</a>
                    @endif
                </div>
            @endif
            @if($hasStoreSocialLinks)
                <div class="socials">
                    @foreach($storeSocialLinks as $network => $url)
                        @if(filled($url))
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                aria-label="{{ ucfirst($network) }}">
                                <i class="fa fa-{{ $socialIcons[$network] ?? 'link' }}" aria-hidden="true"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
            @if(filled($copyrightText))
                <span>© {{ now()->year }} {{ $copyrightText }}</span>
            @endif
        </div>
    @endif
</footer>
@endif
@include('ecommerce::partials.legal-content-modals')
