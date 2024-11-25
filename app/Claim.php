<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = ['claim_type_id', 'remarks', 'source', 'file_number'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function complainant()
    {
        return $this->hasOne(Complainant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flights()
    {
        return $this->hasMany(ClaimFlight::class);
    }

    public function claimType()
    {
        return $this->belongsTo(ClaimType::class);
    }

    public function betweenWithDetails($from, $until)
    {
        return self::select()
            ->whereBetween('created_at', [$from, $until])
            ->with('claimType',
                'complainant.country',
                'complainant.salutation',
                'flights.departureAirport',
                'flights.destinationAirport',
                'flights.airline',
                'flights.delay')
            ->get();
    }
}
