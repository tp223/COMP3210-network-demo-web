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
                    <script src="{{ asset('js/edit-map.js') }}"></script>
                </div>
                <div class="col-md-6">
                    <form method="POST" action="{{ route('map.update', ['map' => $map->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $map->name }}" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $map->description }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label for="mapImage">Map Image</label>
                            <input type="file" class="form-control" id="mapImage" name="mapImage">
                        </div>

                        <div class="form-group mt-3">
                            <label for="published">Published</label>
                            <select class="form-control" id="published" name="published" required>
                                <option value="0" @if($map->status == "hidden") selected @endif>No</option>
                                <option value="1" @if($map->status == "published") selected @endif>Yes</option>
                            </select>
                        </div>

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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($map->beacons as $beacon)
                                    <tr id="beacon-{{ $beacon->id }}">
                                        <td>{{ $beacon->id }}</td>
                                        <td>{{ $beacon->name }}</td>
                                        <td>{{ $beacon->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="#" class="btn btn-primary">Connect Beacon</a>
                    </div>
        <x-footer/>
    </body>
</html>
