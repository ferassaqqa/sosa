<?php

namespace App\Http\Requests\asaneedCourses;

use Illuminate\Foundation\Http\FormRequest;

class newAsaneedCourseRequest extends FormRequest
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
//        dd($this->all());
        return [
            'start_date'        =>'required|date_format:Y-m-d',
            'place_id'          =>'required|numeric|exists:places,id',
//            'course_type'       =>'required|in:إختبار,حضور',
            // 'included_in_plan'  =>'required|in:خارج الخطة,داخل الخطة',
            'book_id'           =>'required|numeric|exists:asaneed_books,id',
            'teacher_id'        =>'required|numeric|exists:users,id',
//            'hours'             =>'required|numeric|min:1',
        ];
    }
    public function messages()
    {
        return [
            'start_date.required'        =>'يرحى ادخال قيمة بداية الدورة',
            'start_date.date_format'     =>'صيغة التاريخ المطلوبة يوم - شهر - سنة',
            'place_id.required'          =>'يرجى ادخال قيمة المكان',
            'place_id.numeric'           =>'المكان قيمة عددية',
            'place_id.exists'            =>'قيمة المكان يجب ان تكون معرفة مسبقا',
            // 'included_in_plan.required'  =>'يرجى تحديد الدورة خارج او داخل الخطة',
            // 'included_in_plan.in'        =>'خارج الخطة أو داخل الخطة',
            'book_id.required'           =>'مطلوب ادخال الكتاب',
            'book_id.numeric'            =>'معرف الكتاب قيمة عددية',
            'book_id.exists'             =>'الكتاب غير معرف مسبقا يرجى الادخال مرة أخرى',
            'teacher_id.required'        =>'يرجى ادخال اسم المعلم',
            'teacher_id.numeric'         =>'معرف اسم المعلم قيمة عددية',
            'teacher_id.exists'          =>'يرجى ادخال معلم معرف مسبقا داخل البرنامج',
            'student_category.required'  =>'يرجى ادخال فئة الطلاب',
            'student_category.string'    =>'فئة الطلاب قيمة نصية',
            'student_category.in'        =>'ابتدائية أو إعدادية أو ثانوية أو ثانوية فما فوق',
        ];
    }
}
