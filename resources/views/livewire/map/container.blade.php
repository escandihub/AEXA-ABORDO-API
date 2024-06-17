<div x-data="{ lataa:2 }">
    <div style="width: 600px; height: 400px; position: relative; outline-style: none;" id="map" map></div>
</div>

@assets
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

@endassets

@script
<script>
    let map = L.map('map').setView([$wire.lat, $wire.log], 15);

    // let marker = L.marker([ {{ $lat }}, {{ $log }} ]).addTo(map);
    let marker = L.marker([ $wire.lat, $wire.log]).addTo(map);
    

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);


let circle = L.circle([$wire.lat, $wire.log], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: $wire._radio
}).addTo(map);

document.addEventListener('livewire:init', () => {
        // Runs after Livewire is loaded but before it's initialized
        // on the page...

        console.log(window.Livewire);
    })
</script>
@endscript