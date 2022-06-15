<?php

namespace App\Http\Requests\controlPanel\exams;

use Illuminate\Foundation\Http\FormRequest;

class newAsaneedExamRequest extends FormRequest
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
            'mark'=>'required|array',
            'mark.*'=>'required|numeric|min:0|max:100',
        ];
    }
}
