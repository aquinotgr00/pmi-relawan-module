<?php

namespace BajakLautMalaka\PmiRelawan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class StoreVolunteerRequest extends FormRequest
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
            'name'=>'required|string',
            'email'=>[
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $registered = User::whereHas('volunteer')->where('email', $value)->count();
                    if ($registered) {
                        $fail("$attribute $value".' already registered as volunteer');
                    }
                }
            ],
            'phone'=>'required|unique:volunteers',
            'password'=>'required|confirmed',
            'birthplace'=>'string',
            'gender'=>'in:male,female',
            'religion'=>Rule::in(config('volunteer.religion')),
            'blood_type'=>Rule::in(config('volunteer.bloodType')),
            'dob'=>'date_format:Y-m-d',
            'postal_code'=>'numeric',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'qualifications.*.description'=>'required',
            'qualifications.*.category'=>[
                'required',
                Rule::in(array_keys(config('volunteer.qualification.category')))
            ],
            'unit_id'=>'required|exists:unit_volunteers,id'
        ];
    }
}
