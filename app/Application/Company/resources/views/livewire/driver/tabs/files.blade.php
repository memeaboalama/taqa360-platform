<div>
    <div class="mt-0">
        <form method="POST" wire:submit.prevent="updateFiles" enctype="multipart/form-data">
            <div class="row gy-3 mt-3">
                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.license_copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model="license_copy"
                                class="form-control @error('license_copy') is-invalid @enderror"
                                name="license_copy">
                            @error('license_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($license_copy_file))
                            @if($license_copy_file)
                                @if(in_array(substr($license_copy_original_name, strpos($license_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $license_copy_file }}" target="_blank">{{ $license_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $license_copy_file }}">
                                            <img src="{{ $license_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('company::home.license_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('company::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>


                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.car_copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="car_copy"
                                class="form-control @error('car_copy') is-invalid @enderror"
                                name="car_copy">
                            @error('car_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        @if(isset($car_copy_file))
                            @if($car_copy_file)
                                @if(in_array(substr($car_copy_original_name, strpos($car_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $car_copy_file }}" target="_blank">{{ $car_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $car_copy_file }}">
                                            <img src="{{ $car_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('company::home.car_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('company::home.No file attached yet')</div>
                        @endif

                    </div>
                </div>


                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.residence_permit_copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="residence_permit_copy"
                                class="form-control @error('residence_permit_copy') is-invalid @enderror"
                                name="residence_permit_copy">
                            @error('residence_permit_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($residence_permit_copy_file))
                            @if($residence_permit_copy_file)
                                @if(in_array(substr($residence_permit_copy_original_name, strpos($residence_permit_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $residence_permit_copy_file }}" target="_blank">{{ $residence_permit_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $residence_permit_copy_file }}">
                                            <img src="{{ $residence_permit_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('company::home.residence_permit_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('company::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.iban_copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="iban_copy"
                                class="form-control @error('iban_copy') is-invalid @enderror"
                                name="iban_copy">
                            @error('iban_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($iban_copy_file))
                            @if($iban_copy_file)
                                @if(in_array(substr($iban_copy_original_name, strpos($iban_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $iban_copy_file }}" target="_blank">{{ $iban_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $iban_copy_file }}">
                                            <img src="{{ $iban_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('company::home.iban_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('company::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.car_registration_copy')
                            </div>
                        </div>
                        <div class="card-body">
                            <input
                                type="file"
                                wire:model.defer="car_registration_copy"
                                class="form-control @error('car_registration_copy') is-invalid @enderror"
                                name="car_registration_copy">
                            @error('car_registration_copy') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                        @if(isset($car_registration_copy_file))
                            @if($car_registration_copy_file)
                                @if(in_array(substr($car_registration_copy_original_name, strpos($car_registration_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $car_registration_copy_file }}" target="_blank">{{ $car_registration_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $car_registration_copy_file }}">
                                            <img src="{{ $car_registration_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('company::home.car_registration_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('company::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="row gy-3 mt-3">
                <div class="col-xl-4 d-grid mt-4">
                    <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('company::home.Save')
                                </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('company::home.Wait') <span
                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
