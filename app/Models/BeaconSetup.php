<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeaconSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'status',
        'api_key',
        'user_key',
    ];
}
