<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Book;
use App\Models\AsaneedBook;
use App\Models\CourseStudent;
use App\Models\User;
use App\Models\CourseProject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use Illuminate\Support\Arr;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', 180); //3 minutes

    }
    public function allReports()
    {
        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");

        $course_project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $course_project = json_decode($course_project);
        $project_books_value = array();

        $in_plane_books = Book::where('year', $year)->where('included_in_plan','داخل الخطة')->get();
        $in_plane_books_value = array();

        $out_plane_books = Book::where('year', $year)->where('included_in_plan','خارج الخطة')->get();
        $out_plane_books_value = array();



            foreach ($in_plane_books as $index => $item) {
                if(! in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($in_plane_books_value, $new_item);
                }
            }

            foreach ($in_plane_books as $index => $item) {
                if( in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($project_books_value, $new_item);
                }
            }

            foreach ($out_plane_books as $index => $item) {
                if(! in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($out_plane_books_value, $new_item);
                }
            }


        return view('control_panel.reports.courseAreaReport', compact('areas', 'in_plane_books_value', 'in_plane_books','out_plane_books_value','project_books_value'));
    }

    public function allReviews()
    {


        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");
        $value = array();

        // if (Cache::has('reviews_acheivment_reports')) {
        //     $value = Cache::get('reviews_acheivment_reports');
        // } else {
            foreach ($areas as $index => $item) {
                $new_item = $item->all_reviews_row_data;
                array_push($value, $new_item);
            }
        //     Cache::put('reviews_acheivment_reports', $value,600);
        // }


        return view('control_panel.reports.departments.reviews.all', compact('areas', 'value'));
    }

    public function getReviewsAnalysisView(Request $request){
        $analysis_type = $request->analysis_type;
        switch ($analysis_type) {
            case 'courses': {
                    return $this->courseReviewDetailsView($request);
                }
            case 'asaneed': {
                    return $this->asaneedReviewDetailsView($request);
                }
                break;
        }
    }


    public function courseReviewDetailsView(Request $request){

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;

        $areas = Area::permissionssubarea($sub_area_id,$area_id)
        ->whereNull('area_id')->get();
        $value = array();

        foreach ($areas as $index => $item) {
            $new_item = $item->course_reviews_row_data;
            array_push($value, $new_item);
        }

        return [
            'view' => view('control_panel.reports.departments.reviews.courseReviewsDetails', compact('areas', 'value'))->render()
        ];

    }
    public function asaneedReviewDetailsView(Request $request){
        $areas = Area::whereNull('area_id')->get();
        $value = array();
        return [
            'view' => view('control_panel.reports.departments.reviews.asaneedReviewsDetails', compact('areas', 'value'))->render()
        ];

    }






    public function getAnalysisView(Request $request)
    {
        $analysis_type = $request->analysis_type;
        switch ($analysis_type) {
            case 'courseAreaPlanProgress':{
                return $this->courseAreaPlanProgressView($request);
            }
            case 'coursePlanProgress': {
                    return $this->coursePlanProgressView($request);
                }
            case 'mostAccomplished': {
                    $analysis_sub_type = $request->analysis_sub_type;
                    if ($analysis_sub_type == 'teachers') {
                        return $this->mostaccomplishedTeacherView($request);
                    } elseif ($analysis_sub_type == 'mosques') {
                        return $this->mostaccomplishedMosquesView($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostaccomplishedLocalAreaView($request);
                    }
                }

            case 'asaneedPlanProgress': {
                    return $this->asaneedPlanProgressView($request);
                }
            case 'asaneedMostAccomplished': {
                    $analysis_sub_type = $request->analysis_sub_type;
                    if ($analysis_sub_type == 'teachers') {
                        return $this->mostAsaneedAccomplishedTeacherView($request);
                    } elseif ($analysis_sub_type == 'mosques') {
                        return $this->mostaccomplishedMosquesView($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostaccomplishedLocalAreaView($request);
                    }
                }

                break;
        }
    }

    /**
     * analysis Views functions
     */
    public function courseAreaPlanProgressView(Request $request){

        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");

        $course_project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $course_project = json_decode($course_project);
        $project_books_value = array();

        $in_plane_books = Book::where('year', $year)->where('included_in_plan','داخل الخطة')->get();
        $in_plane_books_value = array();

        $out_plane_books = Book::where('year', $year)->where('included_in_plan','خارج الخطة')->get();
        $out_plane_books_value = array();




            foreach ($in_plane_books as $index => $item) {
                if(! in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($in_plane_books_value, $new_item);
                }
            }

            foreach ($in_plane_books as $index => $item) {
                if( in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($project_books_value, $new_item);
                }
            }

            foreach ($out_plane_books as $index => $item) {
                if(! in_array($item->id,$course_project)){
                    $new_item = $item->course_students_reports_by_area_row_data;
                    array_push($out_plane_books_value, $new_item);
                }
            }



        return [
            'view' => view('control_panel.reports.departments.courses.courseAreaPlanProgress', compact('areas', 'in_plane_books_value', 'in_plane_books','out_plane_books_value','project_books_value'))->render()
            // ,
            // 'filters'=>$filters
        ];
    }


    public function asaneedPlanProgressView(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $book_id = $request->book_id;

        $year = Carbon::parse($request->start_date)->format('Y');

        if ($book_id) {
            $books = AsaneedBook::where('id', $book_id)->get();
        } else {
            $books = AsaneedBook::where('year', $year)->get();
        }
        $value = array();
        if (Cache::has('asaneed_acheivment_reports')) {
            $value = Cache::get('asaneed_acheivment_reports');
        } else {
            foreach ($books as $index => $item) {
                $new_item = $item->asaneed_students_reports_by_students_categories_row_data;
                array_push($value, $new_item);
            }
            Cache::put('asaneed_acheivment_reports', $value,600);
        }

        return [
            'view' => view('control_panel.reports.coursesPlanProgress', compact(
                'value'
            ))->render()
            // ,
            // 'filters'=>$filters
        ];
    }

    public function coursePlanProgressView(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $book_id = $request->book_id;

        $year = Carbon::parse($request->start_date)->format('Y');

        if ($book_id) {
            $books = Book::where('id', $book_id)->get();
        } else {
            $books = Book::where('year', $year)->get();
        }
        $value = array();

            foreach ($books as $index => $item) {
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($value, $new_item);
            }


        return [
            'view' => view('control_panel.reports.coursesPlanProgress', compact(
                'value'
            ))->render()
            // ,
            // 'filters'=>$filters
        ];
    }


    public function mostaccomplishedTeacherView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.courses.mostAccomplishedCourseTeacher')->render(),
        ];
    }

    public function mostAsaneedAccomplishedTeacherView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.asaneed.mostAccomplishedCourseTeacher')->render(),
        ];
    }


    public function mostaccomplishedMosquesView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.courses.mostAccomplishedCourseMosques')->render(),
        ];
    }

    public function mostaccomplishedLocalAreaView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.courses.mostAccomplishedCourseLocalArea')->render(),
        ];
    }


















    public function getAnalysisData(Request $request)
    {
        $analysis_type = $request->analysis_type;

        switch ($analysis_type) {
            case 'coursePlanProgress': {
                    return $this->coursePlanProgressData($request);
                }
            case 'mostAccomplished': {


                    $analysis_sub_type = $request->analysis_sub_type;
                    if ($analysis_sub_type == 'teachers') {
                        return $this->mostaccomplishedTeacherData($request);
                    } elseif ($analysis_sub_type == 'mosques') {
                        return $this->mostaccomplishedMosquesData($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostaccomplishedLocalAreaData($request);
                    }

                }

            case 'asaneedMostAccomplished': {
                    $analysis_sub_type = $request->analysis_sub_type;
                    if ($analysis_sub_type == 'teachers') {
                        return $this->mostAsaneedAccomplishedTeacherData($request);
                    } elseif ($analysis_sub_type == 'mosques') {
                        return $this->mostaccomplishedMosquesView($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostaccomplishedLocalAreaView($request);
                    }
                }

                break;
        }
    }


    /**
     * analysis data functions
     */


    public function mostaccomplishedLocalAreaData(Request $request)
    {

        $columns = array(
            array('db' => 'id',        'dt' => 0),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 1;

        $teacher_id = (int)$request->teacher_id ? (int)$request->teacher_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;

        $start_date = (int)$request->start_date ? (int)$request->start_date : 0;
        $end_date = (int)$request->end_date ? (int)$request->end_date : 0;




        $value = array();




        $count = Area::whereNotNull('area_id')
                        ->permissionssubarea($sub_area_id,$area_id)
                        ->teacher($teacher_id)
                        ->book($book_id)
                        ->place($place_id)
                        ->count();
        $sub_areas = Area::whereNotNull('area_id')
                    ->permissionssubarea($sub_area_id,$area_id)
                    ->teacher($teacher_id)
                    ->book($book_id)
                    ->place($place_id)
                    ->get();



        foreach ($sub_areas as $index => $item) {
            array_push($value, $item->most_accomplished_course_row_data);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }



    public function mostaccomplishedMosquesData(Request $request)
    {

        $columns = array(
            array('db' => 'id',        'dt' => 0),
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
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;

        $start_date = (int)$request->start_date ? (int)$request->start_date : 0;
        $end_date = (int)$request->end_date ? (int)$request->end_date : 0;




        $value = array();

        if (!empty($search)) {
            $count = Place::select('id', 'name', 'area_id')
                ->search($search)
                ->has('courses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->count();
            $places = Place::select('id', 'name', 'area_id')
                ->search($search)
                ->has('courses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Place::select('id', 'name', 'area_id')->teacher($teacher_id)
                ->book($book_id)
                ->has('courses')
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)->count();
            $places = Place::select('id', 'name', 'area_id')
                ->teacher($teacher_id)
                ->book($book_id)
                ->has('courses')
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }


        foreach ($places as $index => $item) {
            $row = $item->most_accomplished_course_row_data;
                array_push($value, $row);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }


    public function mostaccomplishedTeacherData(Request $request)
    {

        $columns = array(
            array('db' => 'id',        'dt' => 0),
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
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;

        $start_date = (int)$request->start_date ? (int)$request->start_date : 0;
        $end_date = (int)$request->end_date ? (int)$request->end_date : 0;





        $value = array();

        if (!empty($search)) {
            $count = User::subarea($sub_area_id, $area_id)
                ->search($search)
                ->department(2)
                ->has('teacherCourses')
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::subarea($sub_area_id, $area_id)
                ->search($search)
                ->department(2)
                ->has('teacherCourses')
                ->book($book_id)
                ->place($place_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::subarea($sub_area_id, $area_id)
                ->department(2)
                ->has('teacherCourses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::subarea($sub_area_id, $area_id)
                ->department(2)
                ->has('teacherCourses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->limit($length)->offset($start)
                ->get();
        }
        User::$counter = $start;

        foreach ($teachers as $index => $item) {
            array_push($value, $item->most_accomplished_course_row_data);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }


    public function mostAsaneedAccomplishedTeacherData(Request $request)
    {

        $columns = array(
            array('db' => 'id',        'dt' => 0),
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
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;

        $start_date = (int)$request->start_date ? (int)$request->start_date : 0;
        $end_date = (int)$request->end_date ? (int)$request->end_date : 0;





        $value = array();

        if (!empty($search)) {
            $count = User::subarea($sub_area_id, $area_id)
                ->search($search)
                ->department(7)
                ->has('teacherAsaneedCourses')
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::subarea($sub_area_id, $area_id)
                ->search($search)
                ->department(7)
                ->has('teacherAsaneedCourses')
                ->book($book_id)
                ->place($place_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::subarea($sub_area_id, $area_id)
                ->department(7)
                ->has('teacherAsaneedCourses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::distinct()->subarea($sub_area_id, $area_id)
                ->department(7)
                ->has('teacherAsaneedCourses')
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->limit($length)->offset($start)
                ->get();
        }
        User::$counter = $start;

        foreach ($teachers as $index => $item) {
            array_push($value, $item->most_accomplished_asaneed_row_data);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }






    public function coursePlanProgressData(Request $request)
    {

        //        { "mData": "book_name" },
        //        { "mData": "graduated_categories" },
        //        { "mData": "required_num" },
        //        { "mData": "completed_num" },
        //        { "mData": "completed_num_percentage" },
        //        { "mData": "excess_num_percentage" }
        $columns = array(
            array('db' => 'id',        'dt' => 0),
            array('db' => 'name',      'dt' => 1),
            array('db' => 'student_category',      'dt' => 2),
            array('db' => 'required_students_number',      'dt' => 3),
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

        if (!empty($search)) {
            $count = Book::where('year', $year)->search($search)
                ->book($book_id)
                ->count();
            $books = Book::where('year', $year)->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->book($book_id)
                ->get();
        } else {
            $count = Book::where('year', $year)->book($book_id)
                ->count();
            $books = Book::where('year', $year)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->book($book_id)
                ->get();
        }
        foreach ($books as $index => $item) {
            $new_item = $item->book_courses_plan_progress_display_data;
            $new_item['id'] = $index + 1;
            $new_item['completed_num_percentage'] = $new_item['completed_num_percentage'] . '%';
            $new_item['excess_num_percentage'] = $new_item['excess_num_percentage'] . '%';
            array_push($value, $new_item);
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
