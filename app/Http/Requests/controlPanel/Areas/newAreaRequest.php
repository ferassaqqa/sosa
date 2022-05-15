<?php

namespace App\Http\Requests\controlPanel\Areas;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;

class newAreaRequest extends FormRequest
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
            'name'=>'required|string|unique:areas,name',
            'area_percentage'=>'required|numeric',
            'subArea'=>'sometimes|array|nullable',
            'subArea.*'=>'string|unique:areas,name',
        ];
    }
    public function messages()
    {
        return [
            'area_percentage.required' => 'يرجى ادخال قيمة',
            'area_percentage.number' => 'يرجى ادخال قيمة عددية',
            'name.required' => 'يرجى ادخال قيمة',
            'name.string' => 'يجب ان تكون قيمة نصية',
            'name.unique' => $this->name . ' قيمة موجودة مسبقا يرجى ادخال قيمة مختلفة ',
            'subArea.array' => 'المحليات يجب ان تكون من نوع مصفوفة',
            'subArea.*.string' => 'يجب ان تكون قيمة نصية',
            'subArea.*.unique' => ' منطقة محلية موجودة مسبقا يرجى ادخال منطقة أخرى'
        ];
    }
}
