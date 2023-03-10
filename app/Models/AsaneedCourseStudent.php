<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


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
            return $query->whereHas('asaneedCourse', function ($query) use ($sub_area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                    $query->where('area_id', $sub_area_id);
                });
            });
        }else if($area_id){
            return $query->whereHas('asaneedCourse', function ($query) use ($area_id) {
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
        if(60<=$mark && $mark<70){ return '<span style="color:#b3b300">??????????</span>'; }
        elseif(70<=$mark && $mark<75){ return '<span style="color:lawngreen">??????</span>'; }
        elseif(75<=$mark && $mark<80){ return '<span style="color:lightgreen">?????? ??????????</span>'; }
        elseif(80<=$mark && $mark<85){ return '<span style="color:forestgreen">?????? ??????</span>'; }
        elseif(85<=$mark && $mark<90){ return '<span style="color:green">?????? ?????? ??????????</span>'; }
        elseif(90<=$mark && $mark<=100){ return '<span style="color:darkgreen">??????????</span>'; }
        else{
            return '<span style="color:red">???? ????????</span>';
        }

    }

    public function placeForPermissions(){
        return $this->belongsTo(Place::class,'place_id','id')->withoutGlobalScope('relatedPlaces');
    }

    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
//        dd($sub_area_id,$area_id);
        if($area_id){
            return $query->whereHas('user', function ($query) use ($area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                    $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                        $query->where('area_id', $area_id);
                    });
                });
            })
            ->orWhereHas('asaneedCourse',function($query) use ($area_id){
                    $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                        $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                            $query->where('area_id', $area_id);
                        });
                    });
            });
        }else if ($sub_area_id) {
            return $query
                        ->whereHas('user', function ($query) use ($sub_area_id) {
                            $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                                $query->where('area_id', $sub_area_id);
                            });
                        })
                            ->orWhereHas('asaneedCourse',function($query) use ($sub_area_id){
                                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                                    $query->where('area_id', $sub_area_id);
                                });
                            });
        } else{
            return $query;
        }
    }

    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();

            static::addGlobalScope('relatedAsannedStudents', function (Builder $builder) use ($user) {

                $builder->whereHas('asaneedCourse');
                $builder->whereHas('user');

                // if ($user) {
                //     if ($user->hasRole('???????? ??????????????')) {
                //         return $builder;
                //     } else if ($user->hasRole('???????? ??????????????') || $user->hasRole('?????????? ??????????')) {
                //         return $builder;
                //     } else if ($user->hasRole('???????? ??????')) {
                //         return $builder->permissionssubarea(0, $user->area_supervisor_area_id);
                //     } else if ($user->hasRole('???????? ????????????')) {
                //         return $builder->permissionssubarea($user->sub_area_supervisor_area_id, 0);
                //     } else if ($user->hasRole('????????') || $user->hasRole('????????') || $user->hasRole('?????? ??????????')) {
                //         $builder->whereHas('user',function($query) use($user){
                //             $query->where('teacher_id', $user->id)->orWhere('id', $user->id);
                //         });
                //     }else if($user->hasRole('???????? ?????????? ?????????????? ??????????????')){
                //         return $builder;
                //     }else if($user->hasRole('???????? ?????? ????????????????????')){
                //         return $builder;
                //     }else if ($user->hasRole('???????? ??????')) {
                //         return $builder->permissionssubarea(0, $user->branch_supervisor_area_id);
                //     }

                //     else {
                //         $builder->where('id', $user->id);
                //     }
                // }
            });
        }
    }










}
