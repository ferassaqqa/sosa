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
use Illuminate\Support\Facades\Cache;

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



        if (Cache::has('course_acheivment_reports')) {
            $value = Cache::get('course_acheivment_reports');
        }else{
            foreach ($books as $index => $item){
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($value , $new_item);
            }
            Cache::put('course_acheivment_reports', $value);
        }


        return view('control_panel.reports.all',compact('areas','value','books'));
    }
    public function getAnalysisView(Request $request){
        $analysis_type = $request->analysis_type;
        switch($analysis_type){
            case 'coursePlanProgress':{
                return $this->coursePlanProgressView($request);
            }
            case 'mostAccomplished':{
                return $this->mostaccomplishedView($request);
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
        $book_id = $request->book_id;

        $year = Carbon::parse($request->start_date)->format('Y');

        if($book_id){
             $books = Book::where('id',$book_id)->get();
        }else{
            $books = Book::where('year',$year)->get();

        }
         $value = array();
        // if (Cache::has('course_acheivment_reports')) {
        //     $value = Cache::get('course_acheivment_reports');
        // }else{
            foreach ($books as $index => $item){
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($value , $new_item);
            }
        //     Cache::put('course_acheivment_reports', $value);
        // }

        return [
            'view'=>view('control_panel.reports.coursesPlanProgress',compact(
                'value'
            ))->render()
            // ,
            // 'filters'=>$filters
        ];
    }


    public function mostaccomplishedView(Request $request){

        return [
            'view'=>view('control_panel.reports.departments.courses.mostAccomplishedCourse')->render(),
        ];
    }



















    public function getAnalysisData(Request $request){
        $analysis_type = $request->analysis_type;
        switch($analysis_type){
            case 'coursePlanProgress':{
                return $this->coursePlanProgressData($request);
            }
            case 'mostAccomplished':{
                return $this->mostaccomplishedData($request);
            }

            break;
        }
    }


        /**
     * analysis data functions
     */


    public function mostaccomplishedData(Request $request){

                        $columns = array(
                            array( 'db' => 'id',        'dt' => 0 ),
                            // array( 'db' => 'teacher_name',      'dt' => 1 ),
                            // array( 'db' => 'total_courses',      'dt' => 2 ),
                            // array( 'db' => 'total_passed_students',      'dt' => 3 ),
                            // array( 'db' => 'most_accomplished_course',      'dt' => 4 ),

                        );

                        $draw = (int)$request->draw;
                        $start = (int)$request->start;
                        $length = (int)$request->length;
                        $order = $request->order[0]["column"];
                        $direction = $request->order[0]["dir"];
                        $search = trim($request->search["value"]);

                        $sub_area_id =(int)$request->sub_area_id ?(int)$request->sub_area_id : 0;
                        $area_id =(int)$request->area_id ?(int)$request->area_id : 0;

                        $teacher_id =(int)$request->teacher_id ?(int)$request->teacher_id : 0;
                        $book_id =(int)$request->book_id ?(int)$request->book_id : 0;
                        $place_id =(int)$request->place_id ?(int)$request->place_id : 0;
                        $start_date =(int)$request->start_date ?(int)$request->start_date : 0;
                        $end_date =(int)$request->end_date ?(int)$request->end_date : 0;




                        $value = array();

                        // if(!empty($search)){
                        //     $count = User::subarea($sub_area_id,$area_id)
                        //         ->search($search)
                        //         ->department(2)
                        //         ->count();
                        //     $users = User::subarea($sub_area_id,$area_id)
                        //         ->search($search)
                        //         ->department(2)
                        //         ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                        //         ->get();
                        // } else {
                            $count = User::subarea($sub_area_id,$area_id)
                                ->department(2)
                                ->count();
                            $teachers = User::subarea($sub_area_id,$area_id)
                                ->department(2)
                                ->limit($length)->offset($start)
                                ->get();
                        // }
                        User::$counter = $start;

                        foreach ($teachers as $index => $item){
                            array_push($value , $item->most_accomplished_course_row_data);
                        }

                        return [
                            "draw" => $draw,
                            "recordsTotal" => $count,
                            "recordsFiltered" => $count,
                            "data" => (array)$value,
                            "order" => $columns[$order]["db"]
                        ];
            }









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


}
