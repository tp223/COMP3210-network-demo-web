<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <h1>Welcome back {{ Auth::user()->name }}!</h1>
            <h3>Your Maps</h3>
            <p>Maps are used to display your location. You can create, edit, or delete maps here.</p>
            <a href="#">Create a new map</a>
            <div class="table-responsive small">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <h3>Your Beacons</h3>
            <p>Beacons are used to determine your location on the map. You can add, edit, or delete beacons here. To connect a new beacon to your account, connect your device to the WiFi network <i>Beacon ######</i> and navigate to <a href="http://192.168.4.1">192.168.4.1</a> in your browser.</p>
            <div class="table-responsive small">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Map</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        <x-footer/>
    </body>
</html>
