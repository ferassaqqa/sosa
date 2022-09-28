<?php

namespace App\Http\Controllers\controlPanel;

use App\Exports\courseStudentsExport;
use App\Exports\StudentCoursesExport;
use App\Exports\CourseStudentsFaultsExport;
use App\Exports\courseStudentsMarksExport;
use App\Exports\exportMoallemsAsExcelSheet;
use App\Exports\RoleExport;
use App\Exports\RoleFaultsExport;
use App\Exports\asaneedStudentsFaultsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Roles\ImportExcelRequest;
use App\Imports\CourseNewStudentsMarkImport;
use App\Imports\CourseStudentsImport;
use App\Imports\CourseStudentsMarkImport;
use App\Imports\RoleImport;
use App\Models\Course;
use App\Models\AsaneedCourse;
use App\Imports\AsaneedStudentsImport;
use App\Models\Exam;
use App\Models\Circle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Models\Role;
use App\Imports\CircleStudentsImport;
use App\Exports\CircleStudentsFaultsExport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ExcelExporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', 360);
    }
    public function exportRolesExcel(Excel $excel,Request $request){
        return $excel->download(
            new RoleExport($request->search,$request->isDeleted,$request->page,$request->ids)
            , 'ادوار المستخدمين.xlsx');
    }
    public function importRolesExcel(Excel $excel,ImportExcelRequest $request){
        $import = new RoleImport();
        $import->import(request()->file('file'));
        $excel->store(
            new RoleFaultsExport($import->failures())
            , 'public/roles_faulties.xlsx');
//        dd(asset('storage/roles_faulties.xlsx'));
//        return Response::download();
        return response()->json(['file_link'=>asset('storage/roles_faulties.xlsx')]);
    }


    public function importAsaneedStudentsExcel(AsaneedCourse $asaneedCourse,Excel $excel,ImportExcelRequest $request){

        $import = new AsaneedStudentsImport($asaneedCourse);
        $import->import(request()->file('file'));
        $asaneedCourse->update(['status'=>'قائمة']);



        $exam = Exam::where('examable_id','=', $asaneedCourse->id)->where('examable_type' , 'App\Models\AsaneedCourse')->withoutGlobalScopes()->first();

        $students_count = $asaneedCourse->students->count();
        if($students_count >= 10 && !$exam){
            $asaneedCourse->exam()->create($request->all());

        }

        $asaneedCourse->exam()->update([
            'status' => 5
        ]);

        if($import->failures()->count()) {
            $excel->store(
                new asaneedStudentsFaultsExport($import->failures())
                , 'public/ اخطاء استيراد الطلاب من ملف الاكسل لدورة ' . $asaneedCourse->book_name . ' للمعلم ' . $asaneedCourse->name . '.xlsx');
            return response()->json(['msg'=>'تم استيراد ملف دورة '. $asaneedCourse->book_name . ' للمعلم ' . $asaneedCourse->name.' <br><span>
           <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"><div class="swal2-icon-content">!</div></div>
            ويوجد عدد '.$import->failures()->count().' طلاب لم يتم استيرادهم ، لمعرفة الارقام <a  style="color: red;" href="'.asset('storage/ اخطاء استيراد الطلاب من ملف الاكسل لدورة ' . $asaneedCourse->book_name . ' للمعلم ' . $asaneedCourse->name . '.xlsx').'">اضغط هنا</a> لتحميل الملف.</span> ']);
        }else{

            if($students_count < 10 ){
            return response()->json(['msg'=>'<span>
                            <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"><div class="swal2-icon-content">!</div></div>
                            تم استيراد ملف الدورة بنجاح. يرجى العلم بان الحد الادنى لحجز موعد اختيار هو 10 طلاب لمجلس السند</span> ']);
            }else{
            return response()->json(['msg'=>'تم استيراد ملف دورة '. $asaneedCourse->book_name . ' للمعلم ' . $asaneedCourse->name.' بنجاح.']);
            }

        }
    }

    public function importCircleStudentsExcel(Circle $circle,Excel $excel,ImportExcelRequest $request){
        $import = new CircleStudentsImport($circle);
        $import->import(request()->file('file'));


        if ($import->failures()->isNotEmpty()) {

            $excel->store(
                new CircleStudentsFaultsExport($import->failures())
                , 'public/ اخطاء استيراد الطلاب من ملف الاكسل للحلقة  للمعلم ' . $circle->teacher_name . '.xlsx');

            $failures = $import->failures();

            // dd($failures);

            $errors =  view('control_panel.courses.basic.failures',compact('failures'));
            return response()->json(['msg'=>'تم استيراد ملف دورة '. $circle->teacher_name . ' للمعلم ' . $circle->teacher_name.' <br><span>
           <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"><div class="swal2-icon-content">!</div></div>
            ويوجد عدد '.$import->failures()->count().' طلاب لم يتم استيرادهم ، لمعرفة الارقام <a  style="color: red;" href="'.asset('storage/ اخطاء استيراد الطلاب من ملف الاكسل لدورة ' . $circle->teacher_name . ' للمعلم ' . $circle->teacher_name . '.xlsx').'">اضغط هنا</a> لتحميل الملف.</span> '. $errors]);
        }else{
            return response()->json(['msg'=>'تم استيراد ملف دورة '. $circle->teacher_name . ' للمعلم ' . $circle->teacher_name.' بنجاح.']);
        }



    }


    public function importCourseStudentsExcel(Course $course,Excel $excel,ImportExcelRequest $request){

        $file = $request->file('file')->store('import');
        $import = new CourseStudentsImport($course);
        $import->import($file);


        if ($import->failures()->isNotEmpty()) {

            $excel->store(
                new CourseStudentsFaultsExport($import->failures())
                , 'public/ اخطاء استيراد الطلاب من ملف الاكسل لدورة ' . $course->book_name . ' للمعلم ' . $course->name . '.xlsx');

            $failures = $import->failures();
            $errors =  view('control_panel.courses.basic.failures',compact('failures'));
            return response()->json(['status'=>'warning','msg'=>'تم استيراد ملف دورة '. $course->book_name . ' للمعلم ' . $course->name.' <br><span>
                        ويوجد عدد '.$import->failures()->count().' طلاب لم يتم استيرادهم ، لمعرفة الارقام <a  style="color: red;" href="'.asset('storage/ اخطاء استيراد الطلاب من ملف الاكسل لدورة ' . $course->book_name . ' للمعلم ' . $course->name . '.xlsx').'">اضغط هنا</a> لتحميل الملف.</span> '. $errors]);
        }else{

            $students_count = $course->students->count();
            if($students_count < 10 ){
                return response()->json(['status'=>'info','msg'=>'<span>
                            تم استيراد ملف الدورة بنجاح. يرجى العلم بان الحد الادنى لحجز موعد اختيار هو 10 طلاب للدورة الواحدة</span> ']);
            }else{
            // $course->update(['status'=>'قائمة']);
            // $has_exam = Exam::where('examable_id', $course->id )->exists();
            // if(!$has_exam){$course->exam()->create($request->all());}

            return response()->json(['status'=>'success','msg'=>'تم استيراد ملف دورة '. $course->book_name . ' للمعلم ' . $course->name.' بنجاح.']);
            }
        }




    }


    public function exportCourseStudentsMarksExcelSheet(Excel $excel,Course $course){
        $excel->store(
            new courseStudentsMarksExport($course)
            , 'public/ كشف درجات دورة ' . $course->book_name . ' للمعلم ' . $course->name . ' منطقة ' . $course->area_father_name_for_permissions . '.xlsx');
        $course->update(['is_certifications_exported'=>1]);
        $filepath = Storage::url('public/ كشف درجات دورة ' . $course->book_name . ' للمعلم ' . $course->name . ' منطقة ' . $course->area_father_name_for_permissions . '.xlsx');
        return response()->json(['file_link' => $filepath,
        'msg'=>'تم استيراد الطلاب عدد '.$course->manyStudentsForPermissions->count().' بنجاح ، من أصل '.$course->manyStudentsForPermissions->count().'طالب.']);
    }


    public function importCourseStudentsMarkExcel(Exam $exam,Excel $excel,ImportExcelRequest $request){
        $import = new CourseStudentsMarkImport($exam);
        $import->import(request()->file('file'));
    }
    public function importCourseNewStudentsMarkExcel(Exam $exam,Excel $excel,ImportExcelRequest $request){
        $import = new CourseNewStudentsMarkImport($exam);
        $import->import(request()->file('file'));
    }
    public function exportCourseExamStudentsListAsExcelFile(Excel $excel,Exam $exam){
        $course = $exam->course;
        $excel->store(
            new courseStudentsExport($course)
            , 'public/ طلاب دورة ' . $course->book_name . ' للمعلم ' . $course->name . ' منطقة ' . $course->area_father_name_for_permissions . '.xlsx');
        return response()->json(['file_link' => asset('storage/ طلاب دورة ' . $course->book_name . ' للمعلم ' . $course->name . ' منطقة ' . $course->area_father_name_for_permissions . '.xlsx')]);
    }
    public function exportMoallemsAsExcelSheet(Excel $excel,Request $request){

        $search = (isset($request->search)&&!empty($request->search)) ? $request->search : '';
        $sub_area_id = (isset($request->sub_area_id)&&!empty($request->sub_area_id)) ? $request->sub_area_id : '';
        $area_id = (isset($request->area_id)&&!empty($request->area_id)) ? $request->area_id : '';
        $excel->store(
            new exportMoallemsAsExcelSheet($sub_area_id,$area_id,$search)
            , 'public/قائمة المعلمين.xlsx');
        return response()->json(['file_link' => asset('storage/قائمة المعلمين.xlsx')]);
    }


    public function exportStudentCoursesAsExcelSheet(Excel $excel,Request $request){

        $user_id = (isset($request->user_id)&&!empty($request->user_id)) ? $request->user_id : '';
        $user = User::find($user_id);
        $excel->store(
            new StudentCoursesExport($user)
            , 'public/قائمة دورات الطالب ' . $user->name . '.xlsx');
        return response()->json(['file_link' => asset('storage/قائمة دورات الطالب ' . $user->name . '.xlsx')]);
    }

}
