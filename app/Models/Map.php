<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'map_base_image',
        'owner_id',
        'status',
        'public_url',
    ];

    /**
     * Get the owner of the map.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the beacons for the map.
     */
    public function beacons()
    {
        return $this->hasMany(Beacon::class, 'map_id');
    }
}
