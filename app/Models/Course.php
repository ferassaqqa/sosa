<?php

namespace App\Models;

use function Complex\theta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use HasFactory,LogsActivity;
    public static $counter=0;
    protected $fillable = ['start_date','place_id','course_type','included_in_plan','book_id','teacher_id','hours','status','note','is_certifications_exported'];
    public function getCourseDisplayDataAttribute(){
        self::$counter++;
        // $exportStudentsMark = ($this->status == 'منتهية' && $this->exam_status == 5) ?
        //     ($this->is_certifications_exported ?'<button type="button" class="btn btn-outline-secondary" title="دورة منتهية تم طباعة شهاداتها">
        //     <i class="mdi mdi-checkbox-marked-circle-outline" ></i></button>&nbsp': '').'<button type="button" class="btn btn-primary" title="استخراج كشف درجات الدورة اكسل"  onclick="exportCourseStudentsMarksExcelSheet('.$this->id.')"><i class="mdi mdi-microsoft-excel"></i></button>&nbsp': '';

        // I remove $this->exam_status because the course will not work on teacher account make id integrity

        $exportCertificate =
        ( ($this->status == 'منتهية') && $this->is_certifications_exported) ?'<button type="button" class="btn btn-outline-secondary" title="دورة منتهية تم طباعة شهاداتها"><i class="mdi mdi-checkbox-marked-circle-outline" ></i></button>&nbsp': '';

        $export = ($this->status == 'منتهية') ? '<button type="button" class="btn btn-primary" title="استخراج كشف درجات الدورة اكسل"  onclick="exportCourseStudentsMarksExcelSheet('.$this->id.')"><i class="mdi mdi-microsoft-excel"></i></button>&nbsp' : '';


        $addExcelStudent = ($this->status != 'منتهية' && $this->status != 'بانتظار اعتماد الدرجات' && hasPermissionHelper('اضافة طالب جديد - دورات علمية')) ?
            '<button type="button" class="btn btn-primary" title="اضافة طلاب من ملف اكسل"  onclick="addExcelCourseStudents('.$this->id.')"><i class="mdi mdi-microsoft-excel"></i></button>&nbsp' : '';
        $addStudent = ($this->status != 'منتهية' && $this->status != 'بانتظار اعتماد الدرجات' && hasPermissionHelper('اضافة طالب جديد - دورات علمية')) ?
            '<button type="button" class="btn btn-info" title="اضافة طالب"  onclick="createNewCourseStudents('.$this->id.')"><i class="mdi mdi-account-plus"></i></button>&nbsp' : '';
        $viewStudents = (hasPermissionHelper('طلاب الدورات')) ?
            '<button type="button" class="btn btn-success" title="عرض الطلاب" data-url="'.route('courseStudents.ShowCourseStudents',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-account-multiple"></i></button>&nbsp' : '';
        $updateCourse = (hasPermissionHelper('تعديل بيانات الدورات العلمية')) ?
            '<button type="button" class="btn btn-warning" title="تعديل بيانات الدورة" data-url="'.route('courses.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2" onclick="callApi(this,\'user_modal_content_new\')"><i class="mdi mdi-comment-edit"></i></button>&nbsp' : '';
        $alert = "هل أنت متأكد من حذف دورة ".$this->book_name." للمعلم ".$this->teacher_name;
        $deleteCourse = (hasPermissionHelper('حذف بيانات الدورات العلمية')) ?
            '<button type="button" class="btn btn-danger" title="حذف بيانات الدورة" data-url="'.route('courses.destroy',$this->id).'" data-alert="'.$alert.'" onclick="deleteCourse(this)"><i class="mdi mdi-trash-can"></i></button>&nbsp' : '';
       $courseDetails = '<button type="button" class="btn btn-info" title="تفاصيل الدورة" data-url="'.route('courses.details',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2" onclick="callApi(this,\'user_modal_content_new\')"><i class="mdi mdi-account-details"></i></button>';

       $add_reservation_order = (Auth::user()->hasRole('مدير الدائرة') && $this->status != 'منتهية') ?
       '<button type="button" class="btn btn-primary inline" style="width:50%; background-color:#254a70 !important; border-color:#254a70 !important;" title="اضافة طلب حجز موعد اختبار" data-url="'.route('courses.addReservationOrder',$this->id).'" data-alert="" onclick="addReservationOrder(this)"><i class="mdi mdi-account-details"></i></button>&nbsp' : '';


       return [
            'id'=>self::$counter,
            'teacher_name'=>$this->name,
            'book'=>$this->book_name,
            'place'=>$this->area_father_name.' - '.$this->area_name.' <br> '.$this->place_name,

            // 'area_name'=>$this->area_name,
            // 'father_area_name'=>$this->area_father_name,

            // 'area_supervisor'=>$this->area_supervisor_name,
            // 'sub_area_supervisor'=>$this->sub_area_supervisor_name,

            'supervisor' =>'الميداني: '.$this->sub_area_supervisor_name.'<br>'
                          .'العام: '.$this->area_supervisor_name,


            'studentCount'=>$this->studentsForPermissions->count(),
            'status'=>$this->status != 'منتهية' ?
                ($this->status != 'بانتظار اعتماد الدرجات' ? $this->status_select :

                    '<button type="button" class="btn btn-success" title="بانتظار اعتماد الدرجات" data-url="'.route('courseExam.showCourseExamMarks',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">بانتظار اعتماد الدرجات</button>')
                : '<span style="background-color:green;color: white;padding: 5px;border-radius: 8%;">'.$this->status.'</span>',
            'tools'=> '<div class="mb-1">'.$add_reservation_order . '</div> '.
            '<div class="mb-1">'.$exportCertificate.$export.$addExcelStudent.$addStudent.$viewStudents.'</div>'.'
                    <div class="mb-1">'.$updateCourse.$deleteCourse.$courseDetails.'</div>'

        ];
    }
    public function getNameAttribute(){
        return $this->teacher_name;
    }
    public function getStudentsCountAttribute(){
        return $this->students ? $this->students->count() : 0;
    }
    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id')->withoutGlobalScope('relatedUsers');
    }
    public function getTeacherMobileAttribute(){
//        dd($this->teacher);
        return $this->teacher ?
            $this->teacher->mobile : '';
    }
    public function getTeacherNameAttribute(){
//        dd($this->teacher);
        return $this->teacher ?
            $this->teacher->name : '';
    }
    public function getTeacherIdNumAttribute(){
//        dd($this->teacher);
        return $this->teacher ?
            $this->teacher->id_num : '';
    }
    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function getAreaNameAttribute(){
        return $this->place ? $this->place->area_name : '';
    }
    public function getAreaFatherNameAttribute(){
        return $this->place ? $this->place->area_father_name : '';
    }
    public function getAreaIdAttribute(){
        return $this->place ? $this->place->area_id : 0;
    }
    public function getAreaFatherIdAttribute(){
        return $this->place ? $this->place->area_father_id : 0;
    }
    public function getPlaceNameAttribute(){
        return $this->place ? $this->place->name : '';
    }
    public function getPlaceFullNameAttribute(){
        return $this->place ? $this->place->place_full_name : '';
    }

    public function getSupervisorAttribute(){
        return 'الميداني: '.$this->sub_area_supervisor_name.'<br>'.'العام: '.$this->area_supervisor_name;
    }

    public function placeForPermissions(){
        return $this->belongsTo(Place::class,'place_id','id')->withoutGlobalScope('relatedPlaces');
    }
    public function getAreaIdForPermissionsAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->area_id : 0;
    }
    public function getAreaFatherIdForPermissionsAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->area_father_id_for_permissions : 0;
    }
    public function getFatherAreaAbbreviationAttribute(){
        switch($this->placeForPermissions->area_father_name){
            case 'شمال غزة': {return 'ش م غ';}break;
            case 'غرب غزة': {return 'غ غ';}break;
            case 'شرق غزة': {return 'ش ر غ';}break;
            case 'جنوب غزة': {return 'ج غ';}break;
            case 'الوسطى': {return 'و';}break;
            case 'خانيونس': {return 'خ';}break;
            case 'رفح': {return 'ر';}break;
        }
    }
    public function getAreaAttribute(){
        return $this->place ? ($this->place->area ? $this->place->area : new Area()) : new Area();
    }
    public function getAreaFatherAttribute(){
        return $this->area ? $this->area->area : new Area();
    }
    public function getAreaSupervisorAttribute(){
        return $this->area_father ? $this->area_father->areaSupervisor : new User();
    }
    public function getSubAreaSupervisorAttribute(){
        return $this->area ? $this->area->subAreaSupervisor : new User();
    }
    public function getSubAreaSupervisorNameAttribute(){
        return $this->sub_area_supervisor ? $this->sub_area_supervisor->name : '';
    }
    public function getAreaSupervisorNameAttribute(){
        return $this->area_supervisor ? $this->area_supervisor->name : '';
    }
    public function studentCourses(){
        return $this->belongsToMany(User::class,CourseStudent::class)->withPivot('id','user_id','course_id','mark');
    }
    public function manyPassedStudentCourses(){
        return $this->hasMany(CourseStudent::class)->where('mark','>=','60');
    }
    public function passedStudentCourses(){
        return $this->belongsToMany(User::class,CourseStudent::class)->wherePivot('mark','>=','60')->withPivot('id','user_id','course_id','mark');
    }
    public function passedStudentCoursesForPermissions(){
        return $this->belongsToMany(User::class,CourseStudent::class)->wherePivot('mark','>=','60')->withPivot('id','user_id','course_id','mark')->withoutGlobalScope('relatedStudents');
    }
    public function failedStudentCourses(){
        return $this->belongsToMany(User::class,CourseStudent::class)->whereNotNull('mark')->wherePivot('mark','<','60')->withPivot('id','user_id','course_id','mark');
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function getBookNameAttribute(){
//        dd($this->teacher);
        return $this->book ?
            $this->book->name : '';
    }
    public function getBookStudentsCategoryStringAttribute(){
        return $this->book ? '<span style="color: #2ca02c; font-zize:18px;">'.$this->book->student_category_string.'</span>' : '';
    }
    public function getBookStudentsHoursCountAttribute(){
        return $this->book ? '<span style="color: red;  font-zize:18px;">'.$this->book->hours_count.'</span>' : '';
    }
    public function getStudentCategoriesAttribute(){
        return $this->book ? $this->book->student_category_array : array();
    }
    public function getStatusSelectAttribute(){
        $selected1 = '';
        $selected2 = '';
        $selected3 = '';
        $selected4 = '';
        $style = '';
        switch ($this->status){
            case 'انتظار الموافقة' : {$selected1 = 'selected';$style='background-color:#51aaf2;';}break;
            case 'قائمة' : {$selected2 = 'selected';$style='background-color:#3cb6ab;';}break;
//            case 'منتهية' : {$selected3 = 'selected';}break;
            case 'معلقة' : {$selected4 = 'selected';$style='background-color:orange;';}break;
        }
        $select = '<select onchange="changeCourseStatus('.$this->id.',this)" class="form-control course_status_select" style="'.$style.'">
                        <option class="course_status_option" '.$selected1.' value="انتظار الموافقة">انتظار الموافقة</option>
                        <option class="course_status_option" '.$selected2.' value="قائمة">قائمة</option>
                        <!--<option '.$selected3.' value="منتهية">منتهية</option>-->
                        <option class="course_status_option" '.$selected4.' value="معلقة">معلقة</option>
                   </select>';
        return hasPermissionHelper('تغيير حالة الدورات العلمية') ? $select : $this->status;
    }
    public function manyStudents(){
        return $this->hasMany(CourseStudent::class);
    }
    public function manyStudentsForPermissions(){
        return $this->hasMany(CourseStudent::class)->withoutGlobalScope('relatedCourseStudents');
    }
    public function students(){
        return $this->belongsToMany(User::class,CourseStudent::class)->withPivot('id','user_id','course_id','mark');
    }
    public function studentsForPermissions(){
        return $this->belongsToMany(User::class,CourseStudent::class)->withPivot('id','user_id','course_id','mark')->withoutGlobalScope('relatedUsers');
    }
    public function passedStudents(){
        return $this->belongsToMany(User::class,CourseStudent::class)->wherePivot('mark','>=','60');
    }
    public function exam(){
        return $this->morphOne(Exam::class,'examable');
    }
    public function getHasNextExamAttribute(){
        return $this->exam ? $this->exam->where('status',1)->count() ? 1 : 0 : 0;
    }
    public function getExamNotesAttribute(){
        return $this->exam ? $this->exam->notes : '';
    }
    public function getExamDateAttribute(){
        return $this->exam ? $this->exam->date : '';
    }
    public function getExamPlaceIdAttribute(){
        return $this->exam ? $this->exam->place_id : '';
    }
    public function getExamStatusAttribute(){
        return $this->exam ? $this->exam->status : 0;
    }
    public function getExamAsHtmlAttribute(){
//        $this->sub_area_supervisor_name;
        return '
        <div class="modal-header">
            <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">طلب حجز موعد اختبار لدورة علمية</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="'.route('exams.newCourseExamAppointment',$this->id).'" method="POST" id="form">
            '.csrf_field().'
                <table class="table table-responsive table-bordered">
                    <tr>
                        <td>عدد الطلاب:</td>
                        <td>'.$this->students->count().'</td>
                    </tr>
                    <tr>
                        <td>اسم المعلم:</td>
                        <td>'.$this->teacher_name.'</td>
                    </tr>
                    <tr>
                        <td>عدد الساعات:</td>
                        <td>'.$this->book_students_hours_count.'</td>
                    </tr>
                    <tr>
                        <td>المشرف الميداني:</td>
                        <td>'.$this->sub_area_supervisor_name.'</td>
                    </tr>
                    <tr>
                        <td>رقم جوال المعلم:</td>
                        <td>'.$this->teacher_mobile.'</td>
                    </tr>
                    <tr>
                        <td>مكان الإختبار:</td>
                        <td>
                            <select class="form-control" name="place_id">
                                '.getAreaPlacesForCourseExam($this->area_id,($this->place_id ? $this->exam_place_id : $this->place_id)).'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ملاحظات:</td>
                        <td><textarea name="notes" class="form-control" cols="30" rows="3">'.$this->exam_notes.'</textarea></td>
                    </tr>
                </table>

            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" onclick="getEligibleCourses()">رجوع</button>
            <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
        </div>

            <script>
        $("#form").submit(function(event){
            $("input").removeClass("is-invalid");
            $(".invalid-feedback").remove();
            var form = $(this).serialize();
            $("input[disabled]").each( function() {
                form = form + "&" + $(this).attr("name") + "=" + $(this).val();
            });
            event.preventDefault(); // avoid to execute the actual submit of the form.
            $.ajax({
                url:$(this).attr("action"),
                type:$(this).attr("method"),
                data:form,
                success:function(result){
                    $.notify("&nbsp;&nbsp;&nbsp;&nbsp; <strong>"+ result.title +" </strong> | "+result.msg,
                        { allow_dismiss: true,type:result.type }
                    );
                    document.querySelector("button[data-bs-dismiss=\'modal\']").click();
                    // setTimeout(function(){
                    $("#dataTable").DataTable().ajax.reload();
                    // }, 1100)
                }

            });
            return false;
        });
    </script>';
    }


    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('start_date', 'like', "%" . $searchWord . "%")
            ->orWhere('student_category', 'like', "%" . $searchWord . "%")
            ->orWhere('course_type', 'like', "%" . $searchWord . "%")
            ->orWhere('status', 'like', "%" . $searchWord . "%")
            ->orWhereHas('book',function($query) use ($searchWord){
                $query->search($searchWord);
            })
            ->orWhereHas('teacher',function($query) use ($searchWord){
                $query->searchuser($searchWord);
            })
            ->orWhereHas('place',function($query) use ($searchWord){
                $query->search($searchWord);
            });
    }

    public function scopePlaceArea($query,$place_id){

        if($place_id) {
                return $query->where('place_id',$place_id);
            }else{
                return $query;
            }
    }

    public function scopeSupervisorSearch($query,$general_supervisor, $place_supervisor){

        return $query->where('id', 'like', "%" . $general_supervisor . "%")
        ->orWhere('start_date', 'like', "%" . $place_supervisor . "%");

    }

    public function scopeStatus($query,$status)
    {
        if($status) {
            return $query->where('status',$status);
        }else{
            return $query;
        }
    }
    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if ($sub_area_id){
//            dd($sub_area_id,1);
            return $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                $query->where('area_id', $sub_area_id);
            });
        }else if($area_id){
//            dd(2);
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        }else{
            return $query;
        }
    }

    public function scopeWhereStatus($query,$status)
    {
        if ($status) {
            return $query->where('status',$status);
        }else{
            return $query;
        }
    }

    public function scopeExportStatus($query,$export_status)
    {
        if ($export_status) {
                    $export_status = ($export_status == 2)?0: $export_status;
            return $query->where('is_certifications_exported',$export_status);
        }else{
            return $query;
        }
    }


    public function scopeTeacher($query,$teacher_id)
    {
        if ($teacher_id) {
            return $query->where('teacher_id',$teacher_id);
        }else{
            return $query;
        }
    }

    // public function scopeFieldSupervisor($query,$sub_area_id)
    // {
    //     if ($sub_area_id) {
    //         return $query->where('teacher_id',$sub_area_id);
    //     }else{
    //         return $query;
    //     }
    // }


    public function scopeBook($query,$book_id)
    {
        if ($book_id) {
            return $query->where('book_id',$book_id);
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
        })->orWhereHas('teacher', function ($query) use ($role) {
            return $query->where('role', $role);
        });
    }
    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
