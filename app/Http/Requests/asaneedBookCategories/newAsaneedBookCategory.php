<?php

namespace App\Http\Requests\asaneedBookCategories;

use Illuminate\Foundation\Http\FormRequest;

class newAsaneedBookCategory extends FormRequest
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
            'name'=>'required|string|unique:asaneed_book_categories,name'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.string' => 'اسم التصنيف نص',
            'name.unique' => 'اسم التصنيف موجود مسبقا ، يرجى ادخال اسم جديد',
        ];
    }
}
