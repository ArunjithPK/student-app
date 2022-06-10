<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StudentRequest extends FormRequest
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
        return $rules = [
            'name' => "required|max:100",
            'dob' => "required|date|date_format:Y-m-d|before_or_equal:".Carbon::now()->subYears(2)->format('Y-m-d'),
            'gender' => "required",
            'reporting_teacher_id' => "required",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'dob.required' => 'Date of birth is required.',
            'dob.before_or_equal' => 'Date of birth must be before or equal to '.Carbon::now()->subYears(3)->format('M d Y'),
            'gender.required' => 'Gender is required.',
            'reporting_teacher_id.required' => 'Reporting teacher is required.',
        ];
    }
}
