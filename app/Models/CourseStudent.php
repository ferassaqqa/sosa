<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CourseStudent extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['user_id','course_id','mark'];
    protected $appends = ['estimation'];
    public function getEstimationAttribute(){
        $mark = $this->mark;
        if(60<=$mark && $mark<70){ return '<span style="color:#b3b300">متوسط</span>'; }
        elseif(70<=$mark && $mark<75){ return '<span style="color:lawngreen">جيد</span>'; }
        elseif(75<=$mark && $mark<80){ return '<span style="color:lightgreen">جيد مرتفع</span>'; }
        elseif(80<=$mark && $mark<85){ return '<span style="color:forestgreen">جيد جداً</span>'; }
        elseif(85<=$mark && $mark<90){ return '<span style="color:green">جيد جداً مرتفع</span>'; }
        elseif(90<=$mark && $mark<=100){ return '<span style="color:darkgreen">ممتاز</span>'; }
        else{
            return '<span style="color:red">لا يجاز</span>';
        }
//        switch ($mark){
//            case (60<=$mark && $mark<70) : { return '<span style="color:#b3b300">ضعيف</span>'; }break;
//            case (70<=$mark && $mark<75) : { return '<span style="color:lawngreen">جيد</span>'; }break;
//            case (75<=$mark && $mark<80) : { return '<span style="color:lightgreen">جيد مرتفع</span>'; }break;
//            case (80<=$mark && $mark<85) : { return '<span style="color:forestgreen">جيد جدا</span>'; }break;
//            case (85<=$mark && $mark<90) : { return '<span style="color:green">جيد جدا مرتفع</span>'; }break;
//            case (90<=$mark && $mark<=100) : { return '<span style="color:darkgreen">ممتاز</span>'; }break;
//            default : return '<span style="color:red">لا يجاز</span>';
//        }
    }
    public function course(){
        return $this->belongsTo(Course::class);
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
    public function getCourseNameAttribute(){
        return $this->course ? $this->course->name : '';
    }
    public function getBookNameAttribute(){
//        dd($this->teacher);
        return $this->course ?
            $this->course->book_name : '';
    }
    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'user_id': {return 'اسم اطالب رباعياّ';}break;
            case 'course_id': {return 'الدورة';}break;
            case 'mark': {return 'الدرجة';}break;
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id': {return $this->id;}break;
            case 'user_id': {return $this->user_name;}break;
            case 'course_id': {return $this->course_name;}break;
            case 'mark': {return $this->mark;}break;
        }
    }
    protected static $logAttributes = ['id','user_id','course_id','mark'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة الطالب '.$this->user_name .' للدورة ' . $this->book_name;
            }break;
            case 'updated':{
//                $description = ' قام '.$activity->causer->name.' بتعديل دورة المعلم '.$activity->user_name .' للدورة ' . $this->course_name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف الطالب '.$this->user_name .'  من دورة ' . $this->book_name;
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
    public function scopeCourse($query,$status)
    {
        if($status) {
            return $query->whereHas('course',function($query) use ($status){
                $query->where('status',$status);
            });
        }else{
            return $query;
        }
    }
    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if ($sub_area_id) {
            return $query->whereHas('course', function ($query) use ($sub_area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                    $query->where('area_id', $sub_area_id);
                });
            });
        }else if($area_id){
            return $query->whereHas('course', function ($query) use ($area_id) {
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

    public function scopeGenderDepartment($query,$role){
        return $query->whereHas('user', function ($query) use ($role) {
            return $query->where('role', $role);
        });

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
                        $builder->genderdepartment($user->role);
                    } else if ($user->hasRole('مشرف عام')) {
                        $builder->genderdepartment($user->role)->permissionssubarea(0, $user->area_supervisor_area_id)
                            ->orWhereHas('user',function($query) use($user){
                                $query->whereHas('user_roles',function($query){
                                    $query->where('name','مشرف جودة');
                                });
                            });
                    } else if ($user->hasRole('مشرف ميداني')) {
                        $builder->genderdepartment($user->role)->permissionssubarea($user->sub_area_supervisor_area_id, 0)
                            ->orWhereHas('user',function($query) use($user){
                                $query->whereHas('user_roles',function($query){
                                    $query->where('name','مشرف جودة');
                                });
                            });
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
