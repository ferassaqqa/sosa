<?php

namespace App\Http\Requests\controlPanel\Circles;

use App\Models\Circle;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateCircleRequest extends FormRequest
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
            'start_date'=>'required|date_format:Y-m-d',
            'place_id'=>'required|string|exists:places,id',
            'teacher_id'=>'required|string|exists:users,id|',
            'supervisor_id'=>'required|string|exists:users,id',
            'notes'=>'sometimes|string|nullable',
            // 'contract_type'=>'required',
            // 'contract_salary'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'start_date.required'=>'يرجى ادخال تاريخ البداية',
            'start_date.date_format'=>'صيغة التاريخ المطلوبة يوم - شهر - سنة',
            'place_id.required'=>'يرجى ادخال قيمة المكان',
            'place_id.string'=>'قيمة المكان المطلوبة نصية',
            'place_id.exists'=>'يرجى ادخال مكان معرف مسبقا',
            'teacher_id.required'=>'يرجى ادخال اسم المحفظ',
            'teacher_id.string'=>'اسم المحفظ المطلوب قيمة نصية',
            'teacher_id.exists'=>'يرجى ادخال اسم محفظ معرف مسبقا',
            'supervisor_id.required'=>'يرجى ادخال اسم المشرف',
            'supervisor_id.string'=>'اسم المشرف المطلوب قيمة نصية',
            'supervisor_id.exists'=>'يرجى ادخال اسم مشرف معرف مسبقا',

            // 'contract_type.required'=>'يرجى ادخال نوع الحلقة ',
            // 'contract_salary.required'=>'يرجى ادخال قيمة الكفاله ',

        ];
    }
}
