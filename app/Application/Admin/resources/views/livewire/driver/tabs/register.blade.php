<div class="row gy-3">

        <div class="col-xl-8">
            <label for="name" class="form-label text-default fw-bolder">@lang('driver::home.Full Name') <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control form-control-lg br-10"
                   id="name"
                   wire:model.defer="name"
                   placeholder="@lang('driver::home.Full Name')" disabled>
                   @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-xl-4">
            <label for="dateOfBirth" class="form-label text-default fw-bolder">{{__('admin::home.Date Of Birth')}}</label>
            <input type="date" class="form-control" id="dateOfBirth"
                   wire:model.defer="dateOfBirth" disabled>
            @error('dateOfBirth') <span class="text-danger d-block">{{ $message }}</span> @enderror
        </div>

        <div class="col-xl-6">
            <label for="email" class="form-label text-default fw-bolder">@lang('driver::home.Email Address')<span class="text-danger">*</span></label>
            <input type="email"
                   class="form-control form-control-lg br-10"
                   id="email"
                   wire:model.defer="email"
                   placeholder="@lang('driver::home.Email Address')" disabled>
                   @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-xl-6">
            <label for="phone" class="form-label text-default fw-bolder">@lang('driver::home.Phone Number')<span class="text-danger">*</span></label>
            <input type="tel"
                   class="form-control form-control-lg br-10"
                   id="phone"
                   wire:model.defer="phone"
                   placeholder="@lang('driver::home.example') 512345678" disabled>
            @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-xl-12">
            <div>
                <label for="IdentityTypeId" class="form-label text-black">@lang('admin::home.Identity Type')<span class="text-danger">*</span></label>
                <select class="form-control selectCity"
                        wire:model="IdentityTypeId"
                        id="IdentityTypeId" disabled>
                    <option value="">@lang('admin::home.Select')</option>
                    <option value="NV25GlPuOnQ=">مواطن</option>
                    <option value="oIcaYzeDfQQ=">مقيم</option>
                </select>
            </div>
            @error('IdentityTypeId') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="row mt-2 gy-3">
            <div class="col-xl-4">
                <label for="branch-country" class="form-label text-black">@lang('admin::home.Country') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="country_name"
                       id="country_name" disabled>
            </div>

            <div class="col-xl-4">
                <div>
                    <label for="branch-region" class="form-label text-black">@lang('admin::home.Region')<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="region"
                           id="region" disabled>
                </div>
            </div>

            <div class="col-xl-4">
                <div>
                    <label for="branch-city" class="form-label text-black">@lang('admin::home.City') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="city"
                           id="city" disabled>
                </div>
            </div>
        </div>

        <div class="row mt-2 gy-3">
            <div class="col-xl-4">
                <div>
                    <label for="car_type_id" class="form-label text-black">@lang('admin::home.Vehicle Type') <span class="text-danger">*</span></label>
                    <select class="form-control selectCity"
                            wire:model="car_type_id"
                            id="car_type_id" disabled>
                        <option value="">@lang('admin::home.Select')</option>
                        @foreach($cars_types as $type)
                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                        @endforeach
                    </select>
                </div>
                @error('car_type_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-xl-4">
                <label for="carNumber" class="form-label text-black">@lang('admin::home.Vehicle Registration Plate')</label>
                <input type="text" class="form-control" wire:model.defer="carNumber"
                    id="carNumber"
                    placeholder="@lang('admin::home.Vehicle Registration Plate')" disabled>
                @error('carNumber') <span class="text-danger d-block">{{ $message }}</span> @enderror
            </div>
            <div class="col-xl-4">
                <label for="vehicleSequenceNumber" class="form-label text-black">@lang('admin::home.Vehicle ID') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="vehicleSequenceNumber"
                    id="vehicleSequenceNumber"
                    placeholder="@lang('admin::home.Vehicle ID')" disabled>
                @error('vehicleSequenceNumber') <span class="text-danger d-block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row mt-2 gy-3">
            <div class="col-xl-12 mt-2">
                <label for="company-filter" class="form-label">{{ __('admin::home.Company Name') }}</label>
                <select class="form-control" id="company-filter" wire:model.defer="company_id">
                    <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            @error('company_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-xl-12 d-grid mt-2">
            <a wire:click="store" wire:loading.attr="disabled" wire:target="store" class="btn btn-lg btn-primary">
                <span class="indicator-label fw-bolder" wire:loading.class="d-none" wire:target="store">
                    @lang('admin::home.approve')
                </span>
                <span class="d-none fw-bolder" wire:loading.class.remove="d-none" wire:target="store">
                    @lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </a>
        </div>
    </div>

@push('js')
    <script>
        $(document).on('change', '.selectDistrict', function () {
            window.livewire.emit('updateDistrict', $(this).val());
        });

        Livewire.on('renderCities', cities => {
            let element = $('.selectCity');
            element.append(`<option value="">select</option>`);
            for (let i = 0; i < cities.length; i++) {
                element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
            }
        });

        Livewire.on('renderDistricts', districts => {
            console.log(districts);
            for (let i = 0; i < districts.length; i++) {
                $('.selectDistrict').append(`<option value="${districts[i].id}">${districts[i]?.title?.en}</option>`);
            }
            Livewire.emit('renderSelect');
        });
    </script>
@endpush