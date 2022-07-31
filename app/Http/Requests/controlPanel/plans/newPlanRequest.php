<?php

namespace App\Http\Requests\controlPanel\plans;

use App\Models\Area;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ClosureValidationRule;

class newPlanRequest extends FormRequest
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
        if($this->department == 1) {
            return [
                'year'=>'required',
                'yearly_count'=>['required','array',
                    function($attribute, $value, $fail) {
                        $ids = array_keys($value);
                        $book_ids_count = Book::whereIn('id', $ids)->where('department',1)->count();
                        if ($book_ids_count != count($ids)) {
                            return $fail($attribute . ' كتاب غير موجود.');
                        }

                    }
                ],
                'yearly_count.*'=>['required','array',
                    function($attribute, $value, $fail) {
                        foreach ($value as $key => $itemValue){
                            if($key>1){
                                return $fail($attribute . ' يرجى عدم ادخال قيم لغير المكفول والمتطوع .');
                            }
                            if (!$itemValue){
                                return $fail($attribute . ' يرجى ادخال قيمة حقيقية .');
                            }
                        }
                    }
                ],
                'year_semester'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        $ids = array_keys($value);
                        $book_ids_count = Book::whereIn('id', $ids)->where('department',1)->count();
                        if ($book_ids_count != count($ids)) {
                            return $fail($attribute . ' كتاب غير موجود.');
                        }

                    }
                ],
                'year_semester.*'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        foreach ($value as $key => $itemValues){
                            if($key>1){
                                return $fail($attribute . ' يرجى عدم ادخال قيم لغير المكفول والمتطوع .');
                            }
//                            dd($semestersValues);
                            foreach ($itemValues as $itemKey => $itemValue) {
                                if (!$itemValue) {
                                    return $fail($attribute . ' يرجى ادخال قيمة حقيقية .');
                                }
                            }
                        }
                    }
                ],
                'semester_count'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        $ids = array_keys($value);
                        $book_ids_count = Book::whereIn('id', $ids)->where('department',1)->count();
                        if ($book_ids_count != count($ids)) {
                            return $fail($attribute . ' كتاب غير موجود.');
                        }

                    }
                ],
                'semester_count.*'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        foreach ($value as $key => $itemValues){
                            if($key>1){
                                return $fail($attribute . ' يرجى عدم ادخال قيم لغير المكفول والمتطوع .');
                            }
//                            dd($semestersValues);
                            foreach ($itemValues as $itemKey => $itemValue) {
                                if (!$itemValue) {
                                    return $fail($attribute . ' يرجى ادخال قيمة حقيقية .');
                                }
                            }
                        }
                    }
                ],
                'month_count'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        $ids = array_keys($value);
                        $book_ids_count = Book::whereIn('id', $ids)->where('department',1)->count();
                        if ($book_ids_count != count($ids)) {
                            return $fail($attribute . ' كتاب غير موجود.');
                        }

                    }
                ],
                'month_count.*'=>['required','array',
                    function($attribute, $value, $fail) {
//                        dd($value);
                        foreach ($value as $key => $itemValues){
                            if($key>1){
                                return $fail($attribute . ' يرجى عدم ادخال قيم لغير المكفول والمتطوع .');
                            }
//                            dd($semestersValues);
                            foreach ($itemValues as $itemKey => $itemValue) {
                                if (!$itemValue) {
                                    return $fail($attribute . ' يرجى ادخال قيمة حقيقية .');
                                }
                            }
                        }
                    }
                ],
            ];
        }elseif($this->department == 2){
            $books_count = Book::where('department',2)->count();
            $main_area_count = Area::whereNull('area_id')->count();
            return [
                'sub_area_value'=>'required|validateKey|array|size:'.$main_area_count,
                'sub_area_value.*'=>'required|array',
                'sub_area_value.*.*'=>'required|array|size:'.$books_count,
                'year'=>'required',
            ];
        }else{
            return [
                'department'=>'in:1,2'
            ];
        }
    }
}
