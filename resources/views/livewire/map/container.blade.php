<div>
    <div>
        <div style="width: 450px; height: 400px; position: relative; outline-style: none;" id="map"></div>
    </div>

    @assets
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    {{-- draw plugin --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.js"></script>

    @endassets

    @script
    <script>
        let map = L.map('map').setView([$wire.lat, $wire.log], 15);

        let marker = L.marker([$wire.lat, $wire.log]).addTo(map);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        let circle = L.circle([$wire.lat, $wire.log], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: $wire._radio
        })//.addTo(map);

        //  circle.editing.enable()
        // FeatureGroup is to store editable layers
        // var drawnItems = new L.FeatureGroup();
        // map.addLayer(drawnItems);
        drawnItems = new L.featureGroup().addTo(map);

        // drawnItems.addLayer(circle);
        map.addLayer(circle)
   

        var drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: false,
                marker: false,
                polyline: false,
                rectangle: false,
                circle: {
                    showArea: true
                }
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        drawControl.setDrawingOptions({
            circle: {
                shapeOptions: {
                    color: '#0000FF'
                }
            }
        });
        map.addControl(drawControl);


        function onMapClick(e) {
            alert("You clicked the map at " + e.latlng);
        }

        function createCircle(lat, log, ra){
            L.circle([lat,log], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: ra
        }).addTo(map);
        }

        map.on('draw:created', function (e) {
            let type = e.layerType
            var layer = e.layer;
            drawnItems.addLayer(layer);
            if(type == 'circle'){
                // createCircle(e.layer._latlng.lat, e.layer._latlng.lng, e.layer._mRadius)
                // Livewire.dispatch('new-circle', {lat: e.layer._latlng.lat, log: e.layer._latlng.lng, radio: e.layer._mRadius })
                $wire.dispatch('new-circle', 
                {lat: e.layer._latlng.lat, log: e.layer._latlng.lng, radio: e.layer._mRadius })
            }
         console.log(e);

     });

     map.on('draw:deletestart', function (event) {
        var layer = event.layer;

        console.log(layer);
        console.log(event);
    });

     map.on('draw:editstop', function (event) {
        var layer = event.layer;

        console.log(layer);
        console.log(event);
    });


    </script>
    @endscript
</div>