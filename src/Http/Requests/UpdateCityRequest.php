<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
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
            'province_id' => 'required|exists:provinces,id',
            'name' => 'unique:cities,name,' .$this->city->id. ',id',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}