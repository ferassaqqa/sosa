<?php

namespace App\Http\Requests\controlPanel\users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class newUserRequest extends FormRequest
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
//        if($this->role_id == 'مشرف ميداني'){
//            $this->area_id = null;
//        }
        $id_num_role = 'required|numeric|unique:users,id_num';
        $old_user = User::where('id_num',$this->id_num)->first();
        if($old_user){
            $id_num_role = 'required|numeric';
        }
        $area_id_role = 'nullable';
        if($this->role_id=='مشرف عام'){
            $area_id_role = 'required|numeric|exists:areas,id';
        }
//        var_dump($area_id_role);
        return [
            'prefix'=>'required|string|in:أ,م,د',
            'id_num'=>$id_num_role,
//            'name'=>'required|string',
            'mobile'=>'required|numeric',
            'email'=>'sometimes|email|nullable',
            'role_id'=>'required|string|exists:roles,name',
            'area_id'=>$area_id_role,
            // 'sub_area_id'=>'required_if:role_id,مشرف ميداني|numeric|exists:areas,id',
        ];
    }
    public function messages()
    {
        return [
            'prefix.required'=>'يرجى اضافة مسمى',
            'prefix.in'=>'أ او م أو د',
            'id_num.required'=>'يرجى ادخال رقم الهوية',
            'id_num.numeric'=>'رقم الهوية قيمة عددية',
            'name.required'=>'يرجى ادخال الاسم',
            'name.string'=>'الاسم قيمة نصية',
            'mobile.required'=>'يرجى ادخال رقم الجوال',
            'mobile.numeric'=>'الجوال قيمة عددية',
            'email.required'=>'يرجى ادخال بريد الكتروني',
            'email.email'=>'صيغة البريد الالكتروني خاطئة',
            'role_id.string'=>'صلاحيات المستخدم يجب ان تكون نصية',
            'role_id.exists'=>'لم يتم التعرف على الصلاحيات المطلوبة داخل البرنامج',
            'area_id.exists'=>'لم يتم التعرف على المنطقة المطلوبة داخل البرنامج',
            // 'sub_area_id.exists'=>'لم يتم التعرف على المنطقة المطلوبة داخل البرنامج',

        ];
    }
}
