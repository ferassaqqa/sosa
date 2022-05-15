<?php

namespace App\Http\Requests\controlPanel\users\courseStudents;

use Illuminate\Foundation\Http\FormRequest;

class newCourseStudentsRequest extends FormRequest
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
            'id_num'=>'required|numeric',
            'course_id'=>'required|numeric',
            'name'=>'required|string',
            'dob'=>'required|date_format:d-m-Y',
            'pob'=>'required|string',
        ];
    }
    public function messages()
    {
        return [
            'id_num.required'=>'يرجى ادخال رقم الهوية',
            'id_num.numeric'=>'رقم الهوية قيمة عددية',
            'course_id.required'=>'يرجى ادخال دورة',
            'course_id.numeric'=>'معرف الدورة قيمة عددية',
            'course_id.exists'=>'الدورة غير موجودة في قاعدة البيانات',
            'pob.required'=>'يرجى ادخال الاسم',
            'pob.string'=>'رقم الهوية قيمة نصية',
            'name.required'=>'يرجى ادخال الاسم',
            'name.string'=>'رقم الهوية قيمة نصية',
            'dob.required'=>'يرجى ادخال تاريخ الميلاد',
            'dob.date_format'=>'صيغة تاريخ الميلاد سنة - شهر - يوم',
        ];
    }
}
