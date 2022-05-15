<?php

namespace App\Http\Requests\controlPanel\Circles\Books;

use Illuminate\Foundation\Http\FormRequest;

class newCircleBookRequest extends FormRequest
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
            'name'=>'required|string|unique:circle_books,name',
            'hadith_count'=>'required|numeric',
            'pass_mark'=>'required|numeric',
            'book_code'=>'required|string|unique:circle_books,name',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'قيمة الحقل مطلوبة',
            'name.string'=>'قيمة الحقل يجب ان تكون نصية',
            'name.unique'=>'قيمة الحقل يجب ان تكون فريدة ، لا يمكن ادخال كتابين بنفس الاسم',
            'pass_mark.required'=>'علامة النجاح مطلوبة',
            'pass_mark.numeric'=>'علامة النجاح قيمة عددية',
            'hadith_count.required'=>'عدد الأحاديث مطلوب',
            'hadith_count.numeric'=>'عدد الأحاديث قيمة عددية',
            'book_code.required'=>'كود الكتاب مطلوب',
            'book_code.string'=>'كود الكتاب قيمة نصية',
            'book_code.unique'=>'كود الكتاب قيمة فريدة',
        ];
    }
}
