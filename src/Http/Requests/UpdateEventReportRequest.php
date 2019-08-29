<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventReportRequest extends FormRequest
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
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'image_file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'approved' => 'sometimes|boolean',
            'reason_rejection' => [
                Rule::requiredIf($this->filled('approved') && $this->approved == 0)
            ],
            'archived' => function ($attribute, $value, $fail) {
                if(!$this->report->approved) {
                    $fail('only approved event/RSVP may be archived');
                }
            },
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}