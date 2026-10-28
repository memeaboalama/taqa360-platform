<div class="card custom-card">
    <div class="card-header">
        <div class="card-title">New Campaign</div>
    </div>
    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row gy-3">
                @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div class="col-xl-6">
                        <label for="category-name" class="form-label text-black">
                            Name {{$properties['native']}}</label>
                        <input type="text" class="form-control"
                               id="category-name"
                               placeholder=" Name" wire:model.defer="name.{{$localeCode}}">
                        @error("name." . $localeCode) <span
                            class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                    </div>

                @endforeach

                @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div class="col-xl-6">
                        <label for="category-name" class="form-label text-black">
                            Description {{$properties['native']}}</label>

                        <textarea class="form-control" wire:model.defer="description.{{$localeCode}}"></textarea>

                        @error("description." . $localeCode) <span
                            class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                    </div>

                @endforeach

                <div class="col-xl-6">
                    <label for="employee-email"
                           class="form-label text-black">User Count</label>
                    <input type="number" step="1" min="0" class="form-control" id="employee-email"
                           wire:model.defer="user_count"
                           placeholder="user count">
                    @error('user_count') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-6">
                    <label for="city" class="form-label text-black">Merchant</label>
                    <select class="form-control" wire:model.defer="merchant_id">
                        <option selected value="">--select merchant--</option>
                        @foreach($merchants as $city)
                            <option selected
                                    value="{{ $city->id }}">{{ $city->commercial_name }}</option>
                        @endforeach
                    </select>
                    @error("merchant_id") <span
                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-6">
                    <label for="employee-email"
                           class="form-label text-black">Start Date</label>
                    <input type="datetime-local" class="form-control" id="employee-email"
                           wire:model.defer="publish_date"
                           placeholder="Commercial No">
                    @error('publish_date') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-6">
                    <label for="employee-email"
                           class="form-label text-black">End Date</label>
                    <input type="datetime-local" class="form-control" id="employee-email"
                           wire:model.defer="end_date"
                           placeholder="Commercial Activity">
                    @error('end_date') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" value="true"
                               wire:model.lazy="no_time"
                               id="flexSwitchCheckChecked" checked="false">
                        <label class="form-check-label" for="flexSwitchCheckChecked">No Time</label>
                    </div>
                </div>


                <div class="col-xl-6">
                    <label for="time"
                           class="form-label text-black">From</label>
                    <input type="time" class="form-control" id="time"
                           wire:model.defer="from"
                    >
                    @error('from') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-6">
                    <label for="ttoime"
                           class="form-label text-black">To</label>
                    <input type="time" class="form-control" id="ttoime"
                           wire:model.defer="to"
                    >
                    @error('to') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>


                <div class="col-xl-12">
                    <label for="employee-email"
                           class="form-label text-black">Address</label>
                    <input type="text" class="form-control" id="employee-email"
                           wire:model.defer="address"
                           placeholder="Address">
                    @error('address') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-12">
                    <label for="city" class="form-label text-black">City</label>
                    <select class="form-control" wire:model.defer="city_id">
                        <option selected value="">--select city--</option>
                        @foreach($cities as $city)
                            <option selected
                                    value="{{ $city->id }}">{{ $city->title }}</option>
                        @endforeach
                    </select>
                    @error("city_id") <span
                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-6">
                    <label for="sort"
                           class="form-label text-black">Sort</label>
                    <input type="number" step="1" min="1" class="form-control" id="sort"
                           wire:model.defer="sort"
                           placeholder="sort">
                    @error('sort') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-6">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Image</label>
                        <input class="form-control" wire:model.defer="image" type="file" id="formFile">
                    </div>
                    @error('image') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-6">
                    <label class="form-label text-black">Conditions :</label>
                    @foreach($conditions as $i=> $condition)
                        <div class="form-check mx-2 my-2">
                            <input class="form-check-input" wire:model="condition" type="checkbox"
                                   value="{{$condition->id}}" id="conditin{{$condition->id}}">
                            <label class="form-check-label" for="conditin{{$condition->id}}">
                                {{ $condition->key}}
                            </label>
                        </div>
                        @if($condition->key==\App\Enum\CampCond::GENDER->value)
                            @foreach(\App\Enum\GenderType::values() as $gender)
                                <div class="form-check">
                                    <input class="form-check-input" wire:model="value_cond.{{$condition->id}}"
                                           value="{{$gender}}" type="radio" name="value_cond.{{$condition->id}}"
                                           id="flexRadioDefault{{$condition->id}}{{$gender}}">
                                    <label class="form-check-label" for="flexRadioDefault{{$condition->id}}{{$gender}}">
                                        {{$gender}}
                                    </label>
                                </div>
                            @endforeach
                            @error("value_cond") <span
                                class="text-danger  d-block">{{ $message }}</span> @enderror
                        @else
                            <span class="text-danger">* Range From - To </span>
                            <input type="text" class="form-control"
                                   placeholder=" Value" wire:model.defer="value_cond.{{$condition->id}}">
                        @endif
                        @error("condition.$i") <span
                            class="text-danger  d-block">{{ $message }}</span> @enderror
                    @endforeach
                    @error("condition") <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-xl-6">
                    <label class="form-label text-black">Levels : </label>
                    @foreach($levels as $k=> $level)
                        <div class="form-check ">

                            <input class="form-check-input" wire:model.defer="level" type="checkbox" checked
                                   value="{{$level->id}}" id="level{{$level->id}}">
                            <label class="form-check-label" for="level{{$level->id}}">
                                {{ $level->key}}
                            </label>

                        </div>
                        @error("level.$k") <span
                            class="text-danger  d-block">{{ $message }}</span> @enderror
                    @endforeach

                    @error('level') <span
                        class="text-danger  d-block">{{ $message }}</span> @enderror

                </div>

                <div class="col-md-12">
                    <label class="form-label text-black">Gifts: </label>
                    @foreach($inputs as $index => $input)
                        <div>
                            <div class="form-group row mb-5">
                                @foreach ($locals as $k => $value)
                                    <div class="col-md-4">
                                        <label class="form-label">Name ( {{$value['native']}} )</label>
                                        <input type="text" wire:model.defer="inputs.{{ $index }}.name.{{$k}}"
                                               class="form-control mb-2 mb-md-0">
                                        @error("inputs.$index.name")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error("inputs.$index.name.$k")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" wire:model="inputs.{{ $index }}.type"
                                               value="money" type="radio" name="inputs.{{ $index }}.type"
                                               id="flexRadioDefaultmoney{{$index}}">
                                        <label class="form-check-label" for="flexRadioDefaultmoney{{$index}}">
                                            Money
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" wire:model="inputs.{{ $index }}.type"
                                               value="coffee" type="radio" name="inputs.{{ $index }}.type"
                                               id="flexRadioDefaultcoffee{{$index}}">
                                        <label class="form-check-label" for="flexRadioDefaultcoffee{{$index}}">
                                            Coffee
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" wire:model="inputs.{{ $index }}.type"
                                               value="food" type="radio" name="inputs.{{ $index }}.type"
                                               id="flexRadioDefaultfood{{$index}}">
                                        <label class="form-check-label" for="flexRadioDefaultfood{{$index}}">
                                            Food
                                        </label>
                                    </div>
                                </div>
                                @if($index > 0)
                                    <div class="col-md-1">
                                        <a href="#" wire:click.prevent="remove({{$index}})"
                                           class="btn btn-sm btn-danger-light  mt-4">
                                            <i class="la la-trash-o fs-3"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <a href="#" wire:click.prevent="add({{$i}})" class="btn btn-primary-light ">
                            <i class="la la-plus"></i>Add</a>
                    </div>


                </div>

                <div class="col-md-12">
                    <label class="form-label text-black">Dates: </label>
                    @foreach($dates as $index => $date)
                        <div>
                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label">Date {{$index+1}}</label>
                                    <input type="date" wire:model.defer="dates.{{ $index }}.from"
                                           class="form-control mb-2 mb-md-0">
                                    @error("dates.$index.from")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($index > 0)
                                    <div class="col-md-3">
                                        <a href="#" wire:click.prevent="remove_date({{$index}})"
                                           class="btn btn-sm btn-danger-light  mt-4">
                                            <i class="la la-trash-o fs-3"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <a href="#" wire:click.prevent="add_date()" class="btn btn-primary-light ">
                            <i class="la la-plus"></i>Add</a>
                    </div>


                </div>


                <div class="w-100">
                    <div wire:ignore>

                        <input type="text" id="pac-input-create"
                               class="form-control"
                               placeholder=""
                        >

                        <div id="map" style="height: 300px;width: 100%;"></div>
                    </div>


                    @error('latitude')<span class="text-danger">{{$message}}</span>@enderror <br>
                    @error('longitude')<span class="text-danger">{{$message}}</span>@enderror <br>
                    @error('address')<span class="text-danger">{{$message}}</span>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="btn-list text-end">
                <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    Save
                                </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                                    Wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

    <script>

        $("#pac-input-create").focusin(function () {
            $(this).val('');
        })

        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 24.740691, lng: 46.6528521},
                zoom: 13,
                mapTypeId: 'roadmap'
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
                        title: 'موقعك الحالي'
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
                            $wire.set('address', results[0].formatted_address)
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
                            $wire.set('address', results[0].formatted_address)
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
            $("#pac-input-create").val("أبحث هنا ");
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

            Livewire.emit('lat_long', {
                'lat': lat,
                'long': Lng
            });
        }

    </script>


    <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script>

@endpush
