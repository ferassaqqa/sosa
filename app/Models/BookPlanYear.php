<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookPlanYear extends Model
{
    use HasFactory;
    protected $fillable = ['yearly_count','book_plan_id'];
    public function semesters(){
        return $this->hasMany(BookPlanYearSemester::class);
    }
    public function plan(){
        return $this->belongsTo(BookPlan::class,'book_plan_id');
    }
    public function getBookAttribute(){
//        dd($this->plan,$this->id);
        return $this->plan ? $this->plan->book : new Book();
    }
    public function getPlanNameAttribute(){
        return $this->plan ? $this->plan->name : new Book();
    }
    public function getToolsAttribute(){
        return '
                <button type="button" class="btn btn-primary btn-sm" data-url="'.route('planSemesters.create',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-plus"></i></button>
                <button type="button" class="btn btn-warning btn-sm" data-url="'.route('planYears.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-url="'.route('planYears.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
               ';
    }
    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($planYear) { // before delete() method call this
            $planYear->semesters()->delete();
            // do the rest of the cleanup...
        });
    }
}
