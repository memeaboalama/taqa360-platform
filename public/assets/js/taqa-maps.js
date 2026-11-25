/* Shared Leaflet maps. The adapter preserves the existing Livewire screen events. */
(function () {
    'use strict';
    if (window.TaqaMaps || !window.L) return;
    const L = window.L;
    const arabic = document.documentElement.lang.startsWith('ar');
    const maps = new Set();
    function coordinates(value) {
        return L.latLng(typeof value.lat === 'function' ? value.lat() : Number(value.lat),
            typeof value.lng === 'function' ? value.lng() : Number(value.lng));
    }
    class LatLng {
        constructor(lat, lng) {
            const point = typeof lat === 'object' ? coordinates(lat) : L.latLng(Number(lat), Number(lng));
            this.point = point;
        }
        lat() { return this.point.lat; }
        lng() { return this.point.lng; }
        toString() { return `(${this.lat()}, ${this.lng()})`; }
    }
    class LatLngBounds {
        constructor(bounds) { this.layer = bounds || L.latLngBounds([]); }
        extend(point) { this.layer.extend(coordinates(point)); return this; }
        union(bounds) { this.layer.extend(bounds.layer); return this; }
        isEmpty() { return !this.layer.isValid(); }
        getCenter() { return new LatLng(this.layer.getCenter()); }
    }
    // Same street/satellite presentation as alawaly-client, with a fallback layer.
    function addBaseLayers(map) {
        if (map._taqaLayers) return map;
        map._taqaLayers = true;
        const language = arabic ? 'ar' : 'en';
        const street = L.tileLayer(`https://mt{s}.google.com/vt/lyrs=m&hl=${language}&x={x}&y={y}&z={z}`, {
            subdomains: ['0', '1', '2', '3'], maxZoom: 20, attribution: '&copy; Google Maps'
        });
        const satellite = L.tileLayer(`https://mt{s}.google.com/vt/lyrs=y&hl=${language}&x={x}&y={y}&z={z}`, {
            subdomains: ['0', '1', '2', '3'], maxZoom: 20, attribution: '&copy; Google Maps'
        });
        const fallback = L.tileLayer('https://tile.openstreetmap.de/{z}/{x}/{y}.png', {
            maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
        });
        street.addTo(map);
        L.control.layers({ [arabic ? 'الشوارع' : 'Streets']: street,
            [arabic ? 'القمر الصناعي' : 'Satellite']: satellite,
            OpenStreetMap: fallback }).addTo(map);
        [street, satellite].forEach(layer => layer.once('tileerror', () => {
            if (map.hasLayer(layer)) { map.removeLayer(layer); fallback.addTo(map); }
        }));
        maps.add(map);
        if (window.ResizeObserver) {
            const observer = new ResizeObserver(() => {
                if (!map.getContainer().isConnected) { observer.disconnect(); maps.delete(map); return; }
                map.invalidateSize({ pan: false });
            });
            observer.observe(map.getContainer());
        }
        return map;
    }
    class MapAdapter {
        constructor(element, options = {}) {
            if (element._taqaMap) return element._taqaMap;
            element._taqaMap = this;
            if (!element.style.height) element.style.height = '360px';
            this.layer = L.map(element, { scrollWheelZoom: options.scrollwheel !== false });
            this.layer.setView(coordinates(options.center || { lat: 24.7136, lng: 46.6753 }), options.zoom || 8);
            addBaseLayers(this.layer);
            this.controls = { TOP_RIGHT: { push: input => {
                const control = L.control({ position: 'topright' });
                control.onAdd = () => {
                    const wrapper = L.DomUtil.create('div', 'taqa-map-search');
                    wrapper.style.cssText = 'background:white;padding:8px;border-radius:6px;max-width:75vw';
                    input.style.display = 'block';
                    wrapper.appendChild(input);
                    if (input._taqaResults) wrapper.appendChild(input._taqaResults);
                    L.DomEvent.disableClickPropagation(wrapper);
                    L.DomEvent.disableScrollPropagation(wrapper);
                    return wrapper;
                };
                control.addTo(this.layer);
            } } };
        }
        setCenter(point) { this.layer.panTo(coordinates(point)); }
        panTo(point) { this.setCenter(point); }
        getCenter() { return new LatLng(this.layer.getCenter()); }
        setZoom(zoom) { this.layer.setZoom(zoom); }
        getZoom() { return this.layer.getZoom(); }
        getBounds() { return new LatLngBounds(this.layer.getBounds()); }
        fitBounds(bounds) { if (!bounds.isEmpty()) this.layer.fitBounds(bounds.layer, { maxZoom: 16, padding: [30, 30] }); }
        addListener(name, callback) { return listen(this, name, callback); }
    }
    class Marker {
        constructor(options) {
            const settings = { draggable: !!options.draggable, title: options.title || '' };
            const icon = options.icon;
            if (icon && (typeof icon === 'string' || icon.url)) {
                settings.icon = L.icon({ iconUrl: typeof icon === 'string' ? icon : icon.url,
                    iconSize: icon.scaledSize ? [icon.scaledSize.width, icon.scaledSize.height] : [32, 40],
                    iconAnchor: icon.anchor ? [icon.anchor.x, icon.anchor.y] : [16, 40] });
            }
            this.layer = L.marker(coordinates(options.position), settings);
            this.setMap(options.map);
        }
        get position() { return this.getPosition(); }
        getPosition() { return new LatLng(this.layer.getLatLng()); }
        setPosition(point) { this.layer.setLatLng(coordinates(point)); }
        setTitle(title) { this.layer.options.title = title; if (this.layer.getElement()) this.layer.getElement().title = title; }
        setDraggable(value) { if (value) this.layer.dragging.enable(); else this.layer.dragging.disable(); }
        setMap(map) { this.layer.remove(); this.map = map; if (map) this.layer.addTo(map.layer); }
        addListener(name, callback) { return listen(this, name, callback); }
    }
    class InfoWindow {
        constructor(options = {}) { this.layer = L.popup().setContent(options.content || ''); }
        setContent(content) { this.layer.setContent(content); }
        setPosition(point) { this.layer.setLatLng(coordinates(point)); }
        open(options, marker) {
            const map = options.map || options;
            const anchor = options.anchor || marker;
            if (anchor) this.layer.setLatLng(anchor.layer.getLatLng());
            this.layer.openOn(map.layer);
        }
        close() { this.layer.remove(); }
    }
    function listen(target, name, callback, once = false) {
        const eventName = name === 'bounds_changed' ? 'moveend' : name;
        const handler = event => callback({ ...event,
            latLng: event.latlng ? new LatLng(event.latlng) : target instanceof Marker ? target.getPosition() : undefined });
        target.layer[once ? 'once' : 'on'](eventName, handler);
        return { remove: () => target.layer.off(eventName, handler) };
    }
    function address(feature) {
        const p = feature.properties || {};
        return [...new Set([p.name, p.street, p.housenumber, p.district, p.city, p.state, p.country].filter(Boolean))].join(', ');
    }
    async function photon(path, signal) {
        const response = await fetch(`https://photon.komoot.io/${path}`, { signal });
        if (!response.ok) throw new Error('Location search unavailable');
        return (await response.json()).features || [];
    }
    class Geocoder {
        geocode(request, callback) {
            const point = request.location || request.latLng;
            const position = point ? coordinates(point) : null;
            const path = position ? `reverse?lat=${position.lat}&lon=${position.lng}` : `api/?q=${encodeURIComponent(request.address)}&limit=5`;
            const promise = photon(path).then(features => features.map(feature => ({
                formatted_address: address(feature),
                geometry: { location: new LatLng(feature.geometry.coordinates[1], feature.geometry.coordinates[0]) }
            })));
            return promise.then(results => {
                // Reverse lookup must not prevent saving a valid pin if no address is available.
                if (!results.length && position) results = [{ formatted_address: `${position.lat}, ${position.lng}`, geometry: { location: new LatLng(position) } }];
                if (callback) callback(results, results.length ? 'OK' : 'ZERO_RESULTS');
                return { results };
            }).catch(error => {
                if (position) {
                    const results = [{ formatted_address: `${position.lat}, ${position.lng}`, geometry: { location: new LatLng(position) } }];
                    if (callback) callback(results, 'OK');
                    return { results };
                }
                if (callback) { callback([], 'ERROR'); return { results: [] }; }
                throw error;
            });
        }
    }
    class SearchBox {
        constructor(input) {
            this.input = input; this.places = []; this.callbacks = [];
            this.results = document.createElement('div');
            this.results.style.cssText = 'max-height:220px;overflow:auto;max-width:320px';
            this.results.setAttribute('role', 'status');
            input._taqaResults = this.results;
            let timer, controller;
            input.addEventListener('input', () => {
                clearTimeout(timer); if (controller) controller.abort();
                this.results.replaceChildren();
                if (input.value.trim().length < 3) return;
                timer = setTimeout(async () => {
                    controller = new AbortController();
                    try {
                        const center = this.bounds && !this.bounds.isEmpty() ? this.bounds.getCenter() : null;
                        const features = await photon(`api/?q=${encodeURIComponent(input.value.trim())}&limit=6${center ? `&lat=${center.lat()}&lon=${center.lng()}` : ''}`, controller.signal);
                        this.results.replaceChildren();
                        features.forEach(feature => {
                            const button = document.createElement('button');
                            button.type = 'button'; button.textContent = address(feature);
                            button.style.cssText = 'display:block;width:100%;padding:8px;border:0;border-bottom:1px solid #ddd;background:white;text-align:start;color:#222';
                            button.onclick = () => {
                                const name = address(feature);
                                input.value = name;
                                this.places = [{ name, formatted_address: name,
                                    geometry: { location: new LatLng(feature.geometry.coordinates[1], feature.geometry.coordinates[0]) } }];
                                this.results.replaceChildren(); this.callbacks.forEach(callback => callback());
                            };
                            this.results.appendChild(button);
                        });
                        if (!features.length) this.results.textContent = arabic ? 'لا توجد نتائج، حدد الموقع على الخريطة' : 'No results. Select a point on the map.';
                    } catch (error) {
                        if (error.name !== 'AbortError') this.results.textContent = arabic ? 'تعذر البحث، حدد الموقع على الخريطة' : 'Search unavailable. Select a point on the map.';
                    }
                }, 400);
            });
            input.addEventListener('keydown', event => { if (event.key === 'Enter') event.preventDefault(); });
        }
        setBounds(bounds) { this.bounds = bounds; }
        getPlaces() { return this.places; }
        addListener(name, callback) { this.callbacks.push(callback); }
    }
    window.TaqaMaps = {
        Map: MapAdapter, Marker, LatLng, LatLngBounds, InfoWindow, Geocoder,
        Size: class { constructor(width, height) { this.width = width; this.height = height; } },
        Point: class { constructor(x, y) { this.x = x; this.y = y; } },
        places: { SearchBox }, ControlPosition: { TOP_RIGHT: 'TOP_RIGHT' }, GeocoderStatus: { OK: 'OK' },
        addBaseLayers,
        mountPicker(element, options) {
            const initial = options.point || { lat: 24.7136, lng: 46.6753 };
            const map = new MapAdapter(element, { center: initial, zoom: options.point ? 15 : 10 });
            let marker, revision = 0;
            let editable = options.editable !== false;
            const placeMarker = point => {
                if (marker) marker.setPosition(point);
                else {
                    marker = new Marker({ map, position: point, draggable: editable });
                    marker.addListener('dragend', event => select(event.latLng));
                }
            };
            const select = async (point, resolvedAddress) => {
                if (!editable) return;
                const current = ++revision;
                const location = new LatLng(point);
                placeMarker(location);
                // Coordinates can be saved even when address lookup is unavailable.
                options.onChange(location.lat(), location.lng(), resolvedAddress || location.toString());
                if (!resolvedAddress) {
                    const result = await new Geocoder().geocode({ location });
                    if (current === revision && editable && result.results[0]) {
                        options.onChange(location.lat(), location.lng(), result.results[0].formatted_address);
                    }
                }
            };
            const locateControl = L.control({ position: 'topleft' });
            let locateButton;
            locateControl.onAdd = () => {
                const wrapper = L.DomUtil.create('div', 'leaflet-bar');
                locateButton = document.createElement('button');
                locateButton.type = 'button';
                locateButton.textContent = arabic ? 'موقعي الحالي' : 'My location';
                locateButton.disabled = !editable;
                locateButton.style.cssText = 'background:white;color:#222;border:0;padding:8px;cursor:pointer';
                const status = document.createElement('div');
                status.setAttribute('role', 'status');
                status.style.cssText = 'background:white;color:#222;max-width:220px';
                locateButton.onclick = () => {
                    if (!editable) return;
                    if (!navigator.geolocation) {
                        status.textContent = arabic ? 'تعذر تحديد الموقع، انقر على الخريطة' : 'Location unavailable. Click the map.';
                        return;
                    }
                    const current = ++revision;
                    locateButton.disabled = true;
                    navigator.geolocation.getCurrentPosition(position => {
                        locateButton.disabled = !editable;
                        if (current !== revision || !editable) return;
                        const point = { lat: position.coords.latitude, lng: position.coords.longitude };
                        select(point); map.setCenter(point); map.setZoom(16); status.textContent = '';
                    }, () => {
                        locateButton.disabled = !editable;
                        status.textContent = arabic ? 'تعذر تحديد الموقع، انقر على الخريطة' : 'Location unavailable. Click the map.';
                    }, { enableHighAccuracy: true, timeout: 10000 });
                };
                wrapper.append(locateButton, status);
                L.DomEvent.disableClickPropagation(wrapper);
                return wrapper;
            };
            locateControl.addTo(map.layer);
            map.addListener('click', event => select(event.latLng));
            if (options.point) placeMarker(initial);
            if (options.searchInput) {
                const search = new SearchBox(options.searchInput);
                map.controls.TOP_RIGHT.push(options.searchInput);
                search.setBounds(map.getBounds());
                map.addListener('bounds_changed', () => search.setBounds(map.getBounds()));
                search.addListener('places_changed', () => {
                    if (!editable) return;
                    const place = search.getPlaces()[0];
                    if (place) { select(place.geometry.location, place.formatted_address); map.setCenter(place.geometry.location); map.setZoom(16); }
                });
                options.searchInput.disabled = !editable;
            }
            return {
                setEditable(value) {
                    editable = !!value; ++revision;
                    if (marker) marker.setDraggable(editable);
                    if (locateButton) locateButton.disabled = !editable;
                    if (options.searchInput) options.searchInput.disabled = !editable;
                },
                reset(point) {
                    ++revision;
                    if (point) { placeMarker(point); map.setCenter(point); }
                    else if (marker) { marker.setMap(null); marker = null; }
                }
            };
        },
        mountDeliveryMaps() {
            document.querySelectorAll('[data-taqa-delivery-map]').forEach(async element => {
                if (element.dataset.mounted) return;
                element.dataset.mounted = 'true';
                const query = element.dataset.query;
                const match = query.match(/^(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)$/);
                try {
                    const point = match ? new LatLng(match[1], match[2]) : (await new Geocoder().geocode({ address: query })).results[0]?.geometry.location;
                    if (!point) throw new Error('Location unavailable');
                    const map = new MapAdapter(element, { center: point, zoom: 15 });
                    new Marker({ map, position: point });
                } catch (_) {
                    element.textContent = arabic ? 'تعذر عرض الموقع، استخدم رابط فتح الخريطة' : 'Location unavailable. Use the map link.';
                }
            });
        },
        event: {
            addListener: listen,
            addListenerOnce: (target, name, callback) => listen(target, name, callback, true),
            addDomListener: (target, name, callback) => {
                if (target === window && name === 'load' && document.readyState === 'complete') setTimeout(callback, 0);
                else target.addEventListener(name, callback);
            },
            trigger: (target, name) => { if (name === 'resize') target.layer.invalidateSize(); else target.layer.fire(name); }
        }
    };
    document.addEventListener('shown.bs.modal', () => maps.forEach(map => map.invalidateSize()));
    document.addEventListener('shown.bs.tab', () => maps.forEach(map => map.invalidateSize()));
})();
