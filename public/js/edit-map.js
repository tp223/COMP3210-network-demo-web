var map = L.map('edit-map', {
    crs: L.CRS.Simple
});

var bounds = [[0, 0], [1000, 1000]];

// Get the map id from the data attribute
var mapId = document.getElementById('edit-map').dataset.map;

// Get the map image from /dashboard/maps/{id}/base-image.png
var imageUrl = '/dashboard/maps/' + mapId + '/base-image.png';

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
        map.setMinZoom(-1);
    };
});