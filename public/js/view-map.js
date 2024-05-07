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
        // Determine zoom level to show the whole map
        map.setZoom(map.getBoundsZoom(bounds, false));
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

function isWebBluetoothEnabled() {
    if (navigator.bluetooth) {
        return true;
    } else {
        console.error('Web Bluetooth API is not available/enabled. Please enable it in chrome://flags.');
        // Get the bootstrap error modal
        var errorModal = new bootstrap.Modal(document.getElementById('btSetupModal'));
        // Show the error modal
        errorModal.show();
        return false;
    }
}

function logToBrowser(message) {
    var log = document.getElementById('logs');
    log.textContent += message + '<br>';
}

async function startBluetoothScanner() {
    if (!isWebBluetoothEnabled()) {
        return;
    }

    // Bluetooth Options
    let options = {
        acceptAllAdvertisements: true,
    }
    const scan = await navigator.bluetooth.requestLEScan(options);
    logToBrowser('Scan started with:');
    logToBrowser(' acceptAllAdvertisements: ' + scan.acceptAllAdvertisements);
    navigator.bluetooth.addEventListener('advertisementreceived', event => {
        logToBrowser('Advertisement received.');
        logToBrowser('  Device Name: ' + event.device.name);
        logToBrowser('  Device ID: ' + event.device.id);
        logToBrowser('  RSSI: ' + event.rssi);
        logToBrowser('  TX Power: ' + event.txPower);
        logToBrowser('  UUIDs: ' + event.uuids);
    });
}