<?php

namespace App\Http\Requests\controlPanel\plans\yearly;

use Illuminate\Foundation\Http\FormRequest;

class newMonthRequest extends FormRequest
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
            'book_plan_year_semester_id' => 'required|numeric|exists:book_plan_year_semesters,id',
            'semester_month' => 'required|string|SemesterMonthAndSemesterId:'.$this->book_plan_year_semester_id.','.$this->id,
            'from_hadith' => 'required|numeric|FromHadithAndSemesterId:'.$this->book_plan_year_semester_id.','.$this->id,
            'to_hadith' => 'required|numeric|ToHadithAndSemesterId:'.$this->book_plan_year_semester_id.','.$this->id,
            'hadith_count' => 'required|numeric',
        ];
    }
}
