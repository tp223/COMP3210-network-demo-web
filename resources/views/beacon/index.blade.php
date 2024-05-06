@foreach ($beacons as $beacon)
    <tr onclick="window.location='{{ route('beacon.edit', ['beacon' => $beacon->id]) }}'" role="button">
        <td>{{ $beacon->id }}</td>
        <td>{{ $beacon->name }}</td>
        <td>{{ $beacon->map->name }}</td>
        <td>{{ $beacon->status }}</td>
    </tr>
@endforeach
@if (count($beacons) == 0)
    <tr>
        <td colspan="4">No beacons found.</td>
    </tr>
@endif