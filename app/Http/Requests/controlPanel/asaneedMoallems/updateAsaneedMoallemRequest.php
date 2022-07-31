<?php

namespace App\Http\Requests\controlPanel\asaneedMoallems;

use Illuminate\Foundation\Http\FormRequest;

class updateAsaneedMoallemRequest extends FormRequest
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
        if(!$this->course_teacher_name){
            $this->course_teacher_name = array();
        }
        return [
            'prefix'=>'required|string|in:أ,م,د',
//            'id_num'=>'required|numeric|unique:users,id_num,'.$this->id,
            'id_num'=>'required|numeric',
//            'sons_count'=>'required|numeric',
//            'material_status'=>'required|string|in:أعزب,مطلق,متزوج',
//            'name'=>'required|string',
//            'pob'=>'required|string',
//            'dob'=>'required|date_format:d-m-Y',
            'address'=>'required|string',
            'place_id'=>'required|numeric|exists:places,id',
            'mobile'=>'required|numeric',
            'home_tel'=>'nullable|numeric',
            'email'=>'nullable|email',
            'fb_link'=>'nullable|url',
            'study_level'=>'nullable|string|in:الثانوية,دبلوم,بكلوريوس,ماجستير,دكتوراة',
            'collage'=>'nullable|string',
            'speciality'=>'nullable|string',
            'occupation'=>'nullable|string',
            'occupation_place'=>'nullable|required_with:occupation|string',
            'course_name'=>'nullable|required_with:course_teacher_name,course_year|size:'.count($this->course_teacher_name).'|array',
            'course_name.*'=>'string',
            'user_comment'=>'nullable|array',
            'user_comment.*'=>'string',
            'enclose_comment'=>'nullable|required_with:encloses|array',
            'enclose_comment.*'=>'string',
            'monthly_income'=>'nullable|string|in:ضعيف (أقل من 1000 شيكل),جيد (من1000 حتى 2000),ممتاز (أكثر من 2000),بدون',
            'join_date'=>'nullable|date_format:Y-m-d',
            'computer_skills'=>'nullable|string|in:جيد,ضعيف,ممتاز',
            'english_skills'=>'nullable|string|in:جيد,ضعيف,ممتاز',
            'health_skills'=>'nullable|string|in:جيد,ضعيف,ممتاز',
            'contract_type'=>'nullable|string|in:مكفول,متطوع',
            'contract_type_value'=>'nullable|required_with:contract_type|string',
            'user_profile'=>'nullable|mimes:jpg,png,jpeg',
            'encloses'=>'nullable|array',
            'encloses.*'=>'nullable|mimeTypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/png,image/jpg,image/jpeg,video/mp4,application/zip,application/x-rar-compressed',
        ];
    }
    public function messages()
    {
        return [
            'prefix.required'=>'يرجى اضافة مسمى',
            'prefix.in'=>'أ او م أو د',
            'id_num.required'=>'يرجى ادخال رقم الهوية',
            'id_num.numeric'=>'رقم الهوية قيمة عددية',
//            'sons_count.required'=>'يرجى ادخال عدد الابناء',
//            'sons_count.numeric'=>'عدد الابناء قيمة عددية',
//            'name.required'=>'يرجى ادخال الاسم',
//            'name.string'=>'رقم الهوية قيمة نصية',
//            'pob.required'=>'يرجى ادخال مكان الميلاد',
//            'pob.string'=>'مكان الميلاد قيمة نصية',
//            'dob.required'=>'يرجى ادخال تاريخ الميلاد',
//            'dob.date_format'=>'صيغة تاريخ الميلاد سنة - شهر - يوم',
//            'material_status.required'=>'يرجى ادخال الحالة الاجتماعية',
//            'material_status.string'=>'الحالة الاجتماعية قيمة نصية',
//            'material_status.in'=>'أعزب أو مطلق أو متزوج',
            'address.required'=>'يرجى ادخال العنوان',
            'address.string'=>'العنوان قيمة نصية',
            'place_id.required'=>'يرجى ادخال المكان',
            'place_id.numeric'=>'معرف المكان قيمة عددية',
            'place_id.exists'=>'معرف المكان قيمة موجودة في قاعدة البيانات',
            'mobile.required'=>'يرجى ادخال رقم الجوال',
            'mobile.numeric'=>'الجوال قيمة عددية',
            'home_tel.numeric'=>'الهاتف قيمة عددية',
            'email.email'=>'يرجى ادخال بريد الالكتروني صالح',
            'fb_link.url'=>'يرجى ادخال رابط صحيح',
            'collage.string'=>'يرجى ادخال نص',
            'study_level.string'=>'يرجى ادخال نص',
            'speciality.string'=>'يرجى ادخال نص',
            'occupation.string'=>'يرجى ادخال نص',
            'occupation_place.string'=>'يرجى ادخال نص',
            'occupation_place.required_with'=>'مطلوب ادخال قيمة نصية',
            'study_level.in'=>'الثانوية أو دبلوم أو بكلوريوس أو ماجستير أو دكتوراة',
            'course_name.array'=>'من نوع مصفوفة',
            'course_name.required_with'=>'مطلوب ادخال نوع مصفوفة',
            'course_name.*.string'=>'يرجى ادخال نص',
            'enclose_comment.array'=>'من نوع مصفوفة',
            'enclose_comment.required_with'=>'مطلوب ادخال نوع مصفوفة',
            'enclose_comment.*.string'=>'يرجى ادخال نص',
            'user_comment.array'=>'من نوع مصفوفة',
            'user_comment.*.string'=>'يرجى ادخال نص',
            'monthly_income.string'=>'يرجى ادخال نص',
            'monthly_income.in'=>'ضعيف (أقل من 1000 شيكل) أو جيد (من1000 حتى 2000) أو ممتاز (أكثر من 2000) أو بدون',
            'join_date.date_format'=>'صيغة تاريخ الميلاد يوم - شهر - سنة',
            'computer_skills.string'=>'يرجى ادخال نص',
            'computer_skills.in'=>'جيد أو ضعيف أو ممتاز',
            'health_skills.string'=>'يرجى ادخال نص',
            'health_skills.in'=>'جيد أو ضعيف أو ممتاز',
            'english_skills.string'=>'يرجى ادخال نص',
            'english_skills.in'=>'جيد أو ضعيف أو ممتاز',
            'contract_type_value.string'=>'يرجى ادخال نص',
            'contract_type_value.required_with'=>'مطلوب ادخال بيانات',
            'contract_type.string'=>'يرجى ادخال نص',
            'contract_type.in'=>'مكفول أو متطوع',
            'user_profile.mimes'=>'يرجى ادخال صورة من نوع jpg أو png أو jpeg ',
            'encloses.array'=>'من نوع مصفوفة',
            'encloses.*.mime_types'=>'يرجى ادخال احدى الصيغ التالية "xlsx - xls - docx - pptx - jpg - jpeg - png - mp4 - rar - zip"',
        ];
    }
}
