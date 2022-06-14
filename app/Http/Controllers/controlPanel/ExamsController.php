<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\exams\newCourseExamRequest;
use App\Models\Area;
use App\Models\Book;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamsController extends Controller
{
    public function examEligibleCourses(){
        checkPermissionHelper('حجز موعد اختبار');

        $areas = Area::whereNull('area_id')->get();
        $moallems = User::department(2)->whereHas('teacherCourses',function($query){
            $query->where('status', 'قائمة')->whereDoesntHave('exam')->has('manyStudentsForPermissions', '>', 9);
        })->get();
        $books = Book::department(2)->whereHas('courses',function($query){
            $query->where('status', 'قائمة')->whereDoesntHave('exam')->has('manyStudentsForPermissions', '>', 9);
        })->where('year',Carbon::now()->format('Y'))->get();
        $courses = Course::where('status', 'قائمة')->whereDoesntHave('exam')->has('manyStudentsForPermissions', '>', 9)->get();

//        dd($courses);

        return view('control_panel.exams.examEligibleCourses', compact('courses','areas','moallems','books'));

    }
    public function getExamEligibleCoursesData(Request $request){
        checkPermissionHelper('حجز موعد اختبار');
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'book_id', 'dt' => 1),
            array('db' => 'name', 'dt' => 2),
            array('db' => 'place_id', 'dt' => 3),
            array('db' => 'place_id', 'dt' => 4),
            array('db' => 'place_id', 'dt' => 5),
            array('db' => 'place_id', 'dt' => 6),
//            array('db' => 'tools', 'dt' => 5),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $columns[$order]["db"] = $columns[$order]["db"]=='id' ? 'updated_at' : $columns[$order]["db"] ;
        $direction = $columns[$order]["db"]=='created_at' ? 'DESC' : $direction ;

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;

        $value = array();

        if (!empty($search)) {
            $count = Course::where('status', 'قائمة')
                ->whereDoesntHave('exam')
                ->subarea($sub_area_id,$area_id)
                ->teacher($moallem_id)
                ->book($book_id)
                ->has('manyStudentsForPermissions', '>', 9)
                ->search($search)
                ->count();

            $courses = Course::where('status', 'قائمة')
                ->whereDoesntHave('exam')
                ->subarea($request->sub_area_id,$request->area_id)
                ->teacher($request->moallem_id)
                ->book($request->book_id)
                ->has('manyStudentsForPermissions', '>', 9)
                ->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Course::where('status', 'قائمة')
                ->whereDoesntHave('exam')
                ->subarea($request->sub_area_id,$request->area_id)
                ->teacher($request->moallem_id)
                ->book($request->book_id)
                ->has('manyStudentsForPermissions', '>', 9)
                ->count();
            $courses = Course::where('status', 'قائمة')
                ->whereDoesntHave('exam')
                ->subarea($request->sub_area_id,$request->area_id)
                ->teacher($request->moallem_id)
                ->book($request->book_id)
                ->has('manyStudentsForPermissions', '>', 9)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        Course::$counter = $start;
        $st_count = 0;
        foreach ($courses as $index => $item) {
//            dd($item);
            $st_count += $item->students_count;
            Course::$counter++;
            array_push(
                $value,
                [
                    'id'=>Course::$counter,
                    'book_name'=>$item->book_name,
                    'name'=>$item->name,
                    'area_father_name'=>$item->area_father_name,
                    'area_name'=>$item->area_name,
                    'place_name'=>$item->place_name,
                    'students_count'=>$item->students->count(),
                    'tools'=>'<button class="btn btn-success" onclick="examAppointment('.$item->id.')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i class="mdi mdi-table"></i></button>'
                ]
            );
        }
//        dd($st_count);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getCourseExamAppointment(Course $course){
        return $course->exam_as_html;
    }
    public function newCourseExamAppointment(Request $request,Course $course){
        if($course->exam){
//            dd($course->exam,$request->all());
            $course->exam->update($request->all());
        }else{
            $course->exam()->create($request->all());
        }
        return response()->json(['msg'=>'تم تحديد طلب موعد اختبار الدورة بنجاح','title'=>'موعد','type'=>'success']);
    }
    public function getPendingExamRequests(){
//        dd($request->all());
        checkPermissionHelper('طلبات حجز مواعيد الاختبارات');
        $areas = Area::whereNull('area_id')->get();
        $moallems = User::department(2)->whereHas('teacherCourses',function($query){
            $query->whereHas('exam',function($query){
                $query->where('status', 0);
            });
        })->get();
        $books = Book::department(2)->whereHas('courses',function($query){
            $query->whereHas('exam',function($query){
                $query->where('status', 0);
            });
        })->where('year',Carbon::now()->format('Y'))->get();
        $exams = Exam::where('status', 0)->get();
        $students = $exams->sum('students_count');
        $courses = $exams->count();
        return view('control_panel.exams.getPendingExamRequests', compact('courses','students','exams', 'areas','moallems','books'));
    }
    public function getPendingExamRequestsData(Request $request){


        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $columns[$order]["db"] = 'id';
        $direction = $columns[$order]["db"]=='id' ? 'DESC' : $direction ;

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;

        $value = array();

        if (!empty($search)) {
            $count = Exam::where('status', 0)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->book($book_id)
                ->search($search)
                ->count();

            $exams = Exam::where('status', 0)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->book($book_id)
                ->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Exam::where('status', 0)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->book($book_id)
                ->count();
            $exams = Exam::where('status', 0)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->book($book_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        Exam::$counter = $start;
        foreach ($exams as $index => $item) {
            $approveButton = hasPermissionHelper('تأكيد طلبات الحجز') ? '<button class="btn btn-success" onclick="approveExamAppointment(this,'.$item->id .')"><i class="mdi mdi-table"></i></button>' : '';
            $removeButton = hasPermissionHelper('حذف طلبات مواعيد الاختبارات') ? '<button class="btn btn-danger" onclick="deleteExamAppointment(this,'.$item->id .')"><i class="mdi mdi-close"></i></button>' : '';

            Exam::$counter++;
            array_push(
                $value,
                [
                    'id'=>Exam::$counter,
                    'course_book_name'=>$item->course_book_name,
                    'course_name'=>$item->course_name,
                    'students_count'=>$item->students_count,
                    'course_place_name'=>$item->course_place_name,
                    'place_name'=>$item->place_name,
                    'course_area_name'=>$item->course_area_name,
                    'notes'=>$item->notes,
                    'tools'=>$approveButton.'&nbsp'.$removeButton
                ]
            );
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getNextExamsAppointments(){
        checkPermissionHelper('مواعيد الاختبارات');
        $areas = Area::whereNull('area_id')->get();

        $moallems = User::department(2)->whereHas('teacherCourses',function($query){
            $query->whereHas('exam',function($query){
                $query->where('status', 0);
                $query->orWhere('status', 1);
            });
        })->get();
        $books = Book::department(2)->whereHas('courses',function($query){
            $query->whereHas('exam',function($query){
                $query->where('status', 0);
                $query->orWhere('status', 1);
            });
        })->where('year',Carbon::now()->format('Y'))->get();


//         $exams = Exam::where('status', 1)
//             ->where('examable_type', 'App\Models\Course')
//             ->whereHas('examable', function ($query) {
//                 $query->where('status', 'قائمة');
//             })
// //            ->withoutGlobalScope('relatedExams')
//             ->get();
//        dd($exams);
        return view('control_panel.exams.getNextExamsAppointments', compact('areas','books','moallems'));
    }
    public function getNextExamsAppointmentsData(Request $request){

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $columns[$order]["db"] = 'id';
        $direction = $columns[$order]["db"]=='id' ? 'DESC' : $direction ;

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;

        $startDate = $request->start_date ? $request->start_date : '';
        $endDate = $request->end_date ? $request->end_date : '';

        $place_area = $request->place_area ? $request->place_area : 0;

        $exam_type = $request->exam_type ? $request->exam_type : 0;


        // echo $sub_area_id; exit;


        $value = array();

        if (!empty($search)) {
            $count = Exam::where('status', 1)
                ->orWhere('status', 0)
                ->subarea($sub_area_id, $area_id)
                ->examtype($exam_type)
                ->placearea($place_area)
                ->search($search)
                ->fromDate($startDate)
                ->toDate($endDate)
                ->moallem($moallem_id)
                ->book($book_id)
                ->count();

            $exams = Exam::where('status', 1)
                ->orWhere('status', 0)
                ->subarea($sub_area_id, $area_id)
                ->examtype($exam_type)
                ->placearea($place_area)
                ->fromDate($startDate)
                ->toDate($endDate)
                ->moallem($moallem_id)
                ->book($book_id)
                ->orderBy('id', 'DESC')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Exam::where('status', 1)
                ->subarea($sub_area_id, $area_id)
                ->examtype($exam_type)
                ->fromDate($startDate)
                ->placearea($place_area)
                ->toDate($endDate)
                ->orWhere('status', 0)
                ->moallem($moallem_id)
                ->book($book_id)
                ->count();
            $exams = Exam::where('status', 1)
                ->orWhere('status', 0)
                ->fromDate($startDate)
                ->placearea($place_area)
                ->toDate($endDate)
                ->moallem($moallem_id)
                ->book($book_id)
                ->subarea($sub_area_id, $area_id)
                ->examtype($exam_type)
                ->orderBy('id', 'DESC')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        Exam::$counter = $start;
        Carbon::setLocale('ar');
        foreach ($exams as $index => $item) {
            $approveButton = hasPermissionHelper('تأكيد طلبات الحجز') ? '<button class="btn btn-success" onclick="approveExamAppointment(this,'.$item->id .')"><i class="mdi mdi-table"></i></button>' : '';
            $removeButton = hasPermissionHelper('حذف طلبات مواعيد الاختبارات') ? '<button class="btn btn-danger" onclick="deleteExamAppointment(this,'.$item->id .')"><i class="mdi mdi-close"></i></button>' : '';

            Exam::$counter++;
            array_push(
                $value,
                [
                    'id'=>Exam::$counter,
                    'course_book_name'=>$item->course_book_name,
                    'exam_type'=>$item->exam_type,
                    'students_count'=>$item->students_count,
                    'course_name'=>$item->course_name,
                    'teacher_mobile'=>$item->teacher_mobile,
                    // 'course_area_father_name'=>$item->course_area_father_name,
                    // 'course_area_name'=>$item->course_area_name,
                    'area' => $item->course_area_father_name.' - '.$item->course_area_name,
                    'quality_supervisors_string'=>$item->quality_supervisors_string,
                    'place_name'=>$item->place_name,

                    'date'=> $item->date? GetFormatedDate($item->date) . ' الساعة '. Carbon::parse($item->time)->isoFormat('h:mm a'):'',
                    'tools'=>$approveButton.'&nbsp'.$removeButton

                ]
            );
        }
//        dd($st_count);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getExamsAppointmentsArchive(){
        checkPermissionHelper('ارشيف مواعيد الاختبارات');
        $areas = Area::whereNull('area_id')->get();
        $exams = Exam::where('status', 5)->where('date', '<', Carbon::now()->format('Y-m-d'))->get();
        return view('control_panel.exams.getExamsAppointmentsArchive', compact('exams', 'areas'));

    }
    public function getMoallemsList($area_id){
        $moallems = User::department(2)->areascope($area_id)->get();

        $moallemLists = '<option value="0">-- اختر المعلم --</option>';
        foreach ($moallems as $key => $moallem){
            $moallemLists .= '<option value="'.$moallem->id.'">'.$moallem->name.'</option>';
        }
        return $moallemLists;
    }
    public function getExamsAppointmentsArchiveData(Request $request){
//        checkPermissionHelper('فلترة ارشيف مواعيد الاختبارات');
        checkPermissionHelper('ارشيف مواعيد الاختبارات');$draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $columns[$order]["db"] = 'id';
        $direction = $columns[$order]["db"]=='id' ? 'DESC' : $direction ;

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;
        $value = array();

        if (!empty($search)) {
            $count = Exam::where('status', 5)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->search($search)
                ->count();

            $exams = Exam::where('status', 5)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Exam::where('status', 5)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->count();
            $exams = Exam::where('status', 5)
                ->area($area_id,$sub_area_id)
                ->moallem($moallem_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        Exam::$counter = $start;
        foreach ($exams as $index => $item) {
            Exam::$counter++;
            array_push(
                $value,
                [
                    'id'=>Exam::$counter,
                    'course_book_name'=>$item->course_book_name,
                    'students_count'=>$item->students_count,
                    'passed_students_count'=>$item->passed_students_count,
                    'course_name'=>$item->course_name,
                    'teacher_mobile'=>$item->teacher_mobile,
                    'course_area_father_name'=>$item->course_area_father_name,
                    'course_area_name'=>$item->course_area_name,
                    'place_name'=>$item->place_name,
                    'quality_supervisors_string'=>$item->quality_supervisors_string,
                    'date'=>$item->date . ' || '. Carbon::parse($item->time)->isoFormat('h:mm a'),
                    'tools'=>hasPermissionHelper('استخراج كشف درجات معتمد')
                        ? '<a class="btn btn-success" target="_blank" href="'.route('exportExam',$item->id).'">كشف درجات</a>'
                        : ''
                ]
            );
        }
//        dd($st_count);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getExamsWaitingApproveMarks(){
        checkPermissionHelper('اعتماد الدرجات');
//        dd(Carbon::now()->format('Y-m-d'));
        $areas = Area::whereNull('area_id')->get();
        $exams = Exam::where('status', '>=', 2)->where('status', '<=', 4)->where('date', '<', Carbon::now()->format('Y-m-d'))->get();

        return view('control_panel.exams.getExamsWaitingApproveMarks', compact('exams', 'areas'));
    }
    public function getExamsWaitingApproveMarksData(Request $request){

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $columns[$order]["db"] = 'id';
        $direction = $columns[$order]["db"]=='id' ? 'DESC' : $direction ;

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;
        $value = array();
//        var_dump($area_id);
        if (!empty($search)) {
            $count = Exam::where('status', '>=',2)->where('status', '<=',4)
                ->area($area_id)
                ->search($search)
                ->count();

            $exams = Exam::where('status', '>=',2)->where('status', '<=',4)
                ->area($area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Exam::where('status', '>=',2)->where('status', '<=',4)
                ->area($area_id)
                ->count();
            $exams = Exam::where('status', '>=',2)->where('status', '<=',4)
                ->area($area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        Exam::$counter = $start;
        foreach ($exams as $index => $item) {
            Exam::$counter++;
            array_push(
                $value,
                [
                    'id'=>Exam::$counter,
                    'course_book_name'=>$item->course_book_name,
                    'course_name'=>$item->course_name,
                    'course_area_father_name'=>$item->course_area_father_name,
                    'course_area_name'=>$item->course_area_name,
                    'course_place_name'=>$item->course_place_name,
                    'students_count'=>$item->students_count,
                    'course_start_date'=>$item->course_start_date,
                    'tools'=>hasPermissionHelper('اعتماد نتائج الاختبارات') ?
                        '<button class="btn btn-success" onclick="approveEnteredExamMarks('.$item->id .')">اعتماد الدرجات</button>'
                        :''
                ]
            );
        }
//        dd($st_count);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function approveExamAppointment(Exam $exam){
        $qualitySupervisors = User::department(5)->withoutGlobalScope('relatedUsers')->get();
        $selected_quality_supervisors = $exam->quality_supervisors_array;
        return view('control_panel.exams.approveExamAppointment',compact('exam','qualitySupervisors','selected_quality_supervisors'));
    }
    public function updateExamAppointmentApprove(Exam $exam,$appointment,$date,$quality_supervisor_id,$time,$notes){
//        return [$exam,$appointment,$date,$quality_supervisor_id,$time];
        $quality_supervisor_ids = explode(',',$quality_supervisor_id);
//        $quality_supervisors = $exam->quality_supervisors_array;
//        dd($quality_supervisor_ids,$quality_supervisors);
//        if(!in_array($quality_supervisor_id,$quality_supervisors)) {
//            array_push($quality_supervisors, $quality_supervisor_id);
//        }
        $exam->update([
            'status'=>1,
            'time'=>$time,
            'date'=>$date,
            'notes'=>$notes,
            'appointment'=>$appointment ? $appointment : '',
            'quality_supervisor_id'=>$quality_supervisor_ids,
        ]);
        return response()->json(['msg'=>'تم تأكيد موعد اختبار الدورة بنجاح','title'=>'موعد','type'=>'success','errors'=>0]);
    }
    public function deleteExamQualitySupervisor(Exam $exam,$quality_supervisor_id){
        $quality_supervisors = $exam->quality_supervisors_array;
        if(in_array($quality_supervisor_id,$quality_supervisors)) {
            unset($quality_supervisors[array_search($quality_supervisor_id,$quality_supervisors)]);
        }
        $exam->update([
            'quality_supervisor_id'=>(array)$quality_supervisors,
        ]);
    }
    public function deleteExamAppointment(Exam $exam){
        if($exam) {
            $exam->delete();
        }
    }
    public function getEnterExamMarksForm(Exam $exam){
        checkPermissionHelper('انهاء الدورة و ادخال الدرجات');
//        dd($exam);
        $course = $exam->course;
        $course->load(['studentsForPermissions'=>function($query){
            $query->orderBy('name','Asc');
        }]);
//        dd($exam,$course->studentsForPermissions->toArray());
        return view('control_panel.exams.getEnterExamMarksForm',compact('exam','course'));
    }
    public function approveEnteredExamMarks(Exam $exam){
//        dd($exam);
        $course = $exam->course;
        return view('control_panel.exams.approveEnteredExamMarks',compact('exam','course'));
    }
    public function getEligibleCoursesForMarkEnter(){
        checkPermissionHelper('ادخال الدرجات');
        $areas = Area::whereNull('area_id')->get();
        $books = Book::whereHas('courses', function ($query) {
            $query->whereHas('exam', function ($query) {
                $query->where('status', 1)->where('date', '<=', Carbon::now()->format('Y-m-d'));
            });
        })->where('year',Carbon::now()->format('Y'))->get();
        $exams = Exam::where('status', 1)->where('date', '<=', Carbon::now()->format('Y-m-d'))->get();

        $moallems = User::department(2)->get();

        return view('control_panel.exams.getEligibleCoursesForMarkEnter', compact('exams','areas', 'books','moallems'));
    }
    public function getEligibleCoursesForMarkEnterData(Request $request){
        if(hasPermissionHelper('ادخال الدرجات')){
            $columns = array(
                array('db' => 'id', 'dt' => 0),
                array('db' => 'course_book_name', 'dt' => 1),
                array('db' => 'course_name', 'dt' => 2),
                array('db' => 'course_area_father_name', 'dt' => 3),
                array('db' => 'course_area_name', 'dt' => 4),
                array('db' => 'course_place_name', 'dt' => 5),
                array('db' => 'students_count', 'dt' => 6),
                array('db' => 'course_start_date', 'dt' =>7),
//            array('db' => 'tools', 'dt' => 5),
            );

            $draw = (int)$request->draw;
            $start = (int)$request->start;
            $length = (int)$request->length;
            $order = $request->order[0]["column"];
            $direction = $request->order[0]["dir"];
            $search = trim($request->search["value"]);
            $columns[$order]["db"] = 'updated_at';
            $direction = $columns[$order]["db"]=='created_at' ? 'DESC' : $direction ;

            $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
            $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
            $moallem_id = (int)$request->moallem_id ? (int)$request->moallem_id : 0;
            $book_id = (int)$request->book_id ? (int)$request->book_id : 0;

            $startDate = $request->start_date ? $request->start_date : '';
            $endDate = $request->end_date ? $request->end_date : '';

            $place_area = $request->place_area ? $request->place_area : 0;

            $exam_type = $request->exam_type ? $request->exam_type : 0;



            $count = Exam::where('status', 1)
            ->subarea($sub_area_id, $area_id)
            ->examtype($exam_type)
            ->fromDate($startDate)
            ->placearea($place_area)
            ->toDate($endDate)
            ->orWhere('status', 0)
            ->moallem($moallem_id)
            ->book($book_id)
            ->count();
        $exams = Exam::where('status', 1)
            ->orWhere('status', 0)
            ->fromDate($startDate)
            ->placearea($place_area)
            ->toDate($endDate)
            ->moallem($moallem_id)
            ->book($book_id)
            ->subarea($sub_area_id, $area_id)
            ->examtype($exam_type)
            ->orderBy('id', 'DESC')
            ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
            ->get();



            $value = array();

            if (!empty($search)) {
                $count = Exam::where('status',1)
                    // ->where('date','<=',Carbon::now()->format('Y-m-d'))
                    ->area($area_id,0)->coursebook($book_id)
                    ->search($search)
                    ->count();

                $exams = Exam::where('status',1)
                    // ->where('date','<=',Carbon::now()->format('Y-m-d'))
                    ->area($area_id,0)->coursebook($book_id)
                    ->search($search)
                    ->orderBy('id', 'DESC')
                    ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                    ->get();
            } else {
                $count = Exam::where('status',1)
                    // ->where('date','<=',Carbon::now()->format('Y-m-d'))
                    ->area($area_id,0)->coursebook($book_id)
                    ->search($search)
                    ->count();
                $exams = Exam::where('status',1)
                    // ->where('date','<=',Carbon::now()->format('Y-m-d'))
                    ->area($area_id,0)->coursebook($book_id)
                    ->search($search)
                    ->orderBy('id', 'DESC')
                    ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                    ->get();
            }
        Carbon::setLocale('ar');

            Exam::$counter = $start;
            foreach ($exams as $index => $item) {
//                dd($item->examable);
                Exam::$counter++;
                $enterExamMarksButton = hasPermissionHelper('انهاء الدورة و ادخال الدرجات') ?
                    '<button class="btn btn-success" onclick="enterExamMarks('.$item->id .')">انهاء الدورة</button>'
                    : '';
                array_push(
                    $value,
                    [
                        'id'=>Exam::$counter,
                        'course_book_name'=>$item->course_book_name,
                        'exam_type'=>$item->exam_type,
                        'course_place_name'=>$item->place_name,

                        'course_name'=>$item->course_name,
                        'course_area_father_name'=>$item->course_area_father_name,
                        'course_area_name'=>$item->course_area_name,
                        // 'course_place_name'=>$item->course_place_name,
                        'students_count'=>$item->students_count,
                        // 'course_start_date'=>$item->course_start_date,
                        'course_start_date'=>$item->date? GetFormatedDate($item->date) . ' الساعة '. Carbon::parse($item->time)->isoFormat('h:mm a'):'',

                        'tools'=>$enterExamMarksButton
                    ]
                );
            }
//        dd($st_count);
            return [
                "draw" => $draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => (array)$value,
                "order" => $columns[$order]["db"]
            ];
        }
    }
    public function courseExamEnterMarks(newCourseExamRequest $request,Course $course){
        if($course->manyStudentsForPermissions->count()){
            $course->manyStudentsForPermissions->each(function($student) use ($request){
                if(isset($request->mark[$student->user_id])&&!empty($request->mark[$student->user_id])) {
                    $student->update(['mark' => $request->mark[$student->user_id]]);
                }
            });
            $course->update([
                'status' => 'بانتظار اعتماد الدرجات'
            ]);
            $course->exam->update([
                'status' => 2
            ]);
            return response()->json(['msg'=>'تم ادخال درجات الطلاب بنجاح','title'=>'تعديل','type'=>'success']);
        }else{
            return response()->json(['msg'=>'الدورة لا تحتوي على طلاب','title'=>'خطأ!','type'=>'danger']);
        }
    }
    public function approveMarks(Course $course){
        $course->update([
            'status' => 'منتهية'
        ]);
        return response()->json(['msg'=>'تم اعتماد درجات الطلاب بنجاح','title'=>'تعديل','type'=>'success']);
    }
    public function showCourseExamMarks(Course $course){
        return view('control_panel.exams.showCourseExamMarks',compact('course'));
    }
    public function edit(Exam $exam){
        dd($exam);
    }
    public function destroy(Exam $exam){
        dd($exam);
    }
    public function examsDeptManagerApprovement(Exam $exam){
//        dd($exam);
        if ($exam->status == 2) {
            $exam->update(['status' => 3]);
            return response()->json(['msg'=>'تم اعتماد درجات الطلاب بنجاح - رئيس قسم الاختبارات','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }elseif ($exam->status == 3){
            $exam->update(['status' => 2]);
            return response()->json(['msg'=>'تم التراجع عن اعتماد درجات الطلاب بنجاح - رئيس قسم الاختبارات','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }else{
            return response()->json(['msg'=>'لا يمكن التراجع عن اعتماد درجات الطلاب بنجاح - رئيس قسم الاختبارات','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }
    }
    public function qualityDeptManagerApprovement(Exam $exam){
//        dd($exam);
        if ($exam->status == 3) {
            $exam->update(['status' => 4]);
            return response()->json(['msg'=>'تم اعتماد درجات الطلاب بنجاح - مدير دائرة التخطيط والجودة','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }elseif ($exam->status == 4){
            $exam->update(['status' => 3]);
            return response()->json(['msg'=>'تم التراجع عن اعتماد درجات الطلاب بنجاح - مدير دائرة التخطيط والجودة','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }else{
            return response()->json(['msg'=>'لا يمكن التراجع عن اعتماد درجات الطلاب بنجاح - مدير دائرة التخطيط والجودة','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }
    }
    public function sunnaManagerApprovement(Exam $exam){
//        dd($exam);
        if ($exam->status == 4 || $exam->status == 2 || $exam->status == 3) {
            $exam->update(['status' => 5]);
            if($exam->course) {
                $exam->course->update(['status' => 'منتهية']);
            }
            return response()->json(['msg'=>'تم اعتماد درجات الطلاب بنجاح - مدير دائرة السنة النبوية','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }elseif ($exam->status == 5){
            $exam->update(['status' => 4]);
            return response()->json(['msg'=>'تم التراجع عن اعتماد درجات الطلاب بنجاح - مدير دائرة السنة النبوية','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }else{
            return response()->json(['msg'=>'لا يمكن التراجع عن اعتماد درجات الطلاب بنجاح - مدير دائرة السنة النبوية','title'=>'اعتماد !','type'=>'success','status'=>$exam->status]);
        }
    }
    public function exportExam(Exam $exam){
        $course = $exam->course;
//        foreach ($course->manyStudentsForPermissions as $manyStudentsForPermission){
//            echo $manyStudentsForPermission->user_name."||".$manyStudentsForPermission->mark.'<br>';
//        }
//        dd($course->manyStudentsForPermissions,$course->passedStudentCoursesForPermissions,$course->failedStudentCourses,$course->students);
        return view('control_panel.exams.exportExam',compact('exam','course'));

    }
}
