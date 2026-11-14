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
    $storeFooterLogo = $storeFooter['logo'] ?? asset($platformBrand['logo_white']);
@endphp
@php
    $storeTerms = data_get($legalContent, 'terms_conditions_' . $storeLocale)
        ?? data_get($legalContent, 'terms_conditions_en')
        ?? data_get($legalContent, 'terms_conditions_ar')
        ?? '';
    $storePrivacy = data_get($legalContent, 'policies_' . $storeLocale)
        ?? data_get($legalContent, 'policies_en')
        ?? data_get($legalContent, 'policies_ar')
        ?? '';
@endphp
<footer>
    <div class="container footer-grid">
        <div class="footer-col footer-brand"><img class="footer-logo"
                src="{{ $storeFooterLogo }}" alt="{{ __('ecommerce::ecommerce.site_name') }}" style="filter: brightness(0) invert(1);">
            <p>@lang('ecommerce::ecommerce.footer_description')</p>
        </div>
    </div>
    <div class="container copyright">
        <div class="footer-links">
            @if(filled($storePrivacy))
                <a href="#privacyPolicyModal" data-bs-toggle="modal" aria-controls="privacyPolicyModal">@lang('ecommerce::ecommerce.privacy_policy')</a>
            @endif
            @if(filled($storeTerms))
                <a href="#termsConditionsModal" data-bs-toggle="modal" aria-controls="termsConditionsModal">@lang('ecommerce::ecommerce.terms_conditions')</a>
            @endif
            <a href="{{ $storeFooter['links']['contact'] ?? '#' }}">@lang('ecommerce::ecommerce.contact_us')</a>
        </div>
        <div class="socials"><a href="https://wa.me/" aria-label="@lang('ecommerce::ecommerce.whatsapp')"><i class="fa fa-whatsapp"></i></a><a
                href="https://www.facebook.com/" aria-label="@lang('ecommerce::ecommerce.facebook')"><i class="fa fa-facebook"></i></a><a
                href="https://www.instagram.com/" aria-label="@lang('ecommerce::ecommerce.instagram')"><i class="fa fa-instagram"></i></a></div>
        <span>{{ $storeLocalized($storeFooter['copyright'] ?? null, __('ecommerce::ecommerce.copyright')) }}</span>
    </div>
</footer>
@include('ecommerce::partials.legal-content-modals')
