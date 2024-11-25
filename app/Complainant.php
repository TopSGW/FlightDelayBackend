<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complainant extends Model
{
    protected $fillable = [
        'claim_id',
        'country_id',
        'salutation_id',
        'language',
        'first_name',
        'last_name',
        'postal_code',
        'city',
        'street',
        'house_number',
        'box_number',
        'email',
        'phone_number',
        'remarks'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salutation()
    {
        return $this->belongsTo(Salutation::class);
    }
}
