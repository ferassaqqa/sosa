<?php

namespace App\Http\Requests\controlPanel\users\circleStudents;

use Illuminate\Foundation\Http\FormRequest;

class updateCircleStudentsRequest extends FormRequest
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
            'prefix'=>'required|string|in:أ,م,د',
            'id_num'=>'required|numeric|unique:users,id_num,'.$this->id,
//            'name'=>'required|string',
//            'dob'=>'required|date_format:d-m-Y',
            'place_id'=>'required|numeric|exists:places,id',
            'mobile'=>'nullable|numeric',
            'study_level'=>'nullable|string|in:الثانوية,دبلوم,بكلوريوس,ماجستير,دكتوراة,ابتدائي,اعدادي',
            'course_name.*'=>'string',
            'user_profile'=>'mimes:jpg,png,jpeg|nullable',
            'teacher_id'=>'required|numeric|exists:users,id',

        ];
    }
    public function messages()
    {
        return [
            'prefix.required'=>'يرجى اضافة مسمى',
            'prefix.in'=>'أ او م أو د',
            'id_num.required'=>'يرجى ادخال رقم الهوية',
            'id_num.numeric'=>'رقم الهوية قيمة عددية',
            'teacher_id.required'=>'يرجى ادخال محفظ',
            'teacher_id.numeric'=>'معرف المحفظ قيمة عددية',
            'teacher_id.exists'=>'المحفظ غير موجود في قاعدة البيانات',
//            'name.required'=>'يرجى ادخال الاسم',
//            'name.string'=>'رقم الهوية قيمة نصية',
//            'dob.required'=>'يرجى ادخال تاريخ الميلاد',
//            'dob.date_format'=>'صيغة تاريخ الميلاد سنة - شهر - يوم',
            'place_id.required'=>'يرجى ادخال المكان',
            'place_id.numeric'=>'معرف المكان قيمة عددية',
            'place_id.exists'=>'معرف المكان قيمة موجودة في قاعدة البيانات',
            'mobile.numeric'=>'الجوال قيمة عددية',
            'study_level.string'=>'يرجى ادخال نص',
            'study_level.in'=>'الثانوية أو دبلوم أو بكلوريوس أو ماجستير أو دكتوراة أو ابتدائي أو اعدادي',
            'user_profile.mimes'=>'يرجى ادخال صورة من نوع jpg أو png أو jpeg ',
        ];
    }
}
