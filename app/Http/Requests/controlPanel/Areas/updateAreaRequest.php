<?php

namespace App\Http\Requests\controlPanel\Areas;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;

class updateAreaRequest extends FormRequest
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
//        dd($this->all());
        $rulesArray = [
            'name' => 'required|string|unique:areas,name,' . $this->id,
            'area_percentage' => 'required|numeric',
        ];
        if(isset($this->subArea)&& count($this->subArea)) {

            foreach ($this->subArea as $key => $subArea) {
                $sub_area = null;
                if (isset($this->subArea_id[$key])) {
                    $sub_area = Area::find($this->subArea_id[$key]);
                }
                if ($sub_area) {
                    if ($sub_area->area_id == $this->id) {
                        $rulesArray ['subArea.' . $key] = 'string|unique:areas,name,' . $this->subArea_id[$key];
                    } else {
                        $rulesArray ['subArea.' . $key] = 'string';
                    }
                } else {
                    $rulesArray ['subArea.' . $key] = 'string';
                }
            }
        }
        return $rulesArray;
    }
    public function messages()
    {
        return [
            'area_percentage.required' => 'يرجى ادخال قيمة',
            'area_percentage.number' => 'يرجى ادخال قيمة عددية',
            'name.required' => 'يرجى ادخال قيمة',
            'name.string' => 'يجب ان تكون قيمة نصية',
            'name.unique' => $this->name . ' قيمة موجودة مسبقا يرجى ادخال قيمة مختلفة ',
            'subArea.*.string' => 'يجب ان تكون قيمة نصية',
            'subArea.*.unique' => ' منطقة محلية موجودة مسبقا يرجى ادخال منطقة أخرى'
        ];
    }
}
