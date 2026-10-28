<div wire:ignore>
    <div class="box-body" >
        <div id="map" style="height: 600px;"></div>
    </div>

    @push('js')
        <script type="text/javascript">
            let map, activeInfoWindow, markers = [];

            /* ----------------------------- Initialize Map ----------------------------- */
            function initMap() {
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
        </script>
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&callback=initMap"></script> -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&callback=initMap"></script>
    @endpush
</div>


