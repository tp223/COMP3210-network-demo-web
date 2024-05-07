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
            <h1>Edit Beacon</h1>
            <p>
                <b>Beacon Information</b><br>
                Serial Number: {{ $beacon->serial_number }}<br>
                First Connect: {{ $beacon->created_at }}<br>
                Attached Map: @if($beacon->map) {{ $beacon->map->name }} @else None @endif<br>
                Bluetooth Address: {{ $beacon->bt_address }}<br>
            </p>
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('beacon.update', ['beacon_id' => $beacon->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $beacon->name }}">

                            @error('name')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $beacon->description }}</textarea>

                            @error('description')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Cancel</a>
                    </form>

                    <form method="POST" action="{{ route('beacon.destroy', ['beacon_id' => $beacon->id]) }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger mt-3" id="beacon-delete-btn">Delete</button>

                        @error('delete')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <script>
                            document.getElementById('beacon-delete-btn').addEventListener('click', function(e) {
                                if(!confirm('Are you sure you want to delete this beacon?')) {
                                    e.preventDefault();
                                }
                            });
                        </script>
                    </form>

                </div>
            </div>
        <x-footer/>
    </body>
</html>
