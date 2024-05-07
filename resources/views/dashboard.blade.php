<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
    </head>
    <body class="antialiased">
        <x-navbar/>
        <div class="container">
            <h1>Welcome back {{ Auth::user()->name }}!</h1>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <h3>Your Maps</h3>
            <p>Maps are used to display your location. You can create, edit, or delete maps here.</p>
            <a href="{{ route('map.create') }}">Add Map</a>
            <div class="table-responsive small">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maps as $map)
                            <tr onclick="window.location='{{ route('map.edit', ['map' => $map->id]) }}'" role="button">
                                <td>{{ $map->id }}</td>
                                <td>{{ $map->name }}</td>
                            </tr>
                        @endforeach
                        @if(count($maps) == 0)
                            <tr>
                                <td colspan="2">No maps found.</td>
                            </tr>
                        @endif
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
                            <th scope="col">BLE Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beacons as $beacon)
                            <tr onclick="window.location='{{ route('beacon.edit', ['beacon_id' => $beacon->id]) }}'" role="button">
                                <td>{{ $beacon->id }}</td>
                                <td>{{ $beacon->name }}</td>
                                <td>
                                    @if($beacon->map)
                                        {{ $beacon->map->name }}
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>{{ $beacon->status }}</td>
                                <td>
                                    @if($beacon->bt_address)
                                        {{ $beacon->bt_address }}
                                    @else
                                        None
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if(count($beacons) == 0)
                            <tr>
                                <td colspan="4">No beacons found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        <x-footer/>
    </body>
</html>
