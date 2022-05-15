<?php

namespace App\Http\Requests\controlPanel\Roles;

use Illuminate\Foundation\Http\FormRequest;

class ImportExcelRequest extends FormRequest
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
            'file'=> 'required|mimes:xls,xlsx'
        ];
    }
    public function messages(){
        return [
            'file.required'=> 'يرجى ارفاق ملف اكسل',
            'file.mimes'=> 'يرجى ارفاق ملف اكسل بامتداد xls أو xlsx'
        ];
    }
}
