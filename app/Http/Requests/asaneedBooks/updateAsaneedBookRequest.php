<?php

namespace App\Http\Requests\asaneedBooks;

use Illuminate\Foundation\Http\FormRequest;

class updateAsaneedBookRequest extends FormRequest
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
            'name'=>'required|string|unique:books,name,'.$this->id,
            'hadith_count'=>'required_if:hours_count,null|numeric',
            'hours_count'=>'required_if:hadith_count,null|numeric',
            'pass_mark'=>'required|numeric',
            'book_code'=>'required|string|unique:books,name',
            // 'included_in_plan'=>'required|in:خارج الخطة,داخل الخطة',
            'year'=>'required|numeric',
//            'type'=>'required|string|in:سنوية,ساعات',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'قيمة الحقل مطلوبة',
            'name.string'=>'قيمة الحقل يجب ان تكون نصية',
            'name.unique'=>'قيمة الحقل يجب ان تكون فريدة ، لا يمكن ادخال كتابين بنفس الاسم',
            // 'included_in_plan.required'=>'حدد نوع الكتاب',
            // 'included_in_plan.in'=>'نوع الكتاب خارج الخطة أو داخل الخطة',
            'hadith_count.required_if'=>'عدد الاحاديث مطلوب',
            'hadith_count.numeric'=>'عدد الاحاديث قيمة عددية',
            'hours_count.required_if'=>'عدد الساعات مطلوب',
            'hours_count.numeric'=>'عدد الساعات قيمة عددية',
            'pass_mark.required'=>'علامة النجاح مطلوبة',
            'pass_mark.numeric'=>'علامة النجاح قيمة عددية',
            'year.required'=>'السنة مطلوبة',
            'year.numeric'=>'السنة قيمة عددية',
            'book_code.required'=>'كود الكتاب مطلوب',
            'book_code.string'=>'كود الكتاب قيمة نصية',
            'book_code.unique'=>'كود الكتاب قيمة فريدة',
        ];
    }
}
