<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'identification', 'type', 'name', 'latitude', 'longitude', 'continent_id', 'country_id', 'region_id',
        'municipality', 'gps_code', 'iata_code', 'source_id', 'created_at'
    ];
}
