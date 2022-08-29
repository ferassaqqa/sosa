<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Exam extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['examable_type','examable_id','place_id','notes','appointment','date','time','quality_supervisor_id','status'];
    public static $counter;
    protected function getExamTypeAttribute()
    {
        if($this->examable_type == 'App\Models\Course'){
            return 'دورات علمية';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return 'مجالس أسانيد';
        }else{
            return 'حلقات تحفيظ';
        }
    }
    public function examable(){
        return $this->morphTo()->withoutGlobalScope('relatedCourses');
    }


    public function getCourseAttribute(){
        if($this->examable_type == 'App\Models\Course'){
            return $this->examable;
        }
    }


    public function getExportStatusAttribute(){
        if($this->examable_type == 'App\Models\Course'){
            return $this->course->is_certifications_exported;
        }
        if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed->is_certifications_exported;
        }
    }


    public function getCourseStatusAttribute(){
        if($this->examable_type == 'App\Models\Course'){
            return $this->course->status;
        }
        if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed->status;
        }
    }

    public function getAsaneedAttribute(){
        if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->examable;
        }
    }


    public function getCourseNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->name : '';
        }elseif($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->teacher_name : '';
        }

    }
    public function getCourseBookNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->book_name : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->book_name : '';
        }

    }
    public function getCoursePlaceNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->place_name : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->place->name : '';
        }

    }
    public function getCourseStartDateAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? GetFormatedDate($this->course->start_date) : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?GetFormatedDate($this->asaneed->start_date) : '';
        }

    }
    public function getSubAreaSupervisorNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->sub_area_supervisor_name : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->sub_area_supervisor_name : '';
        }

    }
    public function getTeacherMobileAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->teacher_mobile : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->teacher_mobile : '';
        }

    }
    public function placeForPermissions(){
        return $this->belongsTo(Place::class,'place_id','id')->withoutGlobalScope('relatedExams');
    }
    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function getPlaceNameAttribute(){
        return $this->place ? $this->place->name : '';
    }
    public function getCourseAreaFatherNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->area_father_name : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->area_father_name : '';
        }

    }
    public function getCourseAreaNameAttribute(){

        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? $this->course->area_name : '';
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->area_name : '';
        }
    }
    public function getStudentsCountAttribute(){


        if($this->examable_type == 'App\Models\Course'){
            return $this->course ? ( $this->course->studentsForPermissions->count() ? $this->course->studentsForPermissions->count() : 0) : 0;
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?$this->asaneed->students->count() : 0;
        }

    }
    public function getPassedStudentsCountAttribute(){



                if($this->examable_type == 'App\Models\Course'){
                    return $this->course ?
                    ( $this->course->manyStudentsForPermissions->where('mark','>=',60)->count()
                        ? $this->course->manyStudentsForPermissions->where('mark','>=',60)->count() : 0) : 0;
                }else if($this->examable_type == 'App\Models\AsaneedCourse'){
                    return $this->asaneed ?
                    ( $this->asaneed->manyStudentsForPermissions->where('mark','>=',60)->count()
                        ? $this->asaneed->manyStudentsForPermissions->where('mark','>=',60)->count() : 0) : 0;
                }

    }

    public function getFailsStudentsCountAttribute(){



        if($this->examable_type == 'App\Models\Course'){
            return $this->course ?
            ( $this->course->manyStudentsForPermissions->whereBetween('mark', [1, 59])->count()
                ? $this->course->manyStudentsForPermissions->whereBetween('mark', [1, 59])->count() : 0) : 0;
        }else if($this->examable_type == 'App\Models\AsaneedCourse'){
            return $this->asaneed ?
            ( $this->asaneed->manyStudentsForPermissions->whereBetween('mark', [1, 59])->count()
                ? $this->asaneed->manyStudentsForPermissions->whereBetween('mark', [1, 59])->count() : 0) : 0;
        }

}

