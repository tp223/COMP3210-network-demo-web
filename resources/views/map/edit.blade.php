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
            <h1>Edit Map</h1>
            <h3>{{ $map->name }}</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="edit-map" id="edit-map" data-map="{{ $map->id }}"></div>
                </div>
                <div class="col-md-6">
                    <form method="POST" action="{{ route('map.update', ['map' => $map->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $map->name }}" required>
                        </div>

                        @error('name')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $map->description }}</textarea>
                        </div>

                        @error('description')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <div class="form-group mt-3">
                            <label for="map_base_image">Map Image</label>
                            <input type="file" class="form-control" id="map_base_image" name="map_base_image">
                        </div>

                        @error('map_base_image')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <div class="form-group mt-3">
                            <label for="status">Published</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="hidden" @if($map->status == "hidden") selected @endif>No</option>
                                <option value="published" @if($map->status == "published") selected @endif>Yes</option>
                            </select>
                        </div>

                        @if ($map->status == 'published')
                            <p><small>Public URL: <a href="{{ route('map.show', ['key' => $map->public_url]) }}">{{ route('map.show', ['key' => $map->public_url]) }}</a></small></p>
                        @endif

                        @error('status')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>

                    <h3 class="mt-3">Beacons</h3>
                    <p>The beacons listed here can be plotted on the map by clicking the beacon followed by the location on the map.</p>
                    <div class="table-responsive small">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($map->beacons as $beacon)
                                    <tr id="beacon-{{ $beacon->id }}" class="beacon-row">
                                        <td>{{ $beacon->id }}</td>
                                        <td>{{ $beacon->name }}</td>
                                        <td>{{ $beacon->status }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('map.beacon.destroy', ['map' => $map->id]) }}" class="mb-1">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="beacon_id" value="{{ $beacon->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">Disconnect</button>
                                            </form>
                                            <button class="btn btn-primary btn-sm plot-beacon-on-map-btn" data-beacon="{{ $beacon->id }}">Plot on Map</button>
                                            <button class="btn btn-primary btn-sm reset-beacon-on-map-btn" data-beacon="{{ $beacon->id }}" disabled>Reset Position</button>
                                            <a href="{{ route('beacon.edit', ['beacon_id' => $beacon->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <form method="POST" action="{{ route('map.beacon.store', ['map' => $map->id]) }}" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-select" id="beacon-select" aria-label="Add beacon to map" name="beacon_id">
                                        <option selected>Choose...</option>
                                        @foreach($unassignedBeacons as $beacon)
                                            <option value="{{ $beacon->id }}">{{ $beacon->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary" type="submit">Connect Beacon</button>
                                </div>
                            </div>

                            @error('beacon_id')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>

                    <form method="POST" action="{{ route('map.destroy', ['map' => $map->id]) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="delete-map-btn">Delete Map</button>
                    </form>

                    @error('confirm')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                    @enderror

                    <script>
                        document.querySelector('#delete-map-btn').addEventListener('click', function(e) {
                            e.preventDefault();
                            if (confirm('Are you sure you want to delete this map?')) {
                                this.closest('form').submit();
                            }
                        });
                    </script>

                </div>
            </div>
        <x-footer/>
        <script src="{{ asset('js/edit-map.js') }}"></script>
    </body>
</html>
