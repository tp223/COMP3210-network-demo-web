<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-head/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </head>
    <body class="antialiased">
        <!-- Modal showing error when bluetooth fails to start -->
        <div class="modal fade" id="btSetupModal" tabindex="-1" aria-labelledby="btSetupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="btSetupModalLabel">Bluetooth Unavailable</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Bluetooth is required to use this application. Please enable Bluetooth and refresh the page.<br>
                            As this is an experimental feature, you may need to enable the Web Platform flag in Chrome by going to <a href="">chrome://flags/#enable-experimental-web-platform-features</a>.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
            <button class="btn btn-primary mt-5" onClick="startBluetoothScanner();">Start Bluetooth</button>
            <div class="accordion mt-3 mb-3" id="accordionLog">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Logs
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionLog">
                        <div class="accordion-body">
                            <span id="logs"></span>
                        </div>
                    </div>
                </div>
            </div>
        <x-footer/>
        <script src="{{ asset('js/view-map.js') }}"></script>
    </body>
</html>
