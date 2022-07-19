<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedCourseStudent extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','asaneed_course_id','mark'];


    public function asaneedCourse(){
        return $this->belongsTo(AsaneedCourse::class);
    }


    public function scopeBook($query,$book_id){
        if ($book_id) {
        $query =
        $query->from('asaneed_course_students')
            ->select('asaneed_course_students.user_id','mark')
            ->whereIn('asaneed_course_students.asaneed_course_id', function ($query)use($book_id){
                $query->from('asaneed_courses')
                    ->select('asaneed_courses.id')
                    ->where('asaneed_courses.book_id', $book_id);
            });
        }
        return $query;
    }

    public function scopeTeacher($query,$teacher_id){
        if ($teacher_id) {
        $query =
        $query->from('asaneed_course_students')
            ->select('asaneed_course_students.user_id','mark')
            ->whereIn('asaneed_course_students.asaneed_course_id', function ($query)use($teacher_id){
                $query->from('asaneed_courses')
                    ->select('asaneed_courses.id')
                    ->where('asaneed_courses.teacher_id', $teacher_id);
            });
        }
        return $query;
    }

    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if ($sub_area_id) {
            return $query->whereHas('asaneed_courses', function ($query) use ($sub_area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                    $query->where('area_id', $sub_area_id);
                });
            });
        }else if($area_id){
            return $query->whereHas('asaneed_courses', function ($query) use ($area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                    $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                        $query->where('area_id', $area_id);
                    });
                });
            });
        } else{
            return $query;
        }
    }

    public function user(){
        return $this->belongsTo(User::class)->withoutGlobalScope('relatedUsers');
    }


    public function getEstimationAttribute(){
        $mark = $this->mark;
        if(60<=$mark && $mark<70){ return '<span style="color:#b3b300">ضعيف</span>'; }
        elseif(70<=$mark && $mark<75){ return '<span style="color:lawngreen">جيد</span>'; }
        elseif(75<=$mark && $mark<80){ return '<span style="color:lightgreen">جيد مرتفع</span>'; }
        elseif(80<=$mark && $mark<85){ return '<span style="color:forestgreen">جيد جدا</span>'; }
        elseif(85<=$mark && $mark<90){ return '<span style="color:green">جيد جدا مرتفع</span>'; }
        elseif(90<=$mark && $mark<=100){ return '<span style="color:darkgreen">ممتاز</span>'; }
        else{
            return '<span style="color:red">لا يجاز</span>';
        }

    }










}
