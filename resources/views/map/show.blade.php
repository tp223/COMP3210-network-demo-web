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
            <h1>{{ $map->name }}</h1>
            <p>{{ $map->description }}</p>
            <p><small>Created by {{ $map->owner->name }}</small></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="view-map" id="view-map" data-map="{{ $map->public_url }}"></div>
                </div>
            </div>
        <x-footer/>
        <script src="{{ asset('js/view-map.js') }}"></script>
    </body>
</html>
