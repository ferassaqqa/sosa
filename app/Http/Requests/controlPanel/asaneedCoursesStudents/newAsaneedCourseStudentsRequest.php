<?php

namespace App\Http\Requests\controlPanel\asaneedCoursesStudents;

use Illuminate\Foundation\Http\FormRequest;

class newAsaneedCourseStudentsRequest extends FormRequest
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
            'asaneed_course_id'=>'required|numeric|exists:asaneed_courses,id',
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
            'asaneed_course_id.required'=>'يرجى ادخال دورة',
            'asaneed_course_id.numeric'=>'معرف الدورة قيمة عددية',
            'asaneed_course_id.exists'=>'الدورة غير موجودة في قاعدة البيانات',
            'pob.required'=>'يرجى ادخال الاسم',
            'pob.string'=>'رقم الهوية قيمة نصية',
            'name.required'=>'يرجى ادخال الاسم',
            'name.string'=>'رقم الهوية قيمة نصية',
            'dob.required'=>'يرجى ادخال تاريخ الميلاد',
            'dob.date_format'=>'صيغة تاريخ الميلاد سنة - شهر - يوم',
        ];
    }
}
