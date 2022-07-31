<?php

namespace App\Http\Controllers\controlPanel\Asaneed;

use App\Http\Controllers\Controller;
use App\Http\Requests\asaneedCourses\newAsaneedCourseRequest;
use App\Http\Requests\asaneedCourses\updateAsaneedCourseRequest;
use App\Models\Area;
use App\Models\AsaneedBook;
use App\Models\AsaneedCourse;
use App\Models\Place;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AsaneedCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::whereNull('area_id')->get();
        $statuses = '<select id="filterCoursesByStatus" class="form-control">
                        <option value="0">حالة المجلس</option>
                        <option value="انتظار الموافقة">انتظار الموافقة</option>
                        <option value="قائمة">قائمة</option>
                        <option value="منتهية">منتهية</option>
                        <option value="بانتظار اعتماد الدرجات">بانتظار اعتماد الدرجات</option>

                        <option value="معلقة">معلقة</option>
                   </select>';

        $books = AsaneedBook::all();
        return view('control_panel.asaneed.courses.basic.index',compact('areas','statuses','books'));
    }



    public function getData(Request $request)
    {
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'teacher_id', 'dt' => 1),
            array('db' => 'book_id', 'dt' => 2),
            array('db' => 'place_id', 'dt' => 3),
            array('db' => 'status', 'dt' => 4),
            array('db' => 'tools', 'dt' => 5),
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

        $place_area = $request->place_area ? $request->place_area : 0;
        $user = Auth::user();

        if($user->hasRole('مشرف عام')){
            $area_id =    $user->supervisor_area_id;
        }


        $value = array();

        if (!empty($search)) {
            $count = AsaneedCourse::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->search($search)
                ->book($book_id)
                ->teacher($teacher_id)
                ->placearea($place_area)
                ->count();
            $courses = AsaneedCourse::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->search($search)
                ->book($book_id)
                ->teacher($teacher_id)
                ->placearea($place_area)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = AsaneedCourse::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->book($book_id)
                ->teacher($teacher_id)
                ->placearea($place_area)
                ->count();
            $courses = AsaneedCourse::subarea($sub_area_id,$area_id)
                ->wherestatus($status)
                ->book($book_id)
                ->teacher($teacher_id)
                ->placearea($place_area)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
//        dd($courses);
        AsaneedCourse::$counter = $start;
        foreach ($courses as $index => $item) {
//            dd($item);
            array_push(
                $value,
                $item->course_display_data
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


    private function getAreaTeacher($area_id,$teacher_id){
        $moallems = User::department(7)->area($area_id)->get();
        $moallem_list = '<option value="0">اختر الشيخ</option>';
        foreach ($moallems as $moallem) {
            $selected = $teacher_id == $moallem->id ? 'selected' : '';
            $moallem_list .= '<option value="' . $moallem->id . '" '.$selected.'>' . $moallem->name . '</option>';
        }
        return $moallem_list;
    }

    public function getSubAreaAsaneedTeachers($area_id)
    {


        $result = array();
        $moallems = User::department(7)->area($area_id)->get();
        $moallem_list = '<option value="0">اختر الشيخ</option>';
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

    public function showLoadingAsaneedStudents(AsaneedCourse $asaneedCourse)
    {
        return view('control_panel.asaneed.courses.showLoadingAsaneedStudents', compact('asaneedCourse'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $asaneedCourse = new AsaneedCourse();
        $areas = Area::whereNull('area_id')->get();
        return view('control_panel.asaneed.courses.basic.create',compact('asaneedCourse','areas'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAsaneedCourseRequest $request)
    {
//        dd($request->all());
        $sanad = AsaneedCourse::create($request->all());
        // $sanad->exam()->create($request->all());

        return response()->json(['msg'=>'تم اضافة دورة أسانيد جديدة','title'=>'اضافة','type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AsaneedCourse $asaneedCourse)
    {
        $year = explode('-',$asaneedCourse->start_date)[0];
        $books = '';
        $areas = Area::whereNull('area_id')->get();
        $sub_areas = Area::where('area_id',$asaneedCourse->area_father_id)->get();
        $places = Place::where('area_id',$asaneedCourse->area_id)->get();
        $books_object = AsaneedBook::
            where('included_in_plan',$asaneedCourse->included_in_plan)
            ->where('year',(int)$year)
            ->get();
        foreach($books_object as $book){
            $selected = $book->id == $asaneedCourse->book_id ? 'selected' : '';
            $books .= '<option value="'.$book->id.'" '.$selected.'>'.$book->name.'</option>';
        }
        $place = Place::findOrFail($asaneedCourse->place_id);
        $teachers = $this->getAreaTeacher($place->area_father_id,$asaneedCourse->teacher_id);
        return view('control_panel.asaneed.courses.basic.update',compact('asaneedCourse','sub_areas','places','books','teachers','areas'));
    }
    public function getPlaceTeachersForCourses(Place $place,$teacher_id){
        return getPlaceTeachersForAsaneed($place->area_id,$teacher_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAsaneedCourseRequest $request, AsaneedCourse $asaneedCourse)
    {
        $asaneedCourse->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات الدورة بنجاح','title'=>'اضافة','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AsaneedCourse::destroy($id);
        return response()->json(['msg'=>'تم حذف بيانات الدورة بنجاح','title'=>'حذف','type'=>'success']);
    }
    public function changeCourseStatus(AsaneedCourse $asaneedCourse,$status,$note = ''){
        if(in_array($status,['انتظار الموافقة','قائمة','معلقة'])) {
            if($asaneedCourse->students->count()) {
                if($status == 'انتظار الموافقة'){
                    if($asaneedCourse->exam){
                        return response()->json(['msg'=>'لا يمكن تحديث الحالة ، يرجى حذف حجز الاختبار قبل التحديث.','title'=>'خطأ!','type'=>'danger']);
                    }
                }
                $asaneedCourse->update([
                    'status' => $status,
                    'note'=>$note
                ]);
                if ($status == "منتهية"){
//                    return view('control_panel.asaneed.courses.enterAsaneedCourseStudentsExamMarks',compact('asaneedCourse'));
                }
            }else{
                return response()->json(['msg'=>'يرجى ادخال طلاب لدورة الأسانيد والإجازات','title'=>'خطأ!','type'=>'danger']);
            }
            return response()->json(['msg'=>'تم تعديل بيانات دورة الاسانيد والإجازات بنجاح','title'=>'اضافة','type'=>'success']);
        }else{
            return response()->json(['msg'=>'يرجى ادخال قيمة صحيحة للحالة','title'=>'خطأ!','type'=>'danger']);
        }
    }
    public function storeStudentsMarks(Request $request){
//        dd($request->all());
    }
    public function getYearBooksForNewCourse($year,$type){
        $year = explode('-',$year)[0];
        $books_object = AsaneedBook::where('included_in_plan',$type)->where('year',(int)$year)->get();
//        dd($books_object);
        if($type == 'داخل الخطة') {
            $books = '<select class="form-control" name="book_id" id="book_id"><option value="">-- تحديد --</option>';
        }else{
            $books = '<select class="form-control" name="book_id" id="book_id" style="display:inline-block;width: 83%;"><option value="">-- تحديد --</option>';
        }
        foreach($books_object as $book){
//            $selected = $book->id == $course->book_id ? 'selected' : '';
            $books .= '<option value="'.$book->id.'" >'.$book->name.'</option>';
        }
        if($type == 'داخل الخطة') {
            return $books . '</select>';
        }else{
            return $books . '</select><a href="#!" class="btn btn-success" onclick="addNewCourseBook()"><i class="mdi mdi-plus"></i></a>';
        }
    }
    public function createOutOfPlanBook($year){
        $year = explode('-',$year)[0];
//        $years = '';
//        $current_year = Carbon::now()->format('Y');
//        for($i = 0;$i<6;$i++){
//            $year = ($current_year-5)+$i;
//            $years .='<option value="'.$year.'">'.$year.'</option>';
//        }
        $book = new AsaneedBook();
        $type = 'خارج الخطة';
        return view('control_panel.asaneed.books.basic.create', compact('book','year','type'));
    }
}
