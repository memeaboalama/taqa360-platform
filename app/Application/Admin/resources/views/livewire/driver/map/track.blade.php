<div wire:poll.15s="refreshDrivers">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <span class="badge bg-primary">
                {{ count($drivers) }} {{ __('admin::home.Drivers with map location') }}
            </span>
            <span class="text-muted">{{ __('admin::home.Driver locations refresh automatically every 15 seconds') }}</span>
            <span class="text-muted">
                {{ __('admin::home.Last data refresh') }}: {{ $lastUpdatedAt }}
            </span>
        </div>
        <button type="button" class="btn btn-primary btn-sm"
                wire:click="refreshDrivers" wire:loading.attr="disabled" wire:target="refreshDrivers">
            <span wire:loading.remove wire:target="refreshDrivers">{{ __('admin::home.Refresh locations') }}</span>
            <span wire:loading wire:target="refreshDrivers">{{ __('admin::home.Wait') }}</span>
        </button>
    </div>

    @if (!count($drivers))
        <div class="alert alert-info mb-3" role="status">
            {{ __('admin::home.No active drivers with a current location') }}
        </div>
    @endif

    @if (config('services.google_maps_tracking.api_key'))
        <div class="rounded overflow-hidden position-relative">
            <div id="driver-map" wire:ignore style="height: min(70vh, 700px); min-height: 420px; width: 100%;"></div>
            <div id="driver-map-error" class="alert alert-danger position-absolute top-0 start-0 end-0 m-3 d-none"
                 role="alert"></div>
        </div>

        @push('js')
            <script>
                (function () {
                    let map;
                    let activeInfoWindow;
                    let driverMapInitialized = false;
                    const markers = new Map();
                    const mapError = @json(__('admin::home.Google Maps could not be loaded. Check the API key, billing, and allowed domains.'));
                    const driverDetailsUrl = @json(url('/admin/drivers/show'));

                    function showMapError(message) {
                        const error = document.getElementById('driver-map-error');
                        if (!error) return;
                        error.textContent = message;
                        error.classList.remove('d-none');
                    }

                    const previousAuthFailure = window.gm_authFailure;
                    window.gm_authFailure = function () {
                        if (typeof previousAuthFailure === 'function') previousAuthFailure();
                        showMapError(mapError);
                    };
                    window.showDriverMapError = function () {
                        showMapError(mapError);
                    };

                    function createInfoWindowContent(driver) {
                        const container = document.createElement('div');
                        const link = document.createElement('a');
                        link.href = `${driverDetailsUrl}/${encodeURIComponent(driver.id)}`;
                        link.textContent = driver.name || '';
                        link.className = 'fw-bold';
                        container.appendChild(link);

                        const phone = document.createElement('div');
                        phone.textContent = driver.phone || '';
                        container.appendChild(phone);

                        const coordinates = document.createElement('div');
                        coordinates.textContent = `${driver.position.lat}, ${driver.position.lng}`;
                        container.appendChild(coordinates);
                        return container;
                    }

                    function updateMarkers(drivers) {
                        if (!map || !window.google || !google.maps) return;

                        const activeIds = new Set();
                        const bounds = new google.maps.LatLngBounds();

                        drivers.forEach(function (driver) {
                            if (!driver || !driver.position || !Number.isFinite(Number(driver.position.lat))
                                || !Number.isFinite(Number(driver.position.lng))) return;

                            const id = String(driver.id);
                            const position = {
                                lat: Number(driver.position.lat),
                                lng: Number(driver.position.lng)
                            };
                            activeIds.add(id);
                            bounds.extend(position);

                            let marker = markers.get(id);
                            if (marker) {
                                marker.setPosition(position);
                                marker.driver = driver;
                                marker.setTitle(driver.name || '');
                                return;
                            }

                            marker = new google.maps.Marker({
                                map: map,
                                position: position,
                                title: driver.name || '',
                                icon: {
                                    url: @json(asset('assets/vendor/images/marker.png')),
                                    scaledSize: new google.maps.Size(44, 44),
                                    anchor: new google.maps.Point(22, 22)
                                }
                            });
                            marker.driver = driver;
                            marker.addListener('click', function () {
                                if (activeInfoWindow) activeInfoWindow.close();
                                activeInfoWindow = new google.maps.InfoWindow({
                                    content: createInfoWindowContent(marker.driver)
                                });
                                activeInfoWindow.open({ anchor: marker, map: map, shouldFocus: false });
                            });
                            markers.set(id, marker);
                        });

                        markers.forEach(function (marker, id) {
                            if (activeIds.has(id)) return;
                            marker.setMap(null);
                            markers.delete(id);
                        });

                        if (!driverMapInitialized && !bounds.isEmpty()) {
                            if (activeIds.size === 1) {
                                map.setCenter(bounds.getCenter());
                                map.setZoom(12);
                            } else {
                                map.fitBounds(bounds);
                                google.maps.event.addListenerOnce(map, 'bounds_changed', function () {
                                    if (map.getZoom() > 14) map.setZoom(14);
                                });
                            }
                        }

                        driverMapInitialized = true;
                    }

                    window.initAdminDriverTrackingMap = function () {
                        const element = document.getElementById('driver-map');
                        if (!element || !window.google || !google.maps) {
                            showMapError(mapError);
                            return;
                        }

                        map = new google.maps.Map(element, {
                            center: { lat: 24.774265, lng: 46.738586 },
                            zoom: 8,
                            mapTypeControl: false,
                            streetViewControl: false,
                            fullscreenControl: true
                        });
                        updateMarkers(@json($drivers));
                    };

                    window.addEventListener('drivers-updated', function (event) {
                        updateMarkers((event.detail && event.detail.drivers) || []);
                    });
                })();
            </script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key={{ urlencode(config('services.google_maps_tracking.api_key')) }}&callback=initAdminDriverTrackingMap&language={{ app()->getLocale() }}"
                    onerror="showDriverMapError()"></script>
        @endpush
    @else
        <div class="alert alert-warning mb-0" role="alert">
            {{ __('admin::home.Google Maps API key is not configured.') }}
        </div>
    @endif
</div>
