<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBookPlan extends Model
{
    use HasFactory;
    public $department;
    protected $fillable = ['year','area_id','book_id','value','percentage'];

    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function getAreaFatherIdAttribute(){
        return $this->area ? $this->area->area_father_id : '';
    }
    public function getBookNameAttribute(){
        return $this->book ? $this->book->name : '';
    }

    public function setDepartmentValue($department){
        $this->department = $department;
    }
    public function getFinishedAreaCoursesAttribute(){
        $area = $this->area ? $this->area->load(['courses'=>function($query){
            $query->where('status','منتهية');
        }]) : '';
        return $area->courses;
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('year', 'like', "%" . $searchWord . "%");
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
}
