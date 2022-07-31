<?php

namespace App\Http\Requests\controlPanel\Places;

use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updatePlaceRequest extends FormRequest
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
            'area_id'=>'required|string|exists:areas,id',
            'name'=>'required|string|unique_name_area_id:'.$this->area_id.','.$this->id,
        ];
    }
    public function messages(){
        return [
            'name.required' => 'يرجى ادخال قيمة',
            'name.string' => 'يجب ان تكون قيمة نصية',
            'name.unique_name_area_id' => 'لا يمكن اضافة مكان موجود مسبقا',
            'area_id.required' => 'يرجى ادخال قيمة',
            'area_id.string' => 'يجب ان تكون قيمة نصية',
            'area_id.exists' => 'لا يمكن ان يتم اضافة مكان لمنطقة غير موجودة',
        ];
    }
}
