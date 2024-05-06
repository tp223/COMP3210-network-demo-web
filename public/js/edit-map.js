var map = L.map('edit-map', {
    crs: L.CRS.Simple
});

var bounds = [[0, 0], [1000, 1000]];

// Get the map id from the data attribute
var mapId = document.getElementById('edit-map').dataset.map;

// Get the map image from /dashboard/maps/{id}/base-image.png
var imageUrl = '/dashboard/maps/' + mapId + '/base-image.png';

var image = L.imageOverlay(imageUrl, bounds).addTo(map);

map.fitBounds(bounds);