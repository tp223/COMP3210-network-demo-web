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
            <h1>Add Map</h1>
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('map.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="" required>
                        </div>

                        @error('name')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
                                <option value="hidden">No</option>
                                <option value="published">Yes</option>
                            </select>
                        </div>

                        @error('status')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                </div>
            </div>
        <x-footer/>
    </body>
</html>
