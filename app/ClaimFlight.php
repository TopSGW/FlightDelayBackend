<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimFlight extends Model
{
    protected $fillable = [
        'claim_id',
        'departure_airport_id',
        'destination_airport_id',
        'airline_id',
        'delay_id',
        'flight_number',
        'flight_date',
        'is_initial_flight',
        'flight_order'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class);
    }

    public function destinationAirport()
    {
        return $this->belongsTo(Airport::class);
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function delay()
    {
        return $this->belongsTo(Delay::class);
    }
}
