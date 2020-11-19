<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FieldRequest extends FormRequest
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
            'name' => 'required',
            'date' => 'required|date',
            'start' => [
              'required',
              Rule::unique('fields')->ignore($this->field)
              ],
            'end' => 'required',
            'color' => 'required',
            'hour' => 'nullable',
            'user_id' => 'nullable',
        ];
        // $rules = [
        //     'name' => 'required',
        //     'date' => 'required|date',
        //     'start' => [
        //       'required',
        //       Rule::unique('users')->ignore($this->route('user')->id)
        //       ],
        //     'end' => 'required',
        //     'color' => 'required',
        //     'hour' => 'nullable',
        //     'user_id' => 'nullable',
        // ];

        // return $rules;
    }
}