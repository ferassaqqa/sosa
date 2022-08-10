<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Areas\updateAreaRequest;
use App\Http\Requests\controlPanel\courses\newCourseRequest;
use App\Http\Requests\controlPanel\courses\updateCourseRequest;
use App\Models\Area;
use App\Models\Book;
use App\Models\BookPlan;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Place;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Course $course)
    {

        return view('control_panel.courses.basic.details',compact('course'));
    }

    public function addReservationOrder(Course $course){

        if($course->exam){   
            
            // dd($course->exam);
                return response()->json(['msg'=>'يوجد طلب مسبق لهذه الدورة','title'=>'خطأ','type'=>'info']);
        }else{
            $students_count = $course->students->count();
            if($students_count >= 10 && !$course->exam){
                $course->exam()->create();
                return response()->json(['msg'=>'تم ارسال الطلب بنجاح','title'=>'اضافة','type'=>'success']);

            }else{            
                return response()->json(['msg'=>'يجب ان تحتوى الدورة على 10 طلاب على الاقل ليتم حجز موعد للدورة','title'=>'خطأ','type'=>'danger']);
            }
        }
    }


    public function getSubAreaTeachers($area_id)
    {


        $result = array();
        $moallems = User::department(2)->area($area_id)->withoutGlobalScope('relatedUsers')->get();
        // $moallems = User::department(2)->subarea($area_id,0)->get();
        $moallem_list = '<option value="0">اختر المعلم</option>';
        foreach ($moallems as $moallem) {
            $moallem_list .= '<option value="'.$moallem->id.'">'.$moallem->name.'</option>';
        }

        $result[] = $moallem_list;

        $places = Place::where('area_id',$area_id)->get();
        $place_list = '<option value="0">اختر المكان</option>';
        foreach ($places as $place) {
            $place_list .= '<option value="'.$place->id.'">'.$place->name.'</option>';
        }

        $result[] = $place_list;

        return $result;
    }

    public function index()
    {
        checkPermissionHelper('الدورات العلمية');
        $areas = Area::whereNull('area_id')->get();
        $books = Book::where('year',Carbon::now()->format('Y'))->get();

        // $places = Place::whereNull('area_id')->get();


        $statuses = '<select id="filterCoursesByStatus" onchange="updateDateTable()" class="form-control">
                        <option value="0">الكل</option>
                        <option value="انتظار الموافقة">انتظار الموافقة</option>
                        <option value="قائمة">قائمة</option>
                        <option value="منتهية">منتهية</option>
                        <option value="معلقة">معلقة</option>
                   </select>';
        return view('control_panel.courses.basic.index',compact('books','areas','statuses'));
    }
    public function getData(Request $request)
    {
        checkPermissionHelper('الدورات العلمية');
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'teacher_id', 'dt' => 1),
            array('db' => 'book_id', 'dt' => 2),
            array('db' => 'place_id', 'dt' => 3),
            array('db' => 'status', 'dt' => 4),
            array('db' => 'status', 'dt' => 5),
            array('db' => 'status', 'dt' => 6),
//            array('db' => 'tools', 'dt' => 5),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $teacher_id = (int)$request->teacher_id ? (int)$request->teacher_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;
        $status = $request->status ? $request->status : 0;

        $export_status = (int)$request->export_status ? (int)$request->export_status : 0;

        $place_area = $request->place_area ? $request->place_area : 0;




        $columns[$order]["db"] = $columns[$order]["db"]=='id' ? 'updated_at' : $columns[$order]["db"] ;
        $direction = $columns[$order]["db"]=='created_at' ? 'DESC' : $direction ;

        $value = array();



        if (!empty($search)) {
            $count = Course::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->exportstatus($export_status)
                ->search($search)
                ->teacher($teacher_id)
                ->book($book_id)
                ->placearea($place_area)
                ->orderBy('id', 'DESC')
                ->count();
            $courses = Course::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->exportstatus($export_status)
                ->search($search)
                ->teacher($teacher_id)
                ->book($book_id)
                ->placearea($place_area)
                ->orderBy('id', 'DESC')
                ->limit($length)->offset($start)
                // ->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Course::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->exportstatus($export_status)
                ->teacher($teacher_id)
                ->book($book_id)
                ->placearea($place_area)
                ->orderBy('id', 'DESC')
                ->count();
            $courses = Course::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->exportstatus($export_status)
                ->limit($length)->offset($start)
                // ->orderBy($columns[$order]["db"], $direction)
                ->teacher($teacher_id)
                ->book($book_id)
                ->orderBy('id', 'DESC')
                ->placearea($place_area)
                ->get();
        }
//        dd($courses);

