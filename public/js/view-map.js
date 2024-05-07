var map = L.map('view-map', {
    crs: L.CRS.Simple
});

var bounds = [[0, 0], [1000, 1000]];

// Get the map id from the data attribute
var mapKey = document.getElementById('view-map').dataset.map;

// Get the map image from /dashboard/maps/{id}/base-image.png
var imageUrl = '/map/' + mapKey + '/base-image.png';

var image;

// Download image, determine size, and set it as the map background
fetch(imageUrl).then(function (response) {
    return response.blob();
}).then(function (blob) {
    var img = document.createElement('img');
    img.src = URL.createObjectURL(blob);
    img.onload = function () {
        // Set the map size to the image size
        bounds = [[0, 0], [img.height, img.width]];
        image = L.imageOverlay(imageUrl, bounds).addTo(map);
        map.setMaxBounds(bounds);
        map.fitBounds(bounds);
        // Allow greater zoom
        map.setMinZoom(-10);
    };
});

var beacons = {};

// Get markers from /maps/{mapKey}/beacons
fetch('/map/' + mapKey + '/beacons').then(function (response) {
    return response.json();
}).then(function (data) {
    console.log(data);
    data.forEach(function (marker) {
        console.log("Plotting beacon " + marker.beacon_id + " on map");
        // Create a draggable marker at the beacon's location
        if (marker.description == null) {
            marker.description = "";
        } else {
            marker.description = "<br>" + marker.description;
        }
        var markerElement = L.marker([marker.latitude, marker.longitude], { draggable: false, autoPan: true }).addTo(map).bindPopup(marker.name + marker.description);
        beacons[marker.beacon_id] = {
            beacon: marker,
            marker: markerElement,
            markerPlotted: true,
            btAddr: marker.bt_address,
            description: marker.description,
        };
    });
});
