<div>
    <div class="modal fade" id="createBranch"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" wire:ignore.self
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Create new branch')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:loading.attr="disabled"
                        aria-label="Close"></button>
                </div>
                <form method="post" wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                            <div class="col-xl-6">
                                                <label for="branch-name-{{$localeCode}}" class="form-label text-black">@lang('marketplace::home.Branch Name') {{$properties['native']}} <span class="text-danger">*</span>
                                                    <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-branch-name-{{$localeCode}}"
                                                        onclick="translateLocalizedField('branch-name-{{$localeCode}}','branch-name-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                        title="{{ __('admin::main.actions.translate') }}"
                                                        style="cursor: pointer;">
                                                        <i class="ri-refresh-line"></i>
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" id="branch-name-{{$localeCode}}"
                                                    wire:model.defer="title.{{$localeCode}}"
                                                    onblur="translateLocalizedField('branch-name-{{$localeCode}}','branch-name-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                @error("title." . $localeCode) <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            @endforeach
                                            <!-- <div class="col-xl-12">
                                                    <label for="branch-menu"
                                                           class="form-label text-black ">@lang('marketplace::home.Menu Name')</label>

                                                    <div wire:ignore>
                                                        <select class="form-control"
                                                                wire:model.defer="merchant_service_id"
                                                                name="merchant_service_id">
                                                            <option value="">@lang('marketplace::home.Select') @lang('marketplace::home.Menu Name')</option>
                                                            @foreach($menus as $menu)
                                                                <option
                                                                    value="{{ $menu->id }}">{{ $menu->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('merchant_service_id') <span
                                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                                </div> -->


                                            <div class="col-xl-4">
                                                <label for="branch-country"
                                                    class="form-label text-black ">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

                                                <div wire:ignore>
                                                    <select class="form-control selectCountry"
                                                        data-trigger
                                                        name="country_id"
                                                        id="branch-country-add">
                                                        <option value="">@lang('marketplace::home.Select')</option>
                                                        @foreach($counties as $country)
                                                        <option
                                                            value="{{ $country  }}">{{ $country->title }}</option>
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
                                                    <label for="branch-city" class="form-label text-black">@lang('marketplace::home.City') <span class="text-danger">*</span></label>
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
                                                    <label for="branch-district"
                                                           class="form-label text-black">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectDistrict"
                                                            wire:model.lazy="distract_id"
                                                            id="branch-district">
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


                                    <div class="col-xl-4">
                                        <div>
                                            <label for="manager_id"
                                                class="form-label text-black">@lang('marketplace::home.Select Branch Manager')</label>
                                            <select class="form-control"
                                                wire:model.lazy="manager_id"
                                                id="manager_id">
                                                <option value="">@lang('marketplace::home.Select')</option>
                                                @foreach($employees as $employee)
                                                <option
                                                    value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('manager_id') <span
                                            class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <div>
                                            <label for="package_id"
                                                class="form-label text-black">@lang('marketplace::home.Select Package') <span class="text-danger">*</span></label>
                                            <select class="form-control"
                                                wire:model.lazy="package_id"
                                                wire:change="fullPackageTypeService()"
                                                id="package_id">
                                                <option value="">@lang('marketplace::home.Select')</option>
                                                @foreach($packages as $package)
                                                <option
                                                    value="{{ $package->id }}">{{ $package->title."    (".$package->commission.")" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('package_id') <span
                                            class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                    </div>

                                </div>

                                <div class="row gy-3 mt-2">
                                    <div class="col-xl-4">
                                        <label for="cover_image"
                                            class="form-label text-black">@lang('marketplace::home.cover_image')</label>
                                        <input type="file" class="form-control" id="cover_image"
                                            placeholder="{{ __("marketplace::home.cover_image") }}"
                                            wire:model.defer="cover_image">
                                        <label for="cover_image" class="form-label mt-2 text-danger">Max size : 1 MB , 500 px * 200 px</label>
                                        @error("cover_image")
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row gy-3 mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <table class="table">
                                                <thead>
                                                    <th>@lang('admin::home.servicer type') </th>
                                                    <th>@lang('admin::home.commission percentage')</th>
                                                    <th>@lang('admin::home.status')</th>

                                                </thead>

                                                <tbody>
                                                    @foreach($package_type_services as $index =>$package_type_service)
                                                    <tr>
                                                        <input wire:model.defer="package_type_services.{{$index}}.type_service_id" class="form-control" type="hidden" />
                                                        <td>
                                                            <input value="{{ \Illuminate\Support\Facades\Lang::has('admin::home.'.$package_type_services[$index]['type_service']['name']) ? __('admin::home.'.$package_type_services[$index]['type_service']['name']) : $package_type_services[$index]['type_service']['name'] }}" class="form-control" disabled />
                                                        </td>

                                                        <td>
                                                            <input wire:model.defer="package_type_services.{{$index}}.commission" disabled class="form-control" />
                                                        <td>
                                                            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                <input id="{{'active'.$index}}"
                                                                    type="checkbox" {{ $package_type_services[$index]['status'] == "active"  ? 'checked' : '' }}>
                                                                <label for="{{'active'.$index}}" class="label-success"
                                                                    wire:click="updateStatusRow('{{ $index }}')" wire:ignore.self></label>
                                                            </div>
                                                        </td>


                                                    </tr>

                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-3 mt-3">
                                    @error('longitude')
                                    <div class="alert alert-danger" role="alert">
                                        {{ __('marketplace::messages.MAP_MESSAGE') }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="row gy-3 mt-3">
                                    <div class="col-xl-12">
                                        <label for="branch-address" class="form-label text-black">@lang('marketplace::home.Branch Address') <span class="text-danger">*</span></label>
                                        <input type="text" id="branch-address" class="form-control"
                                            wire:model.defer="address"
                                            placeholder="">
                                        @error('address')<span class="d-inline-block text-danger">{{$message}}</span>@enderror
                                    </div>
                                </div>
                                <div class="row gy-3 mt-3">
                                    <div class="col-xl-12" wire:ignore>
                                        <small class="d-block mb-1 text-muted">@lang('marketplace::home.map_hint')</small>
                                        <input type="text" id="pac-input-create"
                                            class="form-control"
                                            placeholder="@lang('marketplace::home.search_for_location')">
                                        <div id="map" style="height:300px;" class="my-3 "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger"
                data-bs-dismiss="modal" wire:loading.attr="disabled">@lang('marketplace::home.Close')
            </button>
            <button type="submit" class="btn btn-success">
                <span class="indicator-label" wire:loading.class="d-none">
                    @lang('marketplace::home.Save')
                </span>
                <span class="d-none" wire:loading.class.remove="d-none">
                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>

        </div>
        </form>
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

    $(document).on('change', '.selectCountry', function() {
        $('.selectCity').empty();
        $('.selectRegion').empty();
        window.livewire.emit('updateCountry', $(this).val());
    });
    $(document).on('change', '.selectRegion', function() {
        $('.selectCity').empty();
        window.livewire.emit('updateRegion', $(this).val());
    });


    // $(document).on('change', '.selectCity', function () {
    //     $('.selectDistrict').empty();
    //     window.livewire.emit('updateCity', $(this).val());
    // });

    $(document).on('change', '.selectDistrict', function() {
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

    $(document).on('click', '#createBranch [data-bs-dismiss="modal"]', function() {
        if ($(this).is(':disabled')) {
            return false;
        }
    });

    var createBranchModal = document.getElementById('createBranch');
    if (createBranchModal) {
        createBranchModal.addEventListener('hide.bs.modal', function(event) {
            var closeBtn = createBranchModal.querySelector('.btn-close');
            var footerClose = createBranchModal.querySelector('.modal-footer [data-bs-dismiss="modal"]');
            if ((closeBtn && closeBtn.disabled) || (footerClose && footerClose.disabled)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }
</script>
<script type="text/javascript">
    $("#pac-input-create").focusin(function() {
        $(this).val('');
    })

    function initAutocomplete() {
        if (window.__createBranchMapDone) {
            return;
        }
        window.__createBranchMapDone = true;
        var mapEl = document.getElementById('map');
        if (!mapEl) {
            return;
        }
        map = new google.maps.Map(mapEl, {
            center: {
                lat: 24.774265,
                lng: 46.738586
            },
            zoom: 13,
            mapTypeId: 'roadmap',
            scrollwheel: true,
        });
        // move pin and current location
        infoWindow = new google.maps.InfoWindow;
        geocoder = new google.maps.Geocoder();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(pos);
                addFallbackMarker();
                if (typeof window.__branchMarker !== 'undefined') {
                    window.__branchMarker.setPosition(new google.maps.LatLng(pos));
                    window.__branchMarker.setTitle('موقعك الحالي');
                    google.maps.event.trigger(window.__branchMarker, 'click');
                }
            }, function() {
                addFallbackMarker();
            });
        } else {
            // Browser doesn't support Geolocation
            addFallbackMarker();
        }
        var geocoder = new google.maps.Geocoder();
        console.log(geocoder);

        google.maps.event.addListener(map, 'click', function(event) {
            SelectedLatLng = event.latLng;
            geocoder.geocode({
                'latLng': event.latLng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        deleteMarkers();
                        addMarkerRunTime(event.latLng);
                        SelectedLocation = results[0].formatted_address;
                        // console.log(results[0].formatted_address);
                        splitLatLng(String(event.latLng));
                        $("#branch-address").val(results[0].formatted_address);
                        @this.set('address', results[0].formatted_address);
                    }
                }
            });
        });

        function geocodeLatLng(geocoder, map, infowindow, markerCurrent) {
            var latlng = {
                lat: markerCurrent.position.lat(),
                lng: markerCurrent.position.lng()
            };
            /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
            $('#latitude').val(markerCurrent.position.lat());
            $('#longitude').val(markerCurrent.position.lng());
            window.livewire.emit('set:latitude-longitude', markerCurrent.position.lat(), markerCurrent.position.lng());
            geocoder.geocode({
                'location': latlng
            }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        map.setZoom(8);
                        var marker = new google.maps.Marker({
                            position: latlng,
                            map: map
                        });
                        markers.push(marker);
                        infowindow.setContent(results[0].formatted_address);
                        SelectedLocation = results[0].formatted_address;
                        $("#pac-input-create").val(results[0].formatted_address);
                        @this.set('address', results[0].formatted_address);
                        infowindow.open(map, marker);
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
            SelectedLatLng = (markerCurrent.position.lat(), markerCurrent.position.lng());
        }

        function addMarkerRunTime(location) {
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });
            markers.push(marker);
            marker.addListener('click', function() {
                geocodeLatLng(geocoder, map, infoWindow, marker);
            });
            marker.addListener('dragend', function(event) {
                var latlng = event.latLng;
                splitLatLng(String(latlng));
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK && results[0]) {
                        SelectedLocation = results[0].formatted_address;
                        $("#branch-address").val(results[0].formatted_address);
                        @this.set('address', results[0].formatted_address);
                    }
                });
            });
        }

        function addFallbackMarker() {
            if (markers.length > 0 && typeof window.__branchMarker !== 'undefined') {
                return;
            }
            var centerMarker = new google.maps.Marker({
                position: map.getCenter(),
                map: map,
                title: 'حدد موقع الفرع',
                draggable: true
            });
            window.__branchMarker = centerMarker;
            markers.push(centerMarker);
            centerMarker.addListener('click', function() {
                geocodeLatLng(geocoder, map, infoWindow, centerMarker);
            });
            centerMarker.addListener('dragend', function(event) {
                var latlng = event.latLng;
                splitLatLng(String(latlng));
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK && results[0]) {
                        SelectedLocation = results[0].formatted_address;
                        $("#branch-address").val(results[0].formatted_address);
                        @this.set('address', results[0].formatted_address);
                    }
                });
            });
        }

        $('#createBranch').on('shown.bs.modal', function() {
            var tries = 0;
            (function showMapReady() {
                if (typeof map !== 'undefined' && map) {
                    google.maps.event.trigger(map, 'resize');
                    window.setTimeout(function() {
                        map.setCenter(map.getCenter());
                        addFallbackMarker();
                    }, 100);
                    return;
                }
                if (tries++ < 20) {
                    window.setTimeout(showMapReady, 250);
                }
            })();
        });

        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function deleteMarkers() {
            clearMarkers();
            markers = [];
            window.__branchMarker = undefined;
        }

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input-create');
        $("#pac-input-create").attr("placeholder", "{{ __('marketplace::home.search_for_location') }}");
        var searchBox = new google.maps.places.SearchBox(input);



        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });
        markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(100, 100),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };
                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));
                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                window.livewire.emit('set:latitude-longitude', place.geometry.location.lat(), place.geometry.location.lng())
            });
            map.fitBounds(bounds);
        });
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }

    function splitLatLng(latLng) {
        var newString = latLng.substring(0, latLng.length - 1);
        var newString2 = newString.substring(1);
        var trainindIdArray = newString2.split(',');
        var lat = trainindIdArray[0];
        var Lng = trainindIdArray[1];
        $("#latitude").val(lat);
        $("#longitude").val(Lng);

        window.livewire.emit('set:latitude-longitude', lat, Lng)
    }
</script>

<!-- <script
        src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&callback=initAutocomplete&libraries=places&v=weekly"
        defer
    ></script> -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&callback=initAutocomplete&libraries=places&v=weekly"
    defer></script>

@endpush