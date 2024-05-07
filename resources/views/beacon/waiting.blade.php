<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <h1>Add Beacon</h1>
            <span id="setup-key" data-setup-key="{{ $setupKey }}"></span>
            <p>
                <b>Beacon Information</b><br>
                The beacon is not connected to the network. Please wait...<br>
                You will be redirected to the beacon setup page once the beacon is connected.<br><br>
                Last updated: <span id="last-updated"></span>
            </p>
            <script src="{{ asset('js/beacon-connection-test.js') }}"></script>
        <x-footer/>
    </body>
</html>
