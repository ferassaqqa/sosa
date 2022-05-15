<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookPlan extends Model
{
    use HasFactory;
    public $department;
    protected $fillable = ['year','type','book_id'];

    public function book(){
        return $this->belongsTo(Book::class);
    }

    public function getBookNameAttribute(){
        return $this->book ? $this->book->name : '';
    }

    public function setDepartmentValue($department){
        $this->department = $department;
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('year', 'like', "%" . $searchWord . "%")
            ->orWhere('type', 'like', "%" . $searchWord . "%");
    }
    public function scopeDepartment($query,$department)
    {
        switch ($department){
            case 0 : {return $query;}break;
            case 1 : {return $query->whereHas('book',function($query) use($department){
                $query->department($department);
            });}break;
            case 2 : {return $query->whereHas('book',function($query) use($department){
                $query->department($department);
            });}break;
        }
    }
    /**
     * Scopes
     */
    public function years(){
        return $this->hasMany(BookPlanYear::class);
    }

    public function getPlanDisplayDataAttribute(){
        return [
            'id'=>$this->id,
            'year'=>$this->year,
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.route('plans.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.route('plans.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select'=>'
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="'.$this->id.'">
                        </div>'
        ];
    }
    public function getSearchedPlanDisplayDataAttribute(){
        return [
            'id'=>$this->id,
            'year'=>'<span style="background-color: #2ca02c;color: #fff;">'.$this->year.'</span>',
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.route('plans.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.route('plans.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select'=>'
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="'.$this->id.'">
                        </div>'
        ];
    }

//    public function hours(){
//        return $this->hasMany(BookPlanHour::class);
//    }
    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();
        static::deleting(function($bookPlan) {
            $bookPlan->years()->delete();
//            $bookPlan->hours()->delete();
        });
    }
}
