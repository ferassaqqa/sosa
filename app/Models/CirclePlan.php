<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CirclePlan extends Model
{
    use HasFactory;
    protected $fillable = ['year','book_id','guaranteed_yearly','guaranteed_semesterly','guaranteed_monthly','volunteer_yearly','volunteer_semesterly','volunteer_monthly'];

    public function getYearsArrayAttribute(){
        return $this->years ? json_decode($this->years) : [];
    }
    public function getYearsStringAttribute(){
//        dd(55);
        $studentCatStr = '';
        foreach ($this->years_array as $key => $value){
            if(!$key){
                $studentCatStr .= $value;
            }else{
                $studentCatStr .= ' - '.$value;
            }
        }
        return $studentCatStr;
    }
    public function setYearsAttribute($value){
        $this->attributes['years'] = json_encode($value);
    }
    public function getPlanDataForTableAttribute(){
//        dd(5555);
        return
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="guaranteed_yearly" value="'.$this->guaranteed_yearly.'"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="guaranteed_semesterly" value="'.$this->guaranteed_semesterly.'"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="guaranteed_monthly" value="'.$this->guaranteed_monthly.'"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="volunteer_yearly" value="'.$this->volunteer_yearly.'"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="volunteer_semesterly" value="'.$this->volunteer_semesterly.'"></td>'.
            '<td><input type="number" min="0" step="1" style="direction:rtl" class="form-control BookPlanData" name="volunteer_monthly" value="'.$this->volunteer_monthly.'"></td>';
    }
    public function searchScope($query,$search){
        return $query->where('year',$search);
    }
}
