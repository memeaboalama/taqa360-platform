<div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card custom-card border-box-1">
                <div class="card-header">
                    <div class="card-title">
                        @lang('admin::home.Branch Data')
                    </div>
                </div>
                <div class="card-body">
                    @if($listBranches->isEmpty())
                        <div class="alert alert-warning mb-3" role="alert">
                            @lang('admin::home.No branches available')
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="branch-menu" class="form-label fw-semibold">@lang('admin::home.Branch Select')</label>
                            <select id="branch-menu" class="form-control"
                                    wire:model.defer="branch_id"
                                    wire:change="changeBranch()"
                                    name="branch_id">
                                <option value="">@lang('marketplace::home.Select')</option>
                                @foreach($listBranches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->title }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="branch-city" class="form-label fw-semibold">@lang('marketplace::home.City') <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="city_id" id="branch-city">
                                <option value="">@lang('marketplace::home.Select')</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->title }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="package_id" class="form-label fw-semibold">@lang('marketplace::home.Select Package')</label>
                            <select class="form-control"
                                    wire:model="package_id"
                                    id="package_id">
                                <option value="">@lang('marketplace::home.Select')</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->title."    (".$package->commission.")" }}</option>
                                @endforeach
                            </select>
                            @error('package_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>@lang('admin::home.servicer type')</th>
                                <th>@lang('admin::home.commission percentage')</th>
                                <th>@lang('admin::home.discount percentage')</th>
                                <th class="text-center">@lang('admin::home.status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($package_type_services as $index => $package_type_service)
                                @php($serviceName = $package_type_service['type_service']['name'] ?? '')
                                <tr>
                                    <td>
                                        <input wire:model.defer="package_type_services.{{ $index }}.type_service_id" type="hidden" />
                                        <input value="{{ $serviceName ? __('admin::home.'.$serviceName) : '-' }}" class="form-control" disabled />
                                    </td>
                                    <td>
                                        <input wire:model.defer="package_type_services.{{ $index }}.commission" disabled class="form-control text-center" />
                                    </td>
                                    <td>
                                        <input wire:model.defer="package_type_services.{{ $index }}.discount_rate" class="form-control text-center" />
                                    </td>
                                    <td>
                                        <div class="custom-toggle-switch d-flex align-items-center justify-content-center">
                                            <input id="active{{ $index }}" type="checkbox" {{ ($package_type_service['status'] ?? '') == 'active' ? 'checked' : '' }} />
                                            <label for="active{{ $index }}" class="label-success mb-0" wire:click="updateStatusRow('{{ $index }}')"></label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        @lang('admin::home.No services available')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button wire:click="updateBranchData" class="btn btn-success" @disabled($listBranches->isEmpty())>
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('marketplace::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card custom-card border-box-1 overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        @lang('admin::home.Branch Map')
                    </div>
                </div>
                <div class="card-body p-0" wire:ignore>
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    @push('js')
    <script>
        $(document).on('change', '.selectCountryBrn', function () {
            $('.selectCityBrn').empty();
            window.livewire.emit('updateCountryBrn', $(this).val());
        });


        $(document).on('change', '.selectCityBrn', function () {
            $('.selectDistrictBrn').empty();
            window.livewire.emit('updateCity', $(this).val());
        });

        $(document).on('change', '.selectDistrictBrn', function () {
            window.livewire.emit('updateDistrictBrn', $(this).val());
        });


        Livewire.on('renderCities', cities => {
            let element = $('.selectCityBrn');
            element.append(`<option value="">select</option>`);
            for (let i = 0; i < cities.length; i++) {
                element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
            }
             Livewire.emit('renderSelect');
        });

        Livewire.on('renderDistricts', districts => {
            console.log(districts);

            for (let i = 0; i < districts.length; i++) {
                $('.selectDistrictBrn').append(`<option value="${districts[i].id}">${districts[i]?.title?.en}</option>`);
            }
            Livewire.emit('renderSelect');
        });
    </script>
    <script type="text/javascript">

        $("#pac-input-create").focusin(function () {
            $(this).val('');
        })

        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 24.774265, lng: 46.738586},
                zoom: 13,
                mapTypeId: 'roadmap',
                scrollwheel: true,
            });
            // move pin and current location
            infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(pos),
                        map: map,
                        title: 'ظ…ظˆظ‚ط¹ظƒ ط§ظ„ط­ط§ظ„ظٹ',
                        draggable: true
                    });
                    markers.push(marker);
                    marker.addListener('click', function () {
                        geocodeLatLng(geocoder, map, infoWindow, marker);
                    });
                    // to get current position address on load
                    google.maps.event.trigger(marker, 'click');
                }, function () {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            var geocoder = new google.maps.Geocoder();
            console.log(geocoder);

            google.maps.event.addListener(map, 'click', function (event) {
                SelectedLatLng = event.latLng;
                geocoder.geocode({
                    'latLng': event.latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            deleteMarkers();
                            addMarkerRunTime(event.latLng);
                            SelectedLocation = results[0].formatted_address;
                            // console.log(results[0].formatted_address);
                            splitLatLng(String(event.latLng));
                            $("#pac-input-create").val(results[0].formatted_address);
                        @this.set('address', results[0].formatted_address)
                            ;
                        }
                    }
                });
            });

            function geocodeLatLng(geocoder, map, infowindow, markerCurrent) {
                var latlng = {lat: markerCurrent.position.lat(), lng: markerCurrent.position.lng()};
                /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
                $('#latitude').val(markerCurrent.position.lat());
                $('#longitude').val(markerCurrent.position.lng());
                geocoder.geocode({'location': latlng}, function (results, status) {
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
                        @this.set('address', results[0].formatted_address)
                            ;
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
                    map: map
                });
                markers.push(marker);
            }

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
            }

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input-create');
            $("#pac-input-create").val("ط£ط¨ط­ط« ظ‡ظ†ط§ ");
            var searchBox = new google.maps.places.SearchBox(input);



            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });
            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
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



        <script type="text/javascript">
            let map, activeInfoWindow, markers = [];

            /* ----------------------------- Initialize Map ----------------------------- */
            function initMap() {
                if (map) {
                    google.maps.event.trigger(map, "resize");
                    return;
                }
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: 24.774265,
                        lng: 46.738586,
                    },
                    zoom: 8,
                    scrollwheel: true,
                });

                map.addListener("click", function(event) {
                    mapClicked(event);
                });

                initMarkers();
            }
            /* --------------------------- Initialize Markers --------------------------- */
            function initMarkers() {
                const result = <?php echo json_encode($branches); ?>;
                var initialMarkers = Object.keys(result).map((key) => [result[key]]);

                console.log(initialMarkers, "initialMarkers");

                for (let index = 0; index < initialMarkers.length; index++) {
                    console.log(initialMarkers.length, "length");
                    const markerData = initialMarkers[index][0];
                    const icon = {
                        url: "{{ asset('images/map/restaurant.png') }}",
                        scaledSize: new google.maps.Size(50, 50),
                        origin: new google.maps.Point(0,0),
                        anchor: new google.maps.Point(0, 0)
                    };
                    console.log(icon, "icccc");
                    const marker = new google.maps.Marker({
                        position: markerData.position,
                        draggable: markerData.draggable,
                        title: "Branch Details",
                        icon: icon,
                        map
                    });
                    markers.push(marker);

                    const infowindow = new google.maps.InfoWindow({
                        content: `<b>Name : <a href="<?php echo url('/'); ?>/admin/merchants/${markerData.merchant_id}" style="color: #0a53be">${markerData.name}</a></b><br><b>Restaurant Name : <a href="<?php echo url('/'); ?>/admin/merchants/${markerData.merchant_id}" style="color: #0a53be">${markerData.restaurant_name}</a></b><br><b>${markerData.position.lat}, ${markerData.position.lng}</b>`,
                    });
                    marker.addListener("click", (event) => {
                        if(activeInfoWindow) {
                            activeInfoWindow.close();
                        }
                        infowindow.open({
                            anchor: marker,
                            shouldFocus: false,
                            map
                        });
                        activeInfoWindow = infowindow;
                        markerClicked(marker, index);
                    });

                    marker.addListener("dragend", (event) => {
                        markerDragEnd(event, index);
                    });
                }
            }

            /* Initialize map when Livewire has loaded */
            document.addEventListener('livewire:load', function () {
                initMap();
            });

            /* ------------------------- Handle Map Click Event ------------------------- */
            function mapClicked(event) {
                console.log(map);
                console.log(event.latLng.lat(), event.latLng.lng());
            }

            /* ------------------------ Handle Marker Click Event ----------------------- */
            function markerClicked(marker, index) {
                console.log(map);
                console.log(marker.position.lat());
                console.log(marker.position.lng());
            }

            /* ----------------------- Handle Marker DragEnd Event ---------------------- */
            function markerDragEnd(event, index) {
                console.log(map);
                console.log(event.latLng.lat());
                console.log(event.latLng.lng());
            }

            /* Resize map when the branches tab becomes visible */
            document.addEventListener('shown.bs.tab', function (event) {
                if (event.target && event.target.id === 'branches-tab' && map) {
                    google.maps.event.trigger(map, "resize");
                }
            });
        </script>
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&callback=initMap"></script> -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&callback=initMap"></script>
    @endpush

