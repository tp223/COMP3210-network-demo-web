// Get the setup key
var setupKey = document.getElementById('setup-key').dataset.setupKey;

// Last update span
var lastUpdateSpan = document.getElementById('last-updated');

// Repeatedly send a request to the API to check if the beacon is connected using /dashboard/beacons/add/{setup_key}/connection endpoint
function checkConnection() {
    fetch(`/dashboard/beacons/add/${setupKey}/connection`)
        .then(response => response.json())
        .then(data => {
            // Update the last updated span
            lastUpdateSpan.innerHTML = new Date().toLocaleTimeString();
            if (data.status == "not-connected") {
                // If the beacon is not connected, check again in 3 seconds
                setTimeout(checkConnection, 3000);
            } else if (data.status == "configured") {
                // If the beacon is already configured, redirect to the dashboard
                window.location.href = `/dashboard`;
            } else if (data.status == "setup-pending") {
                // If setup is pending, refresh the page
                window.location.reload();
            } else {
                // If the beacon is not connected, check again in 3 seconds
                setTimeout(checkConnection, 3000);
            }
        });
}

// Start checking the connection
checkConnection();