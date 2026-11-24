{{--
    Shared store footer. Uses $footerData when the controller provides it, otherwise safe defaults.
--}}
@php
    $storeLocale = $currentLocale ?? app()->getLocale();
    $storeLocalized = static function ($value, $fallback = '') use ($storeLocale) {
        if (is_array($value)) {
            return $value[$storeLocale] ?? $value['en'] ?? reset($value) ?? $fallback;
        }
        return $value ?: $fallback;
    };
    $storeFooter = $footerData ?? [];
    $storeFooterLogo = data_get($storeFooter, 'logo');
    $storeDescription = $storeLocalized(data_get($storeFooter, 'description'), '');
    $storeTermsUrl = data_get($storeFooter, 'links.terms');
    $storePrivacyUrl = data_get($storeFooter, 'links.privacy');
    $contactLink = data_get($storeFooter, 'links.contact');
    $copyrightText = $storeLocalized(
        $storeFooter['copyright'] ?? null,
        __('ecommerce::ecommerce.copyright')
    );
    $copyrightText = preg_replace('/\s*©\s*(?:[0-9٠-٩]{4}\s*)?/u', ' ', (string) $copyrightText);
    $copyrightText = preg_replace('/^\s*[0-9٠-٩]{4}\s*/u', '', $copyrightText);
    $copyrightText = trim($copyrightText);
@endphp
<footer>
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            @if(filled($storeFooterLogo))
                <img class="footer-logo" src="{{ $storeFooterLogo }}" alt="{{ __('ecommerce::ecommerce.site_name') }}"
                    style="display:block;width:auto;height:auto;max-width:160px;max-height:65px;object-fit:contain;margin:0 auto 8px;">
            @endif
            @if(filled($storeDescription))
                <p>{{ $storeDescription }}</p>
            @endif
        </div>
    </div>
    <div class="container copyright">
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
        @include('ecommerce::partials.social-links')
        <span>© {{ now()->year }} {{ $copyrightText }}</span>
    </div>
</footer>
@include('ecommerce::partials.legal-content-modals')
