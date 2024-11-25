<?php

namespace App\Http\Requests\Export;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ClaimRequest extends FormRequest
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
            'from' => 'nullable|date|before:' . (new Carbon)->addDay(2)->toDateString(),
            'until' => 'nullable|date|before:' . (new Carbon)->addDay(2)->toDateString(),
        ];
    }
}
