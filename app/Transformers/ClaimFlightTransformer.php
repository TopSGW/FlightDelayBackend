<?php

namespace App\Transformers;

use Carbon\Carbon;

class ClaimFlightTransformer extends Transformer
{
    /**
     * @param array $flight
     * @return array
     */
    public function transform(array $flight): array
    {
        return [
            'id' => $flight['id'],
            'claim_id' => (int)$flight['claim_id'],
            'departure_airport_id' => (int)$flight['departure_airport_id'],
            'destination_airport_id' => (int)$flight['destination_airport_id'],
            'airline_id' => (int)$flight['airline_id'],
            'delay_id' => (int)$flight['delay_id'],
            'flight_number' => $flight['flight_number'],
            'flight_date' => $flight['flight_date'] ? Carbon::createFromFormat('Y-m-d H:i:s', $flight['flight_date'])->toDateString() : null,
            'is_initial_flight' => (bool)$flight['is_initial_flight'],
            'flight_order' => (int)$flight['flight_order'],
            'created_at' => $flight['created_at'],
            'updated_at' => $flight['updated_at'],
        ];
    }
}