@php
    // Document extensions are matched case-insensitively and safely handle a
    // null original_name or a name without a dot.
    $isDocumentFile = function (?string $name): bool {
        return in_array(strtolower(pathinfo((string) $name, PATHINFO_EXTENSION)), ['pdf', 'csv', 'xls', 'xlsx'], true);
    };
@endphp

<div>
    <div class="mt-0">
        <div class="row gy-3 mt-3">
            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.Company Logo')
                        </div>
                    </div>
                    @if($company_logo_obj)
                        @if($company_logo_file)
                            @if($isDocumentFile($company_logo_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $company_logo_file }}" target="_blank">{{ $company_logo_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $company_logo_file }}">
                                        <img src="{{ $company_logo_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.Company Logo')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $company_logo_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.Owner ID Or The Authorized Person')
                        </div>
                    </div>

                    @if($owner_id_obj)
                        @if($owner_id_file)
                            @if($isDocumentFile($owner_id_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $owner_id_file }}" target="_blank">{{ $owner_id_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $owner_id_file }}">
                                        <img src="{{ $owner_id_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.Owner ID Or The Authorized Person')">
                                    </a>

                                </div>

                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $owner_id_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif

                </div>
            </div>


            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.First Copy')
                        </div>
                    </div>
                    @if($menu_copy_obj)
                        @if($menu_copy_file)
                            @if($isDocumentFile($menu_copy_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $menu_copy_file }}" target="_blank">{{ $menu_copy_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $menu_copy_file }}">
                                        <img src="{{ $menu_copy_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.First Copy')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $menu_copy_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.CR Copy')
                        </div>
                    </div>
                    @if($cr_copy_obj)
                        @if($cr_copy_file)
                            @if($isDocumentFile($cr_copy_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $cr_copy_file }}" target="_blank">{{ $cr_copy_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $cr_copy_file }}">
                                        <img src="{{ $cr_copy_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.CR Copy')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $cr_copy_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.National Address Copy')
                        </div>
                    </div>

                    @if($national_address_copy_obj)
                        @if($national_address_copy_file)
                            @if($isDocumentFile($national_address_copy_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $national_address_copy_file }}" target="_blank">{{ $national_address_copy_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $national_address_copy_file }}">
                                        <img src="{{ $national_address_copy_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.National Address Copy')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $national_address_copy_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.Signed Contract Copy ')
                        </div>
                    </div>
                    @if($signed_contract_copy_obj)
                        @if($signed_contract_copy_file)
                            @if($isDocumentFile($signed_contract_copy_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $signed_contract_copy_file }}" target="_blank">{{ $signed_contract_copy_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $signed_contract_copy_file }}">
                                        <img src="{{ $signed_contract_copy_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.Signed Contract Copy ')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $signed_contract_copy_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif


                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.Bank Account Details')
                        </div>
                    </div>
                    @if($bank_account_details_obj)
                        @if($bank_account_details_file)
                            @if($isDocumentFile($bank_account_details_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $bank_account_details_file }}" target="_blank">{{ $bank_account_details_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $bank_account_details_file }}">
                                        <img src="{{ $bank_account_details_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.Bank Account Details')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $bank_account_details_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif


                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card border-box-1">
                    <div class="card-header">
                        <div class="card-title">
                            @lang('admin::home.Vat Certificated')
                        </div>
                    </div>
                    @if($vat_certificated_obj)
                        @if($vat_certificated_file)
                            @if($isDocumentFile($vat_certificated_original_name))
                                <h5 class="mt-5 mx-auto"><a href="{{ $vat_certificated_file }}" target="_blank">{{ $vat_certificated_original_name }}</a></h5>
                            @else
                                <div class="mb-2 mx-auto" style="width:150px;">
                                    <a href="{{ $vat_certificated_file }}">
                                        <img src="{{ $vat_certificated_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('admin::home.Vat Certificated')">
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="mb-2 mx-auto mt-2">{{ __('admin::home.File unavailable') }}: {{ $vat_certificated_original_name }}</div>
                        @endif
                    @else
                        <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
