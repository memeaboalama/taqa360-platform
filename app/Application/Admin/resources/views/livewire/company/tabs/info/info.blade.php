<div>
    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateBasicInfo">
            <div class="row gx-5">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                    <div class="card custom-card shadow-none mb-0">
                        <div class="card-body p-0">
                            <div class="row gy-3">
                                <div class="col-xl-4">
                                    <label for="name" class="form-label text-black">{{ __('admin::home.Company Name') }}</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                                           placeholder="{{ __('admin::home.Company Name') }}" wire:model.defer="company_name">
                                    @error('company_name') <span
                                        class="text-danger  d-block">{{ $message }}</span> @enderror
                                </div>


                                <div class="col-xl-4">
                                    <label for="email"
                                           class="form-label text-black">{{ __('admin::home.email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                           wire:model.defer="email"
                                           placeholder="{{ __('admin::home.email') }}">
                                    @error('email') <span
                                        class="text-danger  d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-xl-4">
                                    <label for="phone" class="form-label text-black">{{ __('admin::home.Phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone"
                                           id="phone"
                                           placeholder="{{ __('admin::home.Phone') }}">
                                    @error('phone') <span
                                        class="text-danger  d-block">{{ $message }}</span> @enderror
                                </div>



                            </div>

                            <div class="row mt-2">
                                <div class="col-xl-4">
                                    <label for="branch-country"
                                           class="form-label text-black ">@lang('admin::home.Country') <span class="text-danger">*</span></label>

                                    <div wire:ignore>
                                        <select class="form-control @error('country_id') is-invalid @enderror selectCountry"
                                                data-trigger
                                                name="country_id"
                                                id="branch-country-add">
                                            <option value="">@lang('admin::home.Select')</option>
                                            @foreach($countries as $country)
                                                <option
                                                    value="{{ $country  }}" {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id') <span
                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                </div>


                                <div class="col-xl-4">
                                    <div>
                                        <label for="branch-city" class="form-label text-black">@lang('admin::home.Region') <span class="text-danger">*</span></label>
                                        <select class="form-control selectCity"
                                                wire:model="region_id"
                                                id="branch-city">
                                            <option value="">@lang('admin::home.Select')</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('city_id') <span
                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-xl-4">
                                    <div>
                                        <label for="branch-city" class="form-label text-black">@lang('admin::home.City') <span class="text-danger">*</span></label>
                                        <select class="form-control @error('city_id') is-invalid @enderror selectCity"
                                                wire:model="city_id"
                                                id="branch-city">
                                            <option value="">@lang('admin::home.Select')</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('city_id') <span
                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                </div>

                                

                                {{-- <div class="col-xl-4">
                                    <div>
                                        <label for="branch-cityasa"
                                               class="form-label text-black">@lang('admin::home.District') <span class="text-danger">*</span></label>
                                        <select class="form-control @error('distract_id') is-invalid @enderror selectDistrict"
                                                wire:model.lazy="district_id"
                                                id="branch-cityasa">
                                            <option value="">@lang('admin::home.Select')</option>
                                            @foreach($districts as $district)
                                                <option
                                                    value="{{ $district->id }}">{{ $district->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('distract_id') <span
                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                </div> --}}
                            </div>
                            <div class="row gy-3 mt-2">
                                <div class="col-xl-5">
                                    <label for="password"
                                           class="form-label text-black">{{ __('admin::home.Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                           placeholder="************"
                                           wire:model.defer="password"
                                    >
                                    @error('password') <span
                                        class="text-danger  d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-xl-5">
                                    <label for="password-confirm" class="form-label text-black">{{ __('admin::home.Confirm Password') }}</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password-confirm"
                                           placeholder="************"
                                           wire:model.defer="password_confirmation">
                                </div>

                                <div class="col-xl-2">
                                    <label for="category-active"
                                           class="form-label text-black">{{ __('admin::home.status') }}</label>
                                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                        <input id="category-active"
                                               type="checkbox" {{ $status == 'active' ? 'checked' : '' }}
                                        >
                                        <label for="category-active" class="label-success"
                                               wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                    </div>
                                    @error('status') <span
                                        class="text-danger  d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row y-3 mt-2">
                                <div class="col-xl-12">
                                    <label for="description" class="form-label text-black">@lang('admin::home.Description') <span class="text-danger">*</span></label>
                                    <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="{{  __('admin::home.Description') }}" wire:model.defer="description"></textarea>
                                    @error("description")
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @AdminHasRole(['edit_operating companies'])
            <div class="row">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('admin::home.Save')
                                </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('admin::home.Wait') <span
                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                    </button>
                </div>
            </div>
            @endAdminHasRole
        </form>
    </div>
</div>
@push('js')
    <script>
        $(document).on('change', '.selectCountry', function () {
            $('.selectCity').empty();
            window.livewire.emit('updateCountry', $(this).val());
        });


        // $(document).on('change', '.selectCity', function () {
        //     $('.selectDistrict').empty();
        //     window.livewire.emit('updateCity', $(this).val());
        // });

        $(document).on('change', '.selectDistrict', function () {
            window.livewire.emit('updateDistrict', $(this).val());
        });


        Livewire.on('renderCities', cities => {
            let element = $('.selectCity');
            element.append(`<option value="">select</option>`);
            for (let i = 0; i < cities.length; i++) {
                element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
            }
            // Livewire.emit('renderSelect');
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
