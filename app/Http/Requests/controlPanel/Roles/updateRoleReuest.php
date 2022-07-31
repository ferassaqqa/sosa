<?php

namespace App\Http\Requests\controlPanel\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class updateRoleReuest extends FormRequest
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
            'name'=>'required|string|unique:roles,name,'.$this->id,
        ];
    }

    public function messages()
    {
        $role = Role::onlyTrashed()->where('name',$this->name)->first();
        if($role) {if (!hasPermissionHelper('تصفح بيانات دور محذوف')) {
            return [
                'name.required' => 'يرجى ادخال قيمة',
                'name.string' => 'يجب ان تكون قيمة حقيقية',
                'name.unique' => $this->name . ' قيمة محذوفة مسبقا يرجى ادخال قيمة مختلفة ، لا تمتلك صلاحيات استعراض بياناته ولا استرجاعها ',
            ];
        }else{
            $restore_link = '<a href="#!" onclick="callApi(this,\'restore_modal_content\')" data-url="' . route('roles.showDeletedItem', $role->id) . '" data-bs-target=".res-restore-modal" data-bs-toggle="modal">هنا</a>';
            return [
                'name.required' => 'يرجى ادخال قيمة',
                'name.string' => 'يجب ان تكون قيمة حقيقية',
                'name.unique' => $this->name . ' قيمة محذوفة مسبقا يرجى ادخال قيمة مختلفة او اضغط ' . $restore_link . ' لاستعراض بياناته ',
            ];
        }
        }else{

            return [
                'name.required' => 'يرجى ادخال قيمة',
                'name.string' => 'يجب ان تكون قيمة حقيقية',
                'name.unique' => $this->name . ' قيمة موجودة مسبقا يرجى ادخال قيمة مختلفة ',
            ];
        }
    }
}
