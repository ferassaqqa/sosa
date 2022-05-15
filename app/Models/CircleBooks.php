<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CircleBooks extends Model
{
    use HasFactory;
    public static $counter=0;
    protected $fillable = ['name','pass_mark','hadith_count','book_code','location'];
    public function circles(){
        return $this->hasMany(Circle::class);
    }
    public function getBookDisplayDataAttribute(){
        self::$counter++;
        return [
            'DT_RowId'=>$this->id,
            'id_col'=>self::$counter,
            'name'=>$this->name,
            'pass_mark'=>$this->pass_mark,
            'hadith_count'=>$this->hadith_count,
            'book_code'=>$this->book_code,
            'tools'=>'
                <button type="button" class="btn btn-warning" title="تعديل بيانات الكتاب" data-url="'.route('circleBooks.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger" title="حذف بيانات الكتاب" data-url="'.route('circleBooks.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
        ];
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%")
            ->orWhere('pass_mark', 'like', "%" . $searchWord . "%")
            ->orWhere('hadith_count', 'like', "%" . $searchWord . "%")
            ->orWhere('book_code', 'like', "%" . $searchWord . "%");
    }

    /**
     * End Scopes
     */
    public function plans(){
        return $this->hasMany(CirclePlan::class,'book_id');
    }
    public function bookPlanDataForTable($year){
        $emptyPlan = '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="guaranteed_yearly"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="guaranteed_semesterly"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="guaranteed_monthly"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="volunteer_yearly"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="volunteer_semesterly"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl;" class="form-control BookPlanData" name="volunteer_monthly"></td>';
        $plan = $this->plans->count() ? $this->plans->where('year',$year)->first() : null;
        if($plan){
            return $plan->plan_data_for_table;
        }else{
            return $emptyPlan;
        }
    }


}
