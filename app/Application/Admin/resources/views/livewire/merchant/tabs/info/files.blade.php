@php
    $fileCards = [
        [
            'label' => 'admin::home.Company Logo',
            'obj'   => $company_logo_obj,
            'url'   => $company_logo_file,
            'name'  => $company_logo_original_name,
        ],
        [
            'label' => 'admin::home.Owner ID Or The Authorized Person',
            'obj'   => $owner_id_obj,
            'url'   => $owner_id_file,
            'name'  => $owner_id_original_name,
        ],
        [
            'label' => 'admin::home.First Copy',
            'obj'   => $menu_copy_obj,
            'url'   => $menu_copy_file,
            'name'  => $menu_copy_original_name,
        ],
        [
            'label' => 'admin::home.CR Copy',
            'obj'   => $cr_copy_obj,
            'url'   => $cr_copy_file,
            'name'  => $cr_copy_original_name,
        ],
        [
            'label' => 'admin::home.National Address Copy',
            'obj'   => $national_address_copy_obj,
            'url'   => $national_address_copy_file,
            'name'  => $national_address_copy_original_name,
        ],
        [
            'label' => 'admin::home.Signed Contract Copy ',
            'obj'   => $signed_contract_copy_obj,
            'url'   => $signed_contract_copy_file,
            'name'  => $signed_contract_copy_original_name,
        ],
        [
            'label' => 'admin::home.Bank Account Details',
            'obj'   => $bank_account_details_obj,
            'url'   => $bank_account_details_file,
            'name'  => $bank_account_details_original_name,
        ],
        [
            'label' => 'admin::home.Vat Certificated',
            'obj'   => $vat_certificated_obj,
            'url'   => $vat_certificated_file,
            'name'  => $vat_certificated_original_name,
        ],
    ];

    $mode = in_array($mode ?? 'files', ['files', 'images'], true) ? $mode : 'files';

    // The Files tab lists documents (and any not-yet-uploaded document slot),
    // while the Images tab lists only uploaded images.
    $fileCards = collect($fileCards)->filter(function (array $card) use ($mode) {
        $isImage = (bool) $card['obj']?->is_image;

        return $mode === 'images'
            ? ($card['obj'] && $isImage)
            : (!$card['obj'] || !$isImage);
    })->values();
@endphp

<div>
    <div class="mt-0">
        @if($fileCards->isEmpty())
            <div class="alert alert-info mt-3" role="alert">
                @lang($mode === 'images' ? 'admin::home.No images uploaded yet' : 'admin::home.No file attached yet')
            </div>
        @else
            <div class="row gy-3 mt-3">
                @foreach($fileCards as $card)
                    <div class="col-xl-6">
                        <div class="card custom-card border-box-1">
                            <div class="card-header">
                                <div class="card-title">
                                    @lang($card['label'])
                                </div>
                            </div>
                            @if($card['obj'])
                                @if($card['url'])
                                    @if($card['obj']->is_image)
                                        <div class="mb-2 mx-auto" style="width:150px;">
                                            <a href="{{ $card['url'] }}" target="_blank" rel="noopener">
                                                <img src="{{ $card['url'] }}"
                                                     class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                     alt="@lang($card['label'])">
                                            </a>
                                        </div>
                                    @else
                                        <h5 class="mt-5 mx-auto">
                                            <a href="{{ $card['url'] }}" target="_blank" rel="noopener">{{ $card['name'] }}</a>
                                        </h5>
                                    @endif
                                @else
                                    <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $card['name'] }}</div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
