<div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card custom-card border-box-1">
                <div class="card-header">
                    <div class="card-title">
                        @lang('admin::home.Branch Data')
                    </div>
                </div>
                <div class="card-body p-4">
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
                        <div class="p-3 text-muted small">@lang('admin::home.Set branch location on map')</div>
                        <div id="map" style="height: 600px; width: 100%;"></div>

                </div>
            </div>
        </div>
    </div>
</div>
    @push('js')
    @include('shared.maps')
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
                let map, activeInfoWindow;
                let branchMarkers = new Map();
                let selectedBranchId = @json($branch_id);

                function initMap() {
                    if (!window.TaqaMaps || !document.getElementById("map")) {
                        return;
                    }
                    if (map) {
                        TaqaMaps.event.trigger(map, "resize");
                        return;
                    }

                    map = new TaqaMaps.Map(document.getElementById("map"), {
                        center: { lat: 24.774265, lng: 46.738586 },
                        zoom: 8,
                        scrollwheel: true,
                    });
                    map.addListener("click", mapClicked);
                    initMarkers();
                }

                function branchMarkerIcon() {
                    return {
                        url: "{{ asset('images/map/restaurant.png') }}",
                        scaledSize: new TaqaMaps.Size(50, 50),
                        origin: new TaqaMaps.Point(0, 0),
                        anchor: new TaqaMaps.Point(25, 25)
                    };
                }

                function initMarkers() {
                    const initialMarkers = @json(array_values($branches));
                    for (const markerData of initialMarkers) {
                        const branchId = String(markerData.id);
                        const marker = new TaqaMaps.Marker({
                            position: markerData.position,
                            draggable: branchId === String(selectedBranchId),
                            title: "Branch Details",
                            icon: branchMarkerIcon(),
                            map
                        });
                        branchMarkers.set(branchId, marker);

                        const infowindow = new TaqaMaps.InfoWindow({
                            content: `<b>Name : <a href="<?php echo url('/'); ?>/admin/merchants/${markerData.merchant_id}" style="color: #0a53be">${markerData.name}</a></b><br><b>Restaurant Name : <a href="<?php echo url('/'); ?>/admin/merchants/${markerData.merchant_id}" style="color: #0a53be">${markerData.restaurant_name}</a></b><br><b>${markerData.position.lat}, ${markerData.position.lng}</b>`,
                        });
                        marker.addListener("click", () => {
                            if (activeInfoWindow) {
                                activeInfoWindow.close();
                            }
                            infowindow.open({
                                anchor: marker,
                                shouldFocus: false,
                                map
                            });
                            activeInfoWindow = infowindow;
                        });
                        marker.addListener("dragend", event => {
                            if (branchId === String(selectedBranchId)) {
                                saveBranchLocation(event.latLng);
                            }
                        });
                    }
                }

                function mapClicked(event) {
                    if (!selectedBranchId) {
                        return;
                    }

                    const branchId = String(selectedBranchId);
                    let marker = branchMarkers.get(branchId);
                    if (!marker) {
                        marker = new TaqaMaps.Marker({
                            position: event.latLng,
                            draggable: true,
                            title: "Branch Details",
                            icon: branchMarkerIcon(),
                            map
                        });
                        branchMarkers.set(branchId, marker);
                    } else {
                        marker.setPosition(event.latLng);
                        marker.setDraggable(true);
                    }

                    saveBranchLocation(event.latLng);
                }

                function saveBranchLocation(position) {
                    window.livewire.emit('setBranchLocation', position.lat(), position.lng());
                }

                function selectBranchOnMap(branchId) {
                    selectedBranchId = String(branchId || '');
                    branchMarkers.forEach((marker, id) => {
                        marker.setDraggable(id === selectedBranchId);
                    });

                    const marker = branchMarkers.get(selectedBranchId);
                    if (marker && map) {
                        map.panTo(marker.getPosition());
                    }
                }

                document.addEventListener('livewire:load', initMap);
                document.addEventListener('shown.bs.tab', function (event) {
                    if (event.target && event.target.id === 'branches-tab' && map) {
                        TaqaMaps.event.trigger(map, "resize");
                    }
                });
                document.addEventListener('change', function (event) {
                    if (event.target && event.target.id === 'branch-menu') {
                        selectBranchOnMap(event.target.value);
                    }
                });
            </script>

    <script>setTimeout(function () { initMap(); }, 0);</script>
@endpush

