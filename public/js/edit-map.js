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

var beacons = {};

// Get markers from /dashboard/maps/{id}/markers
fetch('/dashboard/maps/' + mapId + '/markers').then(function (response) {
    return response.json();
}).then(function (data) {
    console.log(data);
    data.forEach(function (marker) {
        if (marker.status == "enabled") {
            console.log("Plotting beacon " + marker.beacon_id + " on map");
            // Create a draggable marker at the beacon's location
            var markerElement = L.marker([marker.latitude, marker.longitude], { draggable: true, autoPan: true }).addTo(map).bindPopup(marker.name);
            // Add a drag event listener to the marker
            markerElement.on('dragend', function (event) {
                var markerElement = event.target;
                var position = markerElement.getLatLng();
                // Update the marker's position
                updateMarker(marker.beacon_id, position.lat, position.lng);
            });
            beacons[marker.beacon_id] = {
                beacon: marker,
                marker: markerElement,
                markerPlotted: true
            };
            // Update the button text
            document.querySelector('.plot-beacon-on-map-btn[data-beacon="' + marker.beacon_id + '"]').innerHTML = "Show on map";
            // Enable the reset button
            document.querySelector('.reset-beacon-on-map-btn[data-beacon="' + marker.beacon_id + '"]').disabled = false;
        } else {
            beacons[marker.beacon_id] = {
                beacon: marker,
                markerPlotted: false
            };
        }
    });
});

// Function to add a marker to the map
function addMarker(beaconId) {
    // Get the beacon from beacons
    var beacon = beacons[beaconId];

    // Check if the beacon exists
    if (!beacon) {
        alert('Beacon not found');
        return;
    }

    if (beacon.markerPlotted) {
        // Pan to the marker
        map.panTo(beacon.marker.getLatLng());
        return;
    }
    // Create a draggable marker at the map's center
    var marker = L.marker(map.getCenter(), { draggable: true, autoPan: true }).addTo(map).bindPopup(beacon.beacon.name);
    // Add a drag event listener to the marker
    marker.on('dragend', function (event) {
        var marker = event.target;
        var position = marker.getLatLng();
        // Update the marker's position
        updateMarker(beaconId, position.lat, position.lng);
    });
    // Set the beacon marker to the new marker
    beacons[beaconId].marker = marker;
    beacons[beaconId].markerPlotted = true;
    // Update the button text
    document.querySelector('.plot-beacon-on-map-btn[data-beacon="' + beaconId + '"]').innerHTML = "Show on map";
    // Enable the reset button
    document.querySelector('.reset-beacon-on-map-btn[data-beacon="' + beaconId + '"]').disabled = false;
    // Send the new marker to the server
    markerAdded(beaconId, map.getCenter().lat, map.getCenter().lng);
}

// Send an updated marker location to the server
function updateMarker(beaconId, latitude, longitude) {
    fetch('/dashboard/maps/' + mapId + '/markers/' + beaconId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude
        })
    }).then(function (response) {
        if (!response.ok) {
            alert('Failed to update marker');
        }
    });
}

// Notify API when a marker is added
function markerAdded(beaconId, latitude, longitude) {
    fetch('/dashboard/maps/' + mapId + '/markers/' + beaconId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude
        })
    }).then(function (response) {
        if (!response.ok) {
            alert('Failed to add marker');
        }
    });
}

// Get all plot on map buttons and bind the click event
var plotButtons = document.querySelectorAll('.plot-beacon-on-map-btn');

plotButtons.forEach(function (button) {
    button.addEventListener('click', function () {
        var beaconId = button.dataset.beacon;
        console.log("Plotting beacon " + beaconId + " on map");
        addMarker(beaconId);
    });
});

// Function to move marker to the center of the map
function moveMarker(beaconId) {
    // Get the beacon from beacons
    var beacon = beacons[beaconId];

    // Check if the beacon exists
    if (!beacon) {
        alert('Beacon not found');
        return;
    }

    if (!beacon.markerPlotted) {
        alert('Beacon not plotted on map');
        return;
    }

    // Set the marker's position to the map's center
    beacon.marker.setLatLng(map.getCenter());

    // Pan to the marker
    map.panTo(beacon.marker.getLatLng());

    // Send the new marker location to the server
    updateMarker(beaconId, map.getCenter().lat, map.getCenter().lng);
}

// Get all reset buttons and bind the click event
var resetButtons = document.querySelectorAll('.reset-beacon-on-map-btn');

resetButtons.forEach(function (button) {
    button.addEventListener('click', function () {
        var beaconId = button.dataset.beacon;
        console.log("Resetting beacon " + beaconId + " on map");
        moveMarker(beaconId);
    });
});