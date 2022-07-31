<?php

namespace App\Http\Requests\controlPanel\plans\yearly;

use Illuminate\Foundation\Http\FormRequest;

class newYearRequest extends FormRequest
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
            'book_plan_id'=>'required|numeric|exists:book_plans,id',
            'plan_year'=>'required|string|YearNameAndPlanId:'.$this->book_plan_id.','.$this->id
        ];
    }
}
