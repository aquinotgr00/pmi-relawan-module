<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubTypeRequest extends FormRequest
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
            'member_type_id' => 'required|exists:member_types,id',
            'name' => 'unique:sub_member_types,name,' .$this->subtype->id. ',id',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}