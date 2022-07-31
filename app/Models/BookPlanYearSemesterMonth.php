<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookPlanYearSemesterMonth extends Model
{
    use HasFactory;
    protected $fillable = ['semester_month','hadith_count','from_hadith','to_hadith','book_plan_year_semester_id'];

    public function getRowAttribute(){
        return '<tr>
                    <td>'.$this->semester_month.'</td>
                    <td>'.$this->hadith_count.'</td>
                    <td>'.$this->from_hadith.'</td>
                    <td>'.$this->to_hadith.'</td>
                    <td>'.$this->tools.'</td>
                </tr>';
    }
    public function getToolsAttribute(){
        return '
                <button type="button" class="btn btn-warning btn-sm" data-url="'.route('planMonths.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-url="'.route('planMonths.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
               ';
    }
    public function semester(){
        return $this->belongsTo(BookPlanYearSemester::class,'book_plan_year_semester_id');
    }
    public function getYearAttribute(){
        return $this->semester ? $this->semester->year : new BookPlanYear();
    }
    public function getBookAttribute(){
        return $this->year ? $this->year->book : new Book();
    }
    public function getPlanNameAttribute(){
        return $this->semester ? $this->semester->plan_name : '';
    }
}
