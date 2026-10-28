<div class="tab-pane active show fade p-0 border-0" id="basicInfo-tab-pane"
     role="tabpanel" aria-labelledby="basicInfo-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateBasicInfo" enctype="multipart/form-data">
            <div class="row gy-3">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                    <div class="col-xl-4">
                        <label for="companyname.{{$localeCode}}" class="form-label text-default fw-bolder">{{ __('marketplace::home.Restaurant Name') }} {{$properties['native']}} <span class="text-danger">*</span>
                            <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-companyname.{{$localeCode}}"
                                onclick="translateLocalizedField('companyname.{{$localeCode}}','companyname.{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                title="{{ __('admin::main.actions.translate') }}"
                                style="cursor: pointer;">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </label>
                        <input type="text"
                               class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                               id="companyname.{{$localeCode}}"
                               wire:model.defer="company_name.{{$localeCode}}"
                               placeholder="{{ __('marketplace::home.Restaurant Name') }}"
                               onblur="translateLocalizedField('companyname.{{$localeCode}}','companyname.{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                        @error('company_name.' . $localeCode) <span class="d-block text-danger">{{ $message }}</span>@enderror
                    </div>
                @endforeach
                <div class="col-xl-4">
                    <label for="fullname" class="form-label text-default fw-bolder">{{ __('marketplace::home.Full Name') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('user_name') is-invalid @enderror"
                           id="fullname"
                           value="{{ $this->merchant->user_name }}"
                           wire:model.defer="user_name"
                           placeholder="{{ __('marketplace::home.Full Name') }}">
                    @error('user_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-4">
                    <label for="tax_number" class="form-label text-default fw-bolder">{{ __('marketplace::home.Tax Number') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('tax_number') is-invalid @enderror"
                           id="tax_number"
                           wire:model.defer="tax_number"
                           placeholder="{{ __('marketplace::home.Tax Number') }}">
                    @error('tax_number') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-4">
                    <label for="phone" class="form-label text-default fw-bolder">{{ __('marketplace::home.Phone Number') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control-lg @error('phone') is-invalid @enderror"
                           id="phone"
                           wire:model.defer="phone"
                           placeholder="{{ __('marketplace::home.Phone Number') }}">
                    @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-4">
                    <label for="email" class="form-label text-default fw-bolder">{{ __('marketplace::home.Email Address') }}</label>
                    <input type="email"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           id="email"
                           disabled
                           wire:model.defer="email"
                           placeholder="Email Address">
                    @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

            </div>
            <div class="row gy-3 mt-3">
                <div class="col-xl-4">
                    <label for="branch-country"
                           class="form-label text-black fw-bolder">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

                    <div wire:ignore>
                        <select class="form-control selectCountry"
                                data-trigger
                                name="country_id"
                                id="branch-country-add">
                            <option value="">@lang('marketplace::home.Select')</option>
                            @foreach($counties as $country)
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
                        <label for="branch-region" class="form-label text-black">@lang('company::home.Region') <span class="text-danger">*</span></label>
                        <select class="form-control selectRegion"
                                wire:model="region_id"
                                id="branch-region">
                            <option value="">@lang('company::home.select')</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('region_id') <span
                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-4">
                    <div>
                        <label for="branch-city" class="form-label text-black fw-bolder">@lang('marketplace::home.City') <span class="text-danger">*</span></label>
                        <select class="form-control selectCity"
                                wire:model="city_id"
                                id="branch-city">
                            <option value="">@lang('marketplace::home.Select')</option>
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
                               class="form-label text-black fw-bolder">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                        <select class="form-control selectDistrict"
                                wire:model.lazy="distract_id"
                                id="branch-cityasa">
                            <option value="">@lang('marketplace::home.Select')</option>
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
            <div class="row gy-3 mt-3">
                <div class="col-xl-6">
                    <label for="marketplace_category_id" class="form-label text-black fw-bolder">{{ __('marketplace::home.Choose Main Categories') }} <span class="text-danger">*</span></label>
                    <select class="form-control @error('marketplace_category_id') is-invalid @enderror" id="marketplace_category_id" wire:model.defer="marketplace_category_id">
                        <option value="">-- {{ __('marketplace::home.Choose Main Categories') }} --</option>
                        @foreach($marketplaceCategories as $value)
                            <option value="{{ $value->id }}">{{ $value?->title }}</option>
                        @endforeach
                    </select>
                    @error('marketplace_category_id') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>

            </div>


            <div class="row gy-3 mt-2">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div class="col-xl-6">
                        <label for="description" class="form-label text-black fw-bolder">@lang('marketplace::home.Company Description') {{$properties['native']}}</label>
                        <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="@lang('marketplace::home.Company Description')" wire:model.defer="description.{{$localeCode}}"> {{$app_logo}}</textarea>
                        @error("description." . $localeCode)
                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="row gy-3 mt-3">
                <div class="col-xl-6">
                    <label for="app_logo" class="form-label text-black fw-bolder">@lang('marketplace::home.logo')</label>
                    <input type="file" class="form-control" id="app_logo"  wire:model.defer="app_logo"   accept="image/png, image/jpeg, image/webp">
                    <label for="app_logo" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                    @error("app_logo")
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-xl-2">
                    <a href="{{ $this->app_logo_path }}" target="_blank">
                        <img src="{{ $this->app_logo_path }}"
                             class="img-fluid rounded-start" alt="@lang('marketplace::home.logo')" width="100" height="100">
                    </a>
                </div>
            </div>
            @MerchantHasRole(['edit basic info_profile'])
            <div class="row gy-3 mt-3">
                <div class="col-xl-4 d-grid mt-4">
                    <button type="submit" class="btn btn-lg btn-primary" wire:loading.attr="disabled" wire:target="updateBasicInfo">
                        <span class="indicator-label fw-bolder" wire:loading.class="d-none" wire:target="updateBasicInfo">
                            @lang('marketplace::home.Save')
                        </span>
                        <span class="d-none fw-bolder" wire:loading.class.remove="d-none" wire:target="updateBasicInfo">
                            @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endMerchantHasRole
        </form>
    </div>
</div>

<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" width="500vh" height="600" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">@lang('marketplace::home.crop image before upload image')</h5>
                {{-- <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true" >×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="img-container d-flex justify-content-center" >
                    <div class="row">
                        <div style="width: 700px; height: 500px;" class="">
                            <div id="preview" ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="close" >@lang('marketplace::home.Cancel')</button>
                <button type="button" class="btn btn-primary" id="crop">@lang('marketplace::home.Crop')</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function translateLocalizedField(sourceId, targetId, from, to, force) {
            if (typeof window.__translationInProgress === 'undefined') {
                window.__translationInProgress = {};
            }
            var sourceEl = document.getElementById(sourceId);
            var targetEl = document.getElementById(targetId);
            if (!sourceEl || !targetEl) return;

            var text = (sourceEl.value || '').trim();
            if (!text) return;

            if (!force && (targetEl.value || '').trim() !== '') return;

            if (window.__translationInProgress[sourceId]) return;
            window.__translationInProgress[sourceId] = true;

            var btn = document.getElementById('translate-' + sourceId);
            var originalHtml = btn ? btn.innerHTML : '';

            if (btn) {
                btn.disabled = true;
                btn.classList.add('disabled');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            }

            fetch('https://api.mymemory.translated.net/get?q=' + encodeURIComponent(text) + '&langpair=' + from + '|' + to)
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    var translated = data && data.responseData && data.responseData.translatedText;
                    if (translated) {
                        targetEl.value = translated;
                        targetEl.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                        targetEl.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                })
                .catch(function(error) {
                    console.error('Translation failed:', error);
                })
                .finally(function() {
                    window.__translationInProgress[sourceId] = false;
                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('disabled');
                        btn.innerHTML = originalHtml;
                    }
                });
        }

        $(document).on('change', '.selectCountry', function () {
            $('.selectCity').empty();
            $('.selectRegion').empty();
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
            
            
            
            
            
            
            $(document).ready(function() {
                var $preview = $('#preview');
                var $image = $('#app_logo');
                var cropper;
                var file ;
                var orginfile;
                $image.change(function(e) {
                    file = e.target.files[0];
                    orginfile = e.target.files[0];
                    var url = URL.createObjectURL(file);
                    
                    console.log(file);
                    if (cropper) {
                        cropper.destroy();
                    }
                    
            $preview.empty();
            $preview.append('<img style="width: 700px; height: 500px;" src="' + url + '">');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModal')).show();
        });

        $('#cropModal').on('shown.bs.modal', function() {
            cropper = new Cropper($preview.find('img')[0], {
                aspectRatio:1,
                viewMode: 1,
                autoCropArea:1,
                movable: false,
                zoomable: false,
                rotatable: false,
                scalable: false,
                minCropBoxWidth: 200,
                maxCropBoxWidth: 250,
                minCropBoxHeight: 200,
                maxCropBoxHeight: 250
            });
        });
        $("#crop").click(function(){
        var cropData = cropper.getCroppedCanvas().toDataURL('image/png', 0.8);
            canvas = cropper.getCroppedCanvas({
                width: 250,
                height: 250,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result; 
                    $("input[name='image_base64']").val(base64data);
                    $('.img-fluid.rounded-start').attr('src', base64data);
                    // window.livewire.emit('imageUpload', base64data ,file);
                    window.livewire.emit('imageUpload', base64data, {
                        filename: file.name,
                        type: file.type,
                        size: file.size
                    });
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModal')).hide();
                }
            });
        });  
        
        $('#close').click(function(){
           
           bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModal')).hide();

       });
    });


    </script>
@endpush
