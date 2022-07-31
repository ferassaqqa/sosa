<?php

namespace App\Http\Requests\controlPanel\Places;

use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;

class newPlaceRequest extends FormRequest
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
            'area_id'=>'required|numeric|exists:areas,id',
            'name'=>'required|string|unique_name_area_id:'.$this->area_id.',0',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'مطلوب ادخال اسم المكان',
            'name.string' => 'يجب ان تكون نص',
            'name.unique_name_area_id' => 'لا يمكن اضافة مكان موجود مسبقا',
            'area_id.required' => 'يرجى ادخال منطقة محلية',
            'area_id.numeric' => 'يرجى ادخال عدد',
            'area_id.exists' => 'لا يمكن ان يتم اضافة مكان لمنطقة غير موجودة',
        ];
    }
}
