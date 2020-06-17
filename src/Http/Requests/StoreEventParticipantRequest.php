<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEventParticipantRequest extends FormRequest
{
	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->volunteer->verified;
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
                }),
                Rule::unique('event_participants')->where(function($query) {
                    $query->where('volunteer_id', Auth::user()->volunteer->id);
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            'event_report_id.exists'=>'The event is either pending, rejected or archived',
            'event_report_id.unique'=>'The volunteer has already joined this event'
        ];
    }
}