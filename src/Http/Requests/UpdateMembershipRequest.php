<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMembershipRequest extends FormRequest
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
            'name' => 'required|unique:memberships,name,' .$this->membership->id. ',id',
            'code' => 'unique:memberships,code,' .$this->membership->id. ',id',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}