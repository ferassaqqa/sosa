<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPlanHour extends Model
{
    use HasFactory;
    protected $fillable = ['from','to','hours','book_plan_id'];

    public function plan(){
        return $this->belongsTo(BookPlan::class,'book_plan_id');
    }
    public function getPlanNameAttribute(){
        return $this->plan ? $this->plan->name : new Book();
    }
    public function getBookAttribute(){
        return $this->plan ? $this->plan->book : new Book();
    }
    public function getToolsAttribute(){
        return '
                <button type="button" class="btn btn-warning btn-sm" data-url="'.route('planHours.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,\'restore_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-url="'.route('planHours.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
               ';
    }
    public function getRowAttribute(){
        return '<tr>
                    <td>'.$this->from.'</td>
                    <td>'.$this->to.'</td>
                    <td>'.$this->hours.'</td>
                    <td>'.$this->tools.'</td>
                </tr>';
    }
}
