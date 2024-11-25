<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'flight_number_is_known' => 'required|boolean',
            'departure_airport_id' => 'nullable|numeric|min:1|exists:airports,id',
            'destination_airport_id' => 'nullable|numeric|min:1|exists:airports,id',
            'airline_id' => 'nullable|numeric|min:1|exists:airlines,id',
            'delay_id' => 'required|numeric|exists:delays,id',
            'flight_number' => 'required_if:flight_number_is_known,true',
            'flight_date' => 'required|date|before_or_equal:' . date('Y-m-d'),
            'is_initial_flight' => 'required|boolean',
            'flight_order' => 'required|numeric|min:1',
        ];
    }
}
