@php
    // Document extensions are matched case-insensitively and safely handle a
    // null original_name or a name without a dot.
    $isDocumentFile = function (?string $name): bool {
        return in_array(strtolower(pathinfo((string) $name, PATHINFO_EXTENSION)), ['pdf', 'csv', 'xls', 'xlsx'], true);
    };
@endphp

<div class="tab-pane fade p-0 border-0" id="files-tab-pane"
     role="tabpanel" aria-labelledby="files-tab" tabindex="0" wire:ignore.self>

    <div class="mt-0">
        <form method="POST" wire:submit.prevent="updateFiles" enctype="multipart/form-data">
            <div class="row gy-3 mt-3">
                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.Company Logo')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model="company_logo"
                                class="form-control @error('company_logo') is-invalid @enderror"
                                name="company_logo">
                            @error('company_logo') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($company_logo_file))
                            @if($company_logo_file)
                                @if($isDocumentFile($company_logo_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $company_logo_file }}">{{ $company_logo_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $company_logo_file }}">
                                            <img src="{{ $company_logo_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.Company Logo')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.Owner ID Or The Authorized Person')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="owner_id"
                                class="form-control @error('owner_id') is-invalid @enderror"
                                name="owner_id">
                            @error('owner_id') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($owner_id_file))
                            @if($owner_id_file)
                                @if($isDocumentFile($owner_id_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $owner_id_file }}">{{ $owner_id_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $owner_id_file }}">
                                            <img src="{{ $owner_id_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.Owner ID Or The Authorized Person')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif

                    </div>
                </div>


                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.First Copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="menu_copy"
                                class="form-control @error('menu_copy') is-invalid @enderror"
                                name="menu_copy">
                            @error('menu_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($menu_copy_file))
                            @if($menu_copy_file)
                                @if($isDocumentFile($menu_copy_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $menu_copy_file }}">{{ $menu_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $menu_copy_file }}">
                                            <img src="{{ $menu_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.First Copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.CR Copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="cr_copy"
                                class="form-control @error('cr_copy') is-invalid @enderror"
                                name="cr_copy">
                            @error('cr_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($cr_copy_file))
                            @if($cr_copy_file)
                                @if($isDocumentFile($cr_copy_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $cr_copy_file }}">{{ $cr_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $cr_copy_file }}">
                                            <img src="{{ $cr_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.CR Copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.National Address Copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="national_address_copy"
                                class="form-control @error('national_address_copy') is-invalid @enderror"
                                name="national_address_copy">
                            @error('national_address_copy') <span
                                class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($national_address_copy_file))
                            @if($national_address_copy_file)
                                @if($isDocumentFile($national_address_copy_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $national_address_copy_file }}">{{ $national_address_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $national_address_copy_file }}">
                                            <img src="{{ $national_address_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.National Address Copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.Signed Contract Copy ')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="signed_contract_copy"
                                class="form-control @error('signed_contract_copy') is-invalid @enderror"
                                name="signed_contract_copy">
                            @error('signed_contract_copy') <span
                                class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($signed_contract_copy_file))
                            @if($signed_contract_copy_file)
                                @if($isDocumentFile($signed_contract_copy_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $signed_contract_copy_file }}">{{ $signed_contract_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $signed_contract_copy_file }}">
                                            <img src="{{ $signed_contract_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.Signed Contract Copy ')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif


                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.Bank Account Details')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="bank_account_details"
                                class="form-control @error('bank_account_details') is-invalid @enderror"
                                name="signed_contract_copy">
                            @error('bank_account_details') <span
                                class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($bank_account_details_file))
                            @if($bank_account_details_file)
                                @if($isDocumentFile($bank_account_details_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $bank_account_details_file }}">{{ $bank_account_details_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $bank_account_details_file }}">
                                        <img src="{{ $bank_account_details_file }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="@lang('marketplace::home.Bank Account Details')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif


                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title" style="font-size: 22px">
                                @lang('marketplace::home.Vat Certificated')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="vat_certificated"
                                class="form-control @error('vat_certificated') is-invalid @enderror"
                                name="signed_contract_copy">
                            @error('vat_certificated') <span
                                class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($vat_certificated_file))
                            @if($vat_certificated_file)
                                @if($isDocumentFile($vat_certificated_original_name))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $vat_certificated_file }}">{{ $vat_certificated_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $vat_certificated_file }}">
                                            <img src="{{ $vat_certificated_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('marketplace::home.Vat Certificated')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('marketplace::home.No file attached yet')</div>
                        @endif


                    </div>
                </div>

            </div>

            @MerchantHasRole(['edit files_profile'])
            <div class="row gy-3 mt-3">
                <div class="col-xl-4 d-grid mt-4">
                    <button type="submit" class="btn btn-lg btn-primary" wire:loading.attr="disabled" wire:target="updateFiles">
                        <span class="indicator-label" wire:loading.class="d-none" wire:target="updateFiles">
                            @lang('marketplace::home.Save')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="updateFiles">
                            @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endMerchantHasRole
        </form>
    </div>
</div>
