<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookPlanYearSemester extends Model
{
    use HasFactory;
    protected $fillable = ['year_semester','semester_count','month_count','book_plan_year_id'];
    public function months(){
        return $this->hasMany(BookPlanYearSemesterMonth::class);
    }
    public function year(){
        return $this->belongsTo(BookPlanYear::class,'book_plan_year_id');
    }
    public function getBookAttribute(){
        return $this->year ? $this->year->book : new Book();
    }
    public function getPlanNameAttribute(){
        return $this->year ? $this->year->plan_name : new Book();
    }

    public function getToolsAttribute(){
        return '
                <button type="button" class="btn btn-primary btn-sm" data-url="'.route('planMonths.create',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-plus"></i></button>
                <button type="button" class="btn btn-warning btn-sm" data-url="'.route('planSemesters.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-url="'.route('planSemesters.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
               ';
    }
    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($planeSemester) { // before delete() method call this
            $planeSemester->months()->delete();
            // do the rest of the cleanup...
        });
    }
}
