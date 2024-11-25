<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplainantRequest extends FormRequest
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
            'country_id' => 'nullable|exists:countries,id',
            'salutation_id' => 'required|numeric|exists:salutations,id',
            'language' => 'required|string|size:2',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'postal_code' => 'required|alpha_dash',
            'city' => 'required|string',
            'street' => 'required|string',
            'house_number' => 'required|alpha_num',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/[A-Za-z0-9+ \-.]*/',
        ];
    }
}
