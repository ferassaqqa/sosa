<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CircleStudent extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['user_id','circle_id','teacher_id'];
    // protected $appends = ['estimation'];

    public function circle(){
        return $this->belongsTo(Circle::class);
    }
    public function user(){
        return $this->belongsTo(User::class)->withoutGlobalScope('relatedUsers');
    }
    public function getUserNameAttribute(){
        return $this->user ? $this->user->name : '';
    }
    public function getUserDobAttribute(){
        return $this->user ? $this->user->dob : '';
    }
    public function getUserPobAttribute(){
        return $this->user ? $this->user->pob : '';
    }
    public function getUserIdNumAttribute(){
        return $this->user ? $this->user->id_num : '';
    }
 
    /**
     * Start activities functions
     */


    protected static $logAttributes = ['id','user_id','circle_id','teacher_id'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة الطالب '.$this->user_name .' للحلقة ' . $this->book_name;
            }break;
            case 'updated':{
//                $description = ' قام '.$activity->causer->name.' بتعديل دورة المعلم '.$activity->user_name .' للدورة ' . $this->course_name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف الطالب '.$this->user_name .'  من حلقة ' . $this->book_name;
            }break;
        }
        $activity->description = $description;
        $activity->log_name = $log_name;
        $activity->save();
    }
    public function CreatedBy(){
        return $this->morphMany(Activity::class,'subject');
    }
    public function getCreatedAtUserAttribute(){
        return $this->CreatedBy ? $this->CreatedBy->causer : 0 ;
    }

    /**
     * End activities functions
     */

    /**
     * permissions scopes
     */
    public function scopeCircle($query,$status)
    {
        if($status) {
            return $query->whereHas('circle',function($query) use ($status){
                $query->where('status',$status);
            });
        }else{
            return $query;
        }
    }

    public function scopeExportStatus($query,$export_status)
    {
        if ($export_status) {
            $export_status = ($export_status == 2)?0: $export_status;
            return $query->whereHas('circle',function($query) use ($export_status){
                return $query->where('is_certifications_exported',$export_status);
            });
        }else{
            return $query;
        }
    }


    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if ($sub_area_id) {
            return $query->whereHas('circle', function ($query) use ($sub_area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                    $query->where('area_id', $sub_area_id);
                });
            });
        }else if($area_id){
            return $query->whereHas('circle', function ($query) use ($area_id) {
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

    // public function scopeGenderDepartment($query,$role){
    //     return $query->whereHas('user', function ($query) use ($role) {
    //         return $query->where('role', $role);
    //     });

    // }

    public function scopeBook($query,$book_id){
        if ($book_id) {
        $query =
        $query->from('circle_students')
            ->select('circle_students.user_id')
            ->whereIn('course_students.course_id', function ($query)use($book_id){
                $query->from('courses')
                    ->select('courses.id')
                    ->where('courses.book_id', $book_id);
            });
        }
        return $query;
    }

    public function scopeCourseBookOrTeacher($query,$teacher_id,$book_id,$place_id)
    {

        if(!$teacher_id && !$book_id && !$place_id){
            // return $query;
        }else {
            if ($teacher_id && !$book_id && !$place_id) {

               $query =
                    $query->from('course_students')
                        ->select('course_students.user_id','mark')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id){
                            $query->from('courses')
                            ->select('courses.id')
                            ->where('courses.teacher_id', $teacher_id);
                        });


            } else if ($teacher_id && $book_id && !$place_id){
                $query =
                    $query->from('course_students')
                        ->select('course_students.user_id','mark')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id,$book_id){
                            $query->from('courses')
                                ->select('courses.id')
                                ->where('courses.teacher_id', $teacher_id)
                                ->where('courses.book_id', $book_id);
                        });


            } else if ($teacher_id && $book_id && $place_id){

                $query =
                    $query->from('course_students')
                        ->select('course_students.user_id','mark')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id,$book_id,$place_id){
                            $query->from('courses')
                                ->select('courses.id')
                                ->where('courses.teacher_id', $teacher_id)
                                ->where('courses.place_id', $place_id)
                                ->where('courses.book_id', $book_id);
                        });

            }
        }


        return $query;

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
            ->orWhereHas('course',function($query) use ($area_id){
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
                            ->orWhereHas('course',function($query) use ($sub_area_id){
                                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                                    $query->where('area_id', $sub_area_id);
                                });
                            });
        } else{
            return $query;
        }
    }
    /**
     * End permissions Scopes
     */

    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();
            if($_SERVER['REMOTE_ADDR'] == "82.205.28.65"){
//                dd($user);
            }
            static::addGlobalScope('relatedCourseStudents', function (Builder $builder) use ($user) {
                if ($user) {
                    if ($user->hasRole('رئيس الدائرة')) {
                        return $builder;
                    } else if ($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')) {
                        return $builder;
                    } else if ($user->hasRole('مشرف عام')) {
                        return $builder->permissionssubarea(0, $user->area_supervisor_area_id);
                    } else if ($user->hasRole('مشرف ميداني')) {
                        return $builder->permissionssubarea($user->sub_area_supervisor_area_id, 0);
                    } else if ($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')) {
                        $builder->whereHas('user',function($query) use($user){
                            $query->where('teacher_id', $user->id)->orWhere('id', $user->id);
                        });
                    }else if($user->hasRole('مدير دائرة التخطيط والجودة')){
                        return $builder;
                    }else if($user->hasRole('رئيس قسم الاختبارات')){
                        return $builder;
                    } else {
                        $builder->where('id', $user->id);
                    }
                } else {

                }
            });
        }
    }

}
