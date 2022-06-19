<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedCourse extends Model
{
    use HasFactory;
    public static $counter=0;
    protected $fillable = ['start_date','place_id','course_type','book_id','teacher_id','hours','status','note'];
    public function getCourseDisplayDataAttribute(){
        self::$counter++;
        $addStudent = ' ';
        $addExcelStudent = ' ';


        if(hasPermissionHelper('اضافة طالب جديد الأسانيد والإجازات')){

        $addStudent = $this->status != 'منتهية' ? '<button type="button" class="btn btn-info btn-sm" title="اضافة طالب"  onclick="createNewCourseStudents('.$this->id.')"><i class="mdi mdi-account-plus"></i></button>' : '';        
        $addExcelStudent = ($this->status != 'منتهية') ?'<button type="button" class="btn btn-primary btn-sm" title="اضافة طلاب من ملف اكسل"  onclick="addExcelAsaneedStudents('.$this->id.')"><i class="mdi mdi-microsoft-excel"></i></button>&nbsp' : '';
        
    }

        $options = '';
        if(hasPermissionHelper('تصفح بيانات دورةالسند')){
            $options .=' <button type="button" class="btn btn-success btn-sm" title="عرض الطلاب" data-url="'.route('asaneedCourseStudents.ShowCourseStudents',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-account-multiple"></i></button>';
        }

        if(hasPermissionHelper('تعديل بيانات الاسانيد')){
            $options .= ' <button type="button" class="btn btn-warning btn-sm" title="تعديل بيانات الدورة" data-url="'.route('asaneedCourses.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2" onclick="callApi(this,\'user_modal_content_new\')"><i class="mdi mdi-comment-edit"></i></button>';        
        }

        if(hasPermissionHelper('حذف بيانات الاسانيد')){
              $options .= ' <button type="button" class="btn btn-danger btn-sm" title="حذف بيانات الدورة" data-url="'.route('asaneedCourses.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>';
        }

        $status = '';
        if(hasPermissionHelper('تغيير حالة الاسانيد')){
         $status = $this->status != 'منتهية' ? $this->status_select : $this->status;
        }
        return [
            'id'=>self::$counter,
            'teacher_name'=>$this->name,
            'book'=>$this->book_name,
            'place'=>$this->area_father_name.' - '.$this->area_name.' <br> '.$this->place_name,
            'supervisor' =>'الميداني: '.$this->sub_area_supervisor_name.'<br>'
                                .'العام: '.$this->area_supervisor_name,
            'studentCount'=>$this->students->count(),
            'status'=>$status,
            'tools'=>$addStudent.' '.$addExcelStudent.' '.$options,
        ];
    }
    public function getNameAttribute(){
        return $this->teacher_name;
    }
    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }
    public function getTeacherMobileAttribute(){
//        dd($this->teacher);
        return $this->teacher ? $this->teacher->mobile : '';
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
    public function book(){
        return $this->belongsTo(AsaneedBook::class,'book_id');
    }
    public function getBookNameAttribute(){
//        dd($this->teacher);
        return $this->book ?
            $this->book->name : '';
    }

    public function manyStudentsForPermissions(){
        return $this->hasMany(AsaneedCourseStudent::class)->withoutGlobalScope('relatedCourseStudents');
    }

    public function getBookStudentsCategoryStringAttribute(){
        return $this->book ? '<span style="color: #2ca02c;">'.$this->book->student_category_string.'</span>' : '';
    }
    public function getBookStudentsHoursCountAttribute(){
        return $this->book ? '<span style="color: red;">'.$this->book->hours_count.'</span>' : '';
    }
    public function getStudentCategoriesAttribute(){
        return $this->book ? $this->book->student_category_array : array();
    }
    public function getStatusSelectAttribute(){
        $selected1 = '';
        $selected2 = '';
        $selected3 = '';
        $selected4 = '';
        $selected5 = '';

        switch ($this->status){
            case 'انتظار الموافقة' : {$selected1 = 'selected';}break;
            case 'قائمة' : {$selected2 = 'selected';}break;
//            case 'منتهية' : {$selected3 = 'selected';}break;
            case 'معلقة' : {$selected4 = 'selected';}break;
            case 'بانتظار اعتماد الدرجات' : {$selected5 = 'selected';}break;

        }
        $select = '<select onchange="changeCourseStatus('.$this->id.',this.value)" class="form-control">
                        <option '.$selected1.' value="انتظار الموافقة">انتظار الموافقة</option>
                        <option '.$selected2.' value="قائمة">قائمة</option>
                        <!--<option '.$selected3.' value="منتهية">منتهية</option>-->
                        <option '.$selected4.' value="معلقة">معلقة</option>
                        <option '.$selected5.' value="بانتظار اعتماد الدرجات">بانتظار اعتماد الدرجات</option>


                   </select>';
        return $select;
    }
    public function students(){
        return $this->belongsToMany(User::class,AsaneedCourseStudent::class)->withPivot('id','user_id','asaneed_course_id','mark');
    }
    public function passedStudents(){
        return $this->belongsToMany(User::class,AsaneedCourseStudent::class)->wherePivot('mark','>=','60');
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
    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if ($sub_area_id) {
            return $query->whereHas('place', function ($query) use ($sub_area_id) {
                $query->where('area_id', $sub_area_id);
            });
        } else if($area_id){
            return $query->whereHas('place', function ($query) use ($area_id) {
                $query->whereHas('area', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        }else{
            return $query;
        }
    }

    public function scopeWhereStatus($query,$status){
        if ($status){
            return $query->where('status',$status);
        }else{
            return $query;
        }
    }

    public function scopePlaceArea($query,$place_id){

        if($place_id) {
                return $query->where('place_id',$place_id);
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


    public function scopeBook($query,$book_id)
    {
        if ($book_id) {
            return $query->where('book_id',$book_id);
        }else{
            return $query;
        }
    }
    /**
     * Scopes
     */
    public function exam(){
        return $this->morphOne(Exam::class,'examable');
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

    public static function boot() {
        parent::boot();

        static::deleting(function($course) {
            if($course->exam) {
                $course->exam->delete();
            }
            if($course->students) {
                $course->students()->each(function($student){
                    $student->delete();
                });
            }

        });
    }
}
