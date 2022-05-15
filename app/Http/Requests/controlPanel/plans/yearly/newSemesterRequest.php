<?php

namespace App\Http\Requests\controlPanel\plans\yearly;

use Illuminate\Foundation\Http\FormRequest;

class newSemesterRequest extends FormRequest
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
            'book_plan_year_id'=>'required|numeric|exists:book_plan_years,id',
            'year_semester'=>'required|string|SemesterNameAndYearId:'.$this->book_plan_year_id.','.$this->id
        ];
    }
}
