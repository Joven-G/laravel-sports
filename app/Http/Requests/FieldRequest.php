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
            'name' => 'required|max:200',
            'date' => 'required|date',
            'start' => [
              'required',
              'before:end',
              Rule::unique('fields')->ignore($this->field) // I don't know
              ],
            'end' => [
              'required',
              'after:start'
              ],
            'color' => 'required',
            'hour' => 'nullable',
            'field_number' => 'required',
            'user_id' => 'nullable',
        ];
    }
}