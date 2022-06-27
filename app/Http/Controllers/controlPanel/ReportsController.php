<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Book;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function allReports(){
        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");
        $books = Book::where('year',$year)->get();
        $value = array();
        foreach ($books as $index => $item){
            // $new_item = $item->book_courses_plan_progress_display_data;
            $new_item = $item->students_reports_by_students_categories_row_data;
            // $new_item['id'] = $index+1;
            array_push($value , $new_item);
        }

        return view('control_panel.reports.all',compact('areas','value'));
    }
    public function getAnalysisView(Request $request){
        $analysis_type = $request->analysis_type;
        switch($analysis_type){
            case 'coursePlanProgress':{
                return $this->coursePlanProgressView($request);
            }
            case 'الأكثر إنجازًا':{
                return $this->mostaccomplished($request);
            }

            break;
        }
    }
    public function getAnalysisData(Request $request){
        $analysis_type = $request->analysis_type;
        switch($analysis_type){
            case 'coursePlanProgress':{
                return $this->coursePlanProgressData($request);
            }
            case 'الأكثر إنجازًا':{
                return $this->mostaccomplishedData($request);
            }

            break;
        }
    }
    /**
     * analysis Views functions
     */

    public function coursePlanProgressView(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $year = Carbon::parse($request->start_date)->format('Y');
        $books = Book::where('year',$year)->get();
        $filters =
            '<div class="col-md-3">
                <select class="form-control" onchange="changeBook(this)">
                    <option value="0">اختر الكتاب</option>';
                    foreach($books as $book) {
                        $filters .= '<option value="'.$book->id.'">'.$book->name.'</option>';
                    }
        $filters .= '</select>
            </div>';

         $bookss = Book::get();
         $value = array();
        foreach ($bookss as $index => $item){

            $new_item = $item->book_courses_plan_progress_display_data;
            // $new_item['id'] = $index+1;

            array_push($value , $new_item);
        }

//        return $value;
        $required_num = 0;
        $completed_num = 0;
        $completed_num_percentage = 0;
        $excess_num_percentage = 0;
        foreach ($value as  $item){
            $required_num = $required_num + $item['required_num'];
            $completed_num = $completed_num + $item['completed_num'];
            $completed_num_percentage = $completed_num_percentage + $item['completed_num_percentage'];
            $excess_num_percentage = $excess_num_percentage + $item['excess_num_percentage'];
        }
        return [
            'view'=>view('control_panel.reports.departments.courses.coursesPlanProgress',compact(
                'start_date','end_date','books','year',
                'required_num' , 'completed_num' , 'completed_num_percentage' , 'excess_num_percentage'
            ))->render(),
            'filters'=>$filters
        ];
    }
    public function mostaccomplished(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $year = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $moallems = User::department(2)->get();
        $filters =
            '<div class="col-md-3">
                <select class="form-control" onchange="changeMoallem(this)">
                    <option value="0">اسم المعلم</option>';
        foreach($moallems as $moallem) {
            $filters .= '<option value="'.$moallem->id.'">'.$moallem->name.'</option>';
        }
        $filters .= '</select>

            </div>';
        return [
            'view'=>view('control_panel.reports.departments.courses.mostAccimplished',compact('start_date','end_date','moallems','year'))->render(),
            'filters'=>$filters
        ];
    }
    /**
     * analysis data functions
     */

    public function coursePlanProgressData(Request $request){

//        { "mData": "book_name" },
//        { "mData": "graduated_categories" },
//        { "mData": "required_num" },
//        { "mData": "completed_num" },
//        { "mData": "completed_num_percentage" },
//        { "mData": "excess_num_percentage" }
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'student_category',      'dt' => 2 ),
            array( 'db' => 'required_students_number',      'dt' => 3 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $book_id = trim($request->book_id);

        $year = $request->year;
//        dd($year);
        $value = array();

        if(!empty($search)){
            $count = Book::where('year',$year)->search($search)
                ->book($book_id)
                ->count();
            $books = Book::where('year',$year)->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->book($book_id)
                ->get();
        } else {
            $count = Book::where('year',$year)->book($book_id)
                ->count();
            $books = Book::
                where('year',$year)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->book($book_id)
                ->get();
        }
        foreach ($books as $index => $item){
            $new_item = $item->book_courses_plan_progress_display_data;
            $new_item['id'] = $index+1;
            $new_item['completed_num_percentage'] = $new_item['completed_num_percentage'] . '%';
            $new_item['excess_num_percentage'] = $new_item['excess_num_percentage'] . '%';
            array_push($value , $new_item);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],

        ];
    }
    public function mostaccomplishedData(Request $request){

//        { "mData": "book_name" },
//        { "mData": "graduated_categories" },
//        { "mData": "required_num" },
//        { "mData": "completed_num" },
//        { "mData": "completed_num_percentage" },
//        { "mData": "excess_num_percentage" }
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'courses_num',      'dt' => 1 ),
            array( 'db' => 'graduated_num',      'dt' => 2 ),
            array( 'db' => 'most',      'dt' => 3 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $moallem_id = trim($request->moallem_id);

        $year = $request->year;
//        dd($year , $moallem_id);
        $value = array();

        if(!empty($search)){
            $count = Course::where('created_at','>=',$year)->search($search)
                ->where( 'teacher_id', $moallem_id)
                ->count();
            $courses = Course::where('created_at','>=',$year)->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->where( 'teacher_id', $moallem_id)
                ->get();
        } else {
            $count =Course::where('created_at','>=',$year)
                ->where( 'teacher_id', $moallem_id)
                ->count();
            $courses = Course::
                where('created_at','>=',$year)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->where( 'teacher_id', $moallem_id)
                ->get();
        }
//        return $books;

        $course_name = null;
        foreach ($courses as $index => $item){
            $courses_student = CourseStudent::where('user_id' , $moallem_id)->where('course_id' , $item->id)->where('mark' , '>=' , 60)->get();
            $count_success = count($courses_student);
            if (count($courses_student) > $count_success) {
                $count_success = count($courses_student);
                $course_name = Course::with('book')->find($item->id)->book->name;
            }

        }


        foreach ($courses as $index => $item){
            $courses_student = CourseStudent::where('user_id' , $moallem_id)->where('course_id' , $item->id)->where('mark' , '>=' , 60)->get();
            if ($index == 0) {
                array_push($value , [
                    'id' => $item->id,
                    'courses_num' => $count,
                    'graduated_num' => count($courses_student),
                    'most' => $course_name == null ? 'لا يوجد' : $course_name
                ]);
            }

        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
}
