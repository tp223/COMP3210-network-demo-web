<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beacon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'map_id',
        'api_key',
        'owner_id',
        'status',
    ];

    /**
     * Get the owner of the beacon.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the map attached to the beacon.
     */
    public function map()
    {
        return $this->belongsTo(Map::class, 'map_id');
    }
}
