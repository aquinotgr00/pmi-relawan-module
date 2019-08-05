<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubdistrictRequest extends FormRequest
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
            'id' => 'required',
            'city_id' => 'required|exists:cities,id',
            'name' => 'unique:subdistricts,name,' .$this->request->get('id'). ',id',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}