public function getNullStudentsCountAttribute(){



    if($this->examable_type == 'App\Models\Course'){
        return $this->course ?
        ( $this->course->manyStudentsForPermissions->whereNull('mark')->count()
            ? $this->course->manyStudentsForPermissions->whereNull('mark')->count() : 0) : 0;
    }else if($this->examable_type == 'App\Models\AsaneedCourse'){
        return $this->asaneed ?
        ( $this->asaneed->manyStudentsForPermissions->whereNull('mark')->count()
            ? $this->asaneed->manyStudentsForPermissions->whereNull('mark')->count() : 0) : 0;
    }

}
    public function getRowAttribute(){
        $approveButton = hasPermissionHelper('تأكيد طلبات الحجز') ? '<button class="btn btn-success" onclick="approveExamAppointment(this,'.$this->id .')"><i class="mdi mdi-table"></i></button>' : '';
        $removeButton = hasPermissionHelper('حذف طلبات مواعيد الاختبارات') ? '<button class="btn btn-danger" onclick="deleteExamAppointment(this,'.$this->id .')"><i class="mdi mdi-close"></i></button>' : '';
        return '
                    <td>'. $this->course_book_name .'</td>
                    <td>'. $this->course_name  .'</td>
                    <td>'. $this->students_count  .'</td>
                    <td>'. $this->course_place_name  .'</td>
                    <td>'. $this->place_name  .'</td>
                    <td>'. $this->course_area_name  .'</td>
                    <td>'. $this->notes  .'</td>
                    <td style="width: 113px;min-width: 113px;">
                        '.$approveButton.'&nbsp'.$removeButton.'
                    </td>
                ';
    }
    public function getNextExamRowAttribute(){
        App::setLocale('ar');
        return '
                    <td>'. $this->course_book_name .'</td>
                    <td>'. $this->students_count  .'</td>
                    <td>'. $this->course_name  .'</td>
                    <td>'. $this->teacher_mobile  .'</td>
                    <td>'. $this->course_area_father_name  .'</td>
                    <td>'. $this->course_area_name  .'</td>
                    <td>'. $this->place_name  .'</td>
                    <td>'. $this->quality_supervisors_string  .'</td>
                    <td>'. $this->date . ' || '. Carbon::parse($this->time)->isoFormat('h:mm a')  .'</td>
                    <td>'. $this->tools_for_next_exam_row  .'</td>
                ';
    }
    public function getExamArchiveRowAttribute(){
        $exportExamButton = hasPermissionHelper('استخراج كشف درجات معتمد')
            ? '<a class="btn btn-success" target="_blank" href="'.route('exportExam',$this->id).'">استخراج كشف درجات</a>'
            : '';
        App::setLocale('ar');
        return '
                    <td>'. $this->course_book_name .'</td>
                    <td>'. $this->students_count  .'</td>
                    <td>'. $this->passed_students_count  .'</td>
                    <td>'. $this->course_name  .'</td>
                    <td>'. $this->teacher_mobile  .'</td>
                    <td>'. $this->course_area_father_name  .'</td>
                    <td>'. $this->course_area_name  .'</td>
                    <td>'. $this->place_name  .'</td>
                    <td>'. $this->quality_supervisors_string  .'</td>
                    <td>'. $this->date . ' || '. Carbon::parse($this->time)->isoFormat('h:mm a')  .'</td>
                    <td>'.$exportExamButton.'</td>
                </tr>';
    }
    public function getToolsForNextExamRowAttribute(){

//        return Carbon::createFromFormat('Y-m-d',$this->date);
//        dd(Carbon::createFromFormat('Y-m-d',$this->date),Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d')));
//        dd(Carbon::createFromFormat('Y-m-d',$this->date)->gte(Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d'))));
        if(Carbon::createFromFormat('Y-m-d',$this->date)->gte(Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d')))){
            return hasPermissionHelper('تعديل طلبات الحجز') ? '<button class="btn btn-success" onclick="approveExamAppointment(this,'.$this->id .')"><i class="mdi mdi-table"></i></button>' : '';
        }elseif(Carbon::createFromFormat('Y-m-d',$this->date)->lt(Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d')))){
            return hasPermissionHelper('حذف طلبات مواعيد الاختبارات') ? '<button class="btn btn-danger" onclick="deleteExamAppointment(this,'.$this->id .')"><i class="mdi mdi-close"></i></button>': '';
        }
    }
    public function getEligibleCoursesForMarkEnterAttribute(){
        $enterExamMarksButton = hasPermissionHelper('انهاء الدورة و ادخال الدرجات') ?
            '<button class="btn btn-success" onclick="enterExamMarks('.$this->id .')">انهاء الدورة</button>'
            : '';
        return '
                    <td>'. $this->course_book_name  .'</td>
                    <td>'. $this->course_name  .'</td>
                    <td>'. $this->course_area_father_name  .'</td>
                    <td>'. $this->course_area_name  .'</td>
                    <td>'. $this->course_place_name  .'</td>
                    <td>'. $this->students_count  .'</td>
                    <td>'. $this->course_start_date  .'</td>
                    <td style="padding: 3px;">
                        '.$enterExamMarksButton.'
                    </td>
                </tr>';
    }
    public function getEligibleCoursesForMarksApprovementAttribute(){
        $examMarksApprovement = hasPermissionHelper('اعتماد نتائج الاختبارات') ?
            '<button class="btn btn-success" onclick="approveEnteredExamMarks('.$this->id .')">اعتماد الدرجات</button>'
            :'';
        return '
                    <td>'. $this->course_book_name  .'</td>
                    <td>'. $this->course_name  .'</td>
                    <td>'. $this->course_area_father_name  .'</td>
                    <td>'. $this->course_area_name  .'</td>
                    <td>'. $this->course_place_name  .'</td>
                    <td>'. $this->students_count  .'</td>
                    <td>'. $this->course_start_date  .'</td>
                    <td style="padding: 3px;">
                        '.$examMarksApprovement.'
                    </td>
                </tr>';
    }

    public function getQualitySupervisorsArrayAttribute(){
//        dd((array)json_decode($this->quality_supervisor_id));
        return $this->quality_supervisor_id ? (array)json_decode($this->quality_supervisor_id) : [];
    }
    public function getQualitySupervisorsStringAttribute(){

        $QualitySupervisorsStr = '';
        foreach ($this->quality_supervisors_array as $key => $value){
            $user = User::withoutGlobalScope('relatedUsers')->find($value);
            if(!$key){
                $QualitySupervisorsStr .= $user ? '<span style="color: #2ca02c;">'.$user->name.'</span><br>' : '';
            }else{
                $QualitySupervisorsStr .= ' - '.($user ? '<span style="color: #2ca02c;">'.$user->name.'</span><br>' : '');
            }
        }
        return $QualitySupervisorsStr;
    }
    public function setQualitySupervisorIdAttribute($value){
        $this->attributes['quality_supervisor_id'] = json_encode($value);
    }
    public function hasAppointment($date,$supervisor_id){
//        dd($supervisor_id,$this->quality_supervisors_array);
        if($date == $this->date && in_array($supervisor_id,$this->quality_supervisors_array)){
            App::setLocale('ar');
            return '<span style="color:green;">'.Carbon::parse($this->time)->isoFormat('h:mm a').'</span>';
        }
    }

    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();

            $sub_area = Area::where('sub_area_supervisor_id',$user->id)->first();
            $sub_area_supervisor_area_id = $sub_area ? $sub_area->id : 0;
            $sub_area_supervisor_area_father_id = $sub_area ? $sub_area->area_id : 0;
            $father_area = Area::where('area_supervisor_id',$user->id)->first();
            $area_supervisor_area_id = $father_area ? $father_area->id : 0;
//            dd($sub_area_supervisor_area_id,$area_supervisor_area_id,);
            static::addGlobalScope('relatedExams', function (Builder $builder) use ($user,$sub_area_supervisor_area_father_id,$area_supervisor_area_id,$sub_area_supervisor_area_id) {
                if ($user) {
//                    dd($user->roles);
                    if ($user->hasRole('رئيس الدائرة')){
                        return $builder;
                    } else if($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')){
                        return $builder;
                    }else if($user->hasRole('مشرف عام')){
                        $builder->genderdepartment($user->role)->permissionssubarea(0,$area_supervisor_area_id);
                    }else if($user->hasRole('مدير دائرة التخطيط والجودة')){
                        return $builder;
                    }else if($user->hasRole('رئيس قسم الاختبارات')){
                        return $builder;
                    }else if($user->hasRole('مشرف ميداني')){
                        $builder->genderdepartment($user->role)->permissionssubarea($sub_area_supervisor_area_id,0);
                    }else if($user->hasRole('مشرف جودة')) {
//                        dd(897);
                        return $builder->qualityincluded($user->id);
                    }else if($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')){
                        $builder->whereHas('examable',function ($query) use($user){
                            $query->where('teacher_id',$user->id)->count();
                        });
                    }else if($user->hasRole('مدير فرع')){
                        return $builder->permissionssubarea(0, $user->branch_supervisor_area_id);
                    }
                    else{
//                        $builder->whereHas('teacher',function ($query) use($user){
//                            $query->whereHas('students',function($query1) use($user){
//                                $query1->where('id',$user->id);
//                            });
//                        });
                    }
                }else {
                    return false;
                }
            });
        }
    }

    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'examable_type': {return 'الدورة';}break;
            case 'place_id': {return 'المكان';}break;
            case 'notes': {return 'ملاحظات';}break;
            case 'appointment': {return 'الموعد';}break;
            case 'date': {return 'التاريخ';}break;
            case 'time': {return 'الوقت';}break;
            case 'quality_supervisor_id': {return 'مشرفي الجودة';}break;
            case 'status': {return 'الحالة';}break;
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id': {return $this->id;}break;
            case 'examable_type': {return $this->course_name;}break;
            case 'place_id': {return $this->place_name;}break;
            case 'notes': {return $this->notes;}break;
            case 'appointment': {return $this->appointment;}break;
            case 'date': {return $this->date;}break;
            case 'time': {return $this->time;}break;
            case 'quality_supervisor_id': {return $this->quality_supervisors_string;}break;
            case 'status': {return $this->status;}break;
        }
    }
    protected static $logAttributes = ['id','examable_type','examable_id','place_id','notes','appointment','date','time','quality_supervisor_id','status'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة اختبار للدروة '.$activity->subject->course_book_name;
            }break;
            case 'updated':{
                $description = ' قام '.$activity->causer->name.' بتعديل اختبار الدروة '.$activity->subject->course_book_name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف اختبار الدروة '.$activity->subject->course_book_name;
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
     * Scopes
     */
    public function scopeSearch($query,$word){
        if($word) {
            return $query->whereHas('examable', function ($query) use ($word) {
                return $query->search($word);
            });
        }else{
            return $query;
        }
    }
    public function scopeFromDate($query,$from_date){

        // echo $from_date; exit;
        if($from_date) {
                return $query->whereDate('date','>=',$from_date);
        }else{
            return $query;
        }
    }
    public function scopeToDate($query,$to_date){
        if($to_date) {
                return $query->whereDate('date','<=',$to_date);
        }else{
            return $query;
        }
    }
    public function scopeQualityIncluded($query,$user_id){
//        dd($user_id);
//        $supervisors = $this->quality_supervisors_array;
        return $query;//->whereRaw("JSON_CONTAINS(quality_supervisor_id, '[".$user_id."]' )");
//        $query->whereJsonContains('quality_supervisor_id',[$user_id]);
    }
    public function scopeArea($query,$area_id,$sub_area_id=0){

        return $query->whereHas('examable', function ($query) use ($area_id,$sub_area_id) {
         $query->whereHas('place',function($query) use($area_id,$sub_area_id){
                if($area_id && !$sub_area_id) {
                    return $query->fatherarea($area_id);
                }elseif($sub_area_id){
                    return $query->where('area_id',$sub_area_id);
                }else{
                    return $query;
                }
            });
        });

    }

    public function scopePlaceArea($query,$place_id){

        if($place_id) {
                return $query->where('place_id',$place_id);
            }else{
                return $query;
            }
    }

    public function scopeExamType($query,$exam_type){

        if($exam_type) {
                return $query->where('examable_type',$exam_type);
            }else{
                return $query;
            }
    }

    public function scopeMoallem($query,$moallem_id){
        if($moallem_id) {
            return $query->whereHas('examable', function ($query) use ($moallem_id) {
                return $query->where('teacher_id',$moallem_id);
            });
        }else{
            return $query;
        }
    }
    public function scopeBook($query,$book_id){
        if($book_id) {
            return $query->whereHas('examable', function ($query) use ($book_id) {
                return $query->where('book_id',$book_id);
            });
        }else{
            return $query;
        }
    }
    public function scopeDate($query,$start_date,$end_date){
        if($start_date && $end_date) {
            return $query;

//            return $query->whereHas('examable', function ($query) use ($book_id) {
//                return $query->where('book_id',$book_id);
//            });
        }else{
            return $query;
        }
    }
    public function scopeCourseBook($query,$book_id){
        if($book_id) {
//            dd($this);
//            if ($this->examable_type == 'App\Models\Course') {
                return $query->whereHas('examable', function ($query1) use ($book_id) {
                    return $query1->where('book_id', $book_id);
                });
//            }
        }else{
            return $query;
        }
    }

    public function scopeGenderDepartment($query,$role)
    {
        return $query->whereHas('CreatedBy', function ($query) use ($role) {
            $query->whereHas('causer', function ($query1) use ($role){
                return $query1->where('role',$role);
            });
        });
    }

    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if($area_id){
            return $query->whereHas('examable', function ($query) use ($area_id) {
             $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('area', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        });

        }else if ($sub_area_id){
            return $query->whereHas('examable', function ($query) use ($sub_area_id) {
                $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                       $query->where('area_id', $sub_area_id);
               });
           });
        } else{
            return $query;
        }
    }

    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {

        if($area_id){
            return $query->whereHas('examable', function ($query) use ($area_id) {
             $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        });

        }else if ($sub_area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                $query->where('area_id', $sub_area_id);
            });
        } else{
            return $query;
        }
    }

//     public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
//     {
// //        dd($sub_area_id,$area_id);
//         if($area_id){
//             return $query
// //                ->whereHas('placeForPermissions', function ($query) use ($area_id) {
// //                    $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
// //                        $query->where('area_id', $area_id);
// //                    });
// //                })
//                 ->orWhereHas('examable', function ($query) use ($area_id) {
//                 $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
//                     $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
//                         $query->where('area_id', $area_id);
//                     });
//                 });
//             });
//         }else if ($sub_area_id){
//             return $query
// //                ->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
// //                    $query->where('area_id', $sub_area_id);
// //                })
//                 ->orWhereHas('examable', function ($query) use ($sub_area_id) {
//                     $query->whereHas('place', function ($query) use ($sub_area_id) {
// //                        dd($sub_area_id);
//                         $query->where('area_id', $sub_area_id);
//                     });
//                 });
//         } else{
//             return $query;
//         }
//     }

    /**
     * Scopes
     */
}
