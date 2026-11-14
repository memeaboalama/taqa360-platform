@php
    $legalLocale = $currentLocale ?? app()->getLocale();
    $termsContent = data_get($legalContent, 'terms_conditions_' . $legalLocale)
        ?? data_get($legalContent, 'terms_conditions_en')
        ?? data_get($legalContent, 'terms_conditions_ar')
        ?? '';
    $privacyContent = data_get($legalContent, 'policies_' . $legalLocale)
        ?? data_get($legalContent, 'policies_en')
        ?? data_get($legalContent, 'policies_ar')
        ?? '';
@endphp

@if(filled($termsContent))
    <div class="modal fade" id="termsConditionsModal" tabindex="-1" aria-labelledby="termsConditionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="termsConditionsModalLabel">@lang('ecommerce::ecommerce.terms_conditions')</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('ecommerce::ecommerce.close')"></button>
                </div>
                <div class="modal-body" dir="{{ in_array($legalLocale, ['ar', 'he'], true) ? 'rtl' : 'ltr' }}">
                    {!! $termsContent !!}
                </div>
            </div>
        </div>
    </div>
@endif

@if(filled($privacyContent))
    <div class="modal fade" id="privacyPolicyModal" tabindex="-1" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="privacyPolicyModalLabel">@lang('ecommerce::ecommerce.privacy_policy')</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('ecommerce::ecommerce.close')"></button>
                </div>
                <div class="modal-body" dir="{{ in_array($legalLocale, ['ar', 'he'], true) ? 'rtl' : 'ltr' }}">
                    {!! $privacyContent !!}
                </div>
            </div>
        </div>
    </div>
@endif
