<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
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
            'city_id' => 'required|exists:cities,id',
            'membership_id' => 'required|exists:memberships,id',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}