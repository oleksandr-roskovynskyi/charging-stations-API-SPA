<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargingStation extends Model
{
    protected $fillable = ['name', 'city', 'open_from', 'open_to', 'latitude', 'longitude'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'open_from' => 'datetime:H:i',
        'open_to' => 'datetime:H:i',
    ];
}