//        dd($sub_area_id,$area_id);
        if($area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
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
    /**
     * Scopes
     */

    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'start_date': {return 'تارخ البداية';}break;
            case 'place_id': {return 'المكان';}break;
//            case 'course_type': {return 'نوع الدورة';}break;
            case 'included_in_plan': {return 'نوع الخطة';}break;
            case 'book_id': {return 'الكتاب';}break;
            case 'teacher_id': {return 'المعلم';}break;
            case 'hours': {return 'عدد الساعات';}break;
            case 'notes': {return 'ملاحظات';}break;
            case 'status': {return 'الحالة';}break;
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id': {return $this->id;}break;
            case 'start_date': {return $this->start_date;}break;
            case 'place_id': {return $this->place_name;}break;
            case 'included_in_plan': {return $this->included_in_plan;}break;
            case 'teacher_id': {return $this->teacher_name;}break;
            case 'book_id': {return $this->book_name;}break;
            case 'hours': {return $this->hours;}break;
            case 'notes': {return $this->notes;}break;
            case 'status': {return $this->status;}break;
        }
    }
    protected static $logAttributes = ['id','start_date','place_id','course_type','included_in_plan','book_id','teacher_id','hours','status','note'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة دورة للمعلم '.$activity->subject->teacher_name;
            }break;
            case 'updated':{
                $description = ' قام '.$activity->causer->name.' بتعديل دورة المعلم '.$activity->subject->teacher_name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف دورة المعلم '.$activity->subject->teacher_name;
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
        return $this->CreatedBy ? $this->CreatedBy->where('log_name','created')[0]->causer : 0 ;
    }

    /**
     * End activities functions
     */
    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();

            $sub_area = Area::where('sub_area_supervisor_id',$user->id)->withoutGlobalScope('relatedAreas')->first();
            $sub_area_supervisor_area_id = $sub_area ? $sub_area->id : 0;
            $sub_area_supervisor_area_father_id = $sub_area ? $sub_area->area_id : 0;
            $father_area = Area::where('area_supervisor_id',$user->id)->withoutGlobalScope('relatedAreas')->first();
            $area_supervisor_area_id = $father_area ? $father_area->id : 0;
            static::addGlobalScope('relatedCourses', function (Builder $builder) use ($user,$sub_area_supervisor_area_father_id,$area_supervisor_area_id,$sub_area_supervisor_area_id) {
                if ($user) {


                    if ($user->hasRole('رئيس الدائرة')){
                        return $builder;
                    } else if($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')){
                        // return $builder->genderdepartment($user->role);
                        return $builder;

                    }else if($user->hasRole('مشرف عام')){
//                        dd($area_supervisor_area_id);
                        return $builder->permissionssubarea(0,$area_supervisor_area_id);
                        // return $builder;

                        // return $builder->permissionssubarea($sub_area_supervisor_area_id,0);
                    }else if($user->hasRole('مشرف ميداني')){
//                        dd($user->sub_area_supervisor_area_id,$user);
                        // return $builder->permissionssubarea($user->area_supervisor_area_id,0);
                        return $builder->permissionssubarea($sub_area_supervisor_area_id,0);
                    }else if($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')){
                        return $builder->where('teacher_id',$user->id);
                    }else if($user->hasRole('مدير دائرة التخطيط والجودة')){
                        return $builder;
                    }else if($user->hasRole('رئيس قسم الاختبارات')){
                        return $builder;
                    }else if($user->hasRole('مدير فرع')){
                        return $builder->permissionssubarea(0, $user->branch_supervisor_area_id);
                    }
                    else{
                        return $builder->whereHas('teacher',function ($query) use($user){
                            $query->whereHas('students',function($query1) use($user){
                                $query1->where('id',$user->id);
                            });
                        });
                    }
                }else {
                    return false;
                }
            });
        }
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($course) {
            if($course->exam) {
                $course->exam->delete();
            }
            // if($course->students) {
            //     $course->students()->each(function($student){
            //         $student->delete();
            //     });
            // }

        });
    }

}