//        $courses = Course::all();
        Course::$counter = $start;
        $st_count = 0;
        foreach ($courses as $index => $item) {
//            dd($item);
            // $st_count += $item->students_count;
            array_push(
                $value,
                $item->course_display_data
            );
        }

        $moallems_count = User::department(2)->subarea($sub_area_id,$area_id)->coursebookorteacher($teacher_id,$book_id,$place_area)->course($status)->count();
        $course_students_count = CourseStudent::book($book_id)->exportstatus($export_status)->coursebookorteacher($teacher_id,$book_id,$place_area)->subarea($sub_area_id,$area_id)->course($status)->count();


        $passed_students =  CourseStudent::book($book_id)->exportstatus($export_status)->coursebookorteacher($teacher_id,$book_id,$place_area)->subarea($sub_area_id,$area_id)->course('منتهية')->whereBetween('mark', [60, 101])->count();
        $failed_students = CourseStudent::book($book_id)->exportstatus($export_status)->coursebookorteacher($teacher_id,$book_id,$place_area)->subarea($sub_area_id,$area_id)->course('منتهية')->whereBetween('mark', [0, 59])->count();


        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],
            'statistics' => '
                            <td>'.$count.'</td>
                            <td>'.$course_students_count.'</td>
                            <td>'.$passed_students.'</td>
                            <td>'.$failed_students.'</td>
                            <td>'.$moallems_count.'</td>'
        ];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        checkPermissionHelper('اضافة دورة علمية');
        $course = new Course();
        $areas = Area::whereNull('area_id')->get();
        $course_number = Carbon::now()->format('Y').'/'.Course::count();
        return view('control_panel.courses.basic.create',compact('course','areas', 'course_number'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCourseRequest $request)
    {
        checkPermissionHelper('اضافة دورة علمية');
       $course = Course::create($request->all());

        // $course->exam()->create($request->all());
        return response()->json(['msg'=>'تم اضافة دورة جديدة','title'=>'اضافة','type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        checkPermissionHelper('تصفح بيانات دورة العلمية');
    }


    private function getAreaTeacher($area_id,$teacher_id){
        $moallems = User::department(2)->area($area_id)->get();
        $moallem_list = '<option value="0">اختر المعلم</option>';
        foreach ($moallems as $moallem) {
            $selected = $teacher_id == $moallem->id ? 'selected' : '';
            $moallem_list .= '<option value="' . $moallem->id . '" '.$selected.'>' . $moallem->name . '</option>';
        }
        return $moallem_list;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        checkPermissionHelper('تعديل بيانات الدورات العلمية');
        $year = explode('-',$course->start_date)[0];
        $books = '';
        $areas = Area::whereNull('area_id')->get();
        $sub_areas = Area::where('area_id',$course->area_father_id)->get();
        $places = Place::where('area_id',$course->area_id)->get();
        $books_object = Book::
            where('department',2)
            ->where('included_in_plan',$course->included_in_plan)
            ->where('year',(int)$year)
            ->get();
        foreach($books_object as $book){
            $selected = $book->id == $course->book_id ? 'selected' : '';
            $books .= '<option value="'.$book->id.'" '.$selected.'>'.$book->name.'</option>';
        }
        $place = Place::findOrFail($course->place_id);
        // $teachers = getPlaceTeachersForCourses($place->area_id,$course->teacher_id);

        $teachers = $this->getAreaTeacher($place->area_father_id,$course->teacher_id);
        return view('control_panel.courses.basic.update',compact('course','sub_areas','places','books','teachers','areas'));
    }
    public function getPlaceTeachersForCourses($place_id,$teacher_id){
        $place = Place::withoutGlobalScope('relatedPlaces')->find($place_id);
        return getPlaceTeachersForCourses($place->area_id,$teacher_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateCourseRequest $request, Course $course)
    {
        checkPermissionHelper('تعديل بيانات الدورات العلمية');
        $course->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات الدورة بنجاح','title'=>'اضافة','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        checkPermissionHelper('حذف بيانات الدورات العلمية');
        $course->delete();
        if($course->exam){
            $course->exam->delete();
        }
        CourseStudent::where('course_id',$course->id)->delete();
        $alert = "تم حذف دورة ".$course->book_name." للمعلم ".$course->teacher_name;
        return response()->json(['msg'=>$alert,'title'=>'حذف','type'=>'success']);
    }
    public function changeCourseStatus(Course $course,$status,$note = ''){
        checkPermissionHelper('تغيير حالة الدورات العلمية');
        if(in_array($status,['انتظار الموافقة','قائمة','معلقة'])) {
//            if($course->students->count()) {
                if($status == 'انتظار الموافقة'){
                    if($course->exam){
                        return response()->json(['msg'=>'لا يمكن تحديث الحالة ، يرجى حذف حجز الاختبار قبل التحديث.','title'=>'خطأ!','type'=>'danger']);
                    }
                }
                $course->update([
                    'status' => $status,
                    'note'=>$note
                ]);
                if ($status == "منتهية"){
//                    return view('control_panel.courses.enterCourseStudentsExamMarks',compact('course'));
                }
//            }else{
//                return response()->json(['msg'=>'يرجى ادخال طلاب للدورة','title'=>'خطأ!','type'=>'danger']);
//            }
            return response()->json(['msg'=>'تم تعديل بيانات الدورة بنجاح','title'=>'اضافة','type'=>'success']);
        }else{
            return response()->json(['msg'=>'يرجى ادخال قيمة صحيحة للحالة','title'=>'خطأ!','type'=>'danger']);
        }
    }
    public function storeStudentsMarks(Request $request){
//        dd($request->all());
    }
    public function getYearBooksForNewCourse($year){
        $year = explode('-',$year)[0];
        $books_object = Book::department(2)->where('year',(int)$year)->get();
        $books = '<select class="form-control" name="book_id" id="book_id"><option value="">-- تحديد --</option>';
        foreach($books_object as $book){
            $books .= '<option value="'.$book->id.'" >'.$book->name.'</option>';
        }
        return $books . '</select>';
    }
    public function createOutOfPlanBook($year){
        $year = explode('-',$year)[0];
//        $years = '';
//        $current_year = Carbon::now()->format('Y');
//        for($i = 0;$i<6;$i++){
//            $year = ($current_year-5)+$i;
//            $years .='<option value="'.$year.'">'.$year.'</option>';
//        }
        $book = new Book();
        $type = 'خارج الخطة';
        $department = 2;
        return view('control_panel.books.basic.create', compact('book', 'department','year','type'));
    }
}
