<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventActivityRequest extends FormRequest
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
            'event_report_id' => [
                'required',
                Rule::exists('event_reports','id')->where(function ($query) {
                    $query->where('approved', 1)->whereNull('deleted_at');
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}