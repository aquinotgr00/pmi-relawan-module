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
            'title' => 'unique:event_reports,title,' .$this->report->id . ',id',
            'description' => Rule::requiredIf(null !== $this->input('title')),
            'image_file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'approved' => 'nullable|boolean',
            'reason_rejection' => Rule::requiredIf((null !== $this->input('approved')) && ($this->input('approved') == 0)),
            'archived' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}