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
        ini_set('max_execution_time', 360); //6 minutes

    }
    public function allReports()
    {
        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");

        // $course_project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        // $course_project = json_decode($course_project);
        // $project_books_value = array();

        $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->get();
        // $in_plane_books_value = array();

        // $out_plane_books = Book::where('year', $year)->where('required_students_number' ,'>',0)->where('included_in_plan','خارج الخطة')->get();
        // $out_plane_books_value = array();



        // foreach ($in_plane_books as $index => $item) {
        //     if(! in_array($item->id,$course_project)){
        //         $new_item = $item->course_students_reports_by_area_row_data;
        //         array_push($in_plane_books_value, $new_item);
        //     }
        // }

        // foreach ($in_plane_books as $index => $item) {
        //     if( in_array($item->id,$course_project)){
        //         $new_item = $item->course_students_reports_by_area_row_data;
        //         array_push($project_books_value, $new_item);
        //     }
        // }

        // foreach ($out_plane_books as $index => $item) {
        //     if(! in_array($item->id,$course_project)){
        //         $new_item = $item->course_students_reports_by_area_row_data;
        //         array_push($out_plane_books_value, $new_item);
        //     }
        // }



        return view('control_panel.reports.courseAreaReport', compact('areas', 'in_plane_books'));
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

    public function getReviewsAnalysisView(Request $request)
    {
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

    private function getOrderLabel($key)
    {
        $rate = '';
        switch ($key) {
            case 0:
                # code...
                $rate = 'الاول';
                break;
            case 1:
                # code...
                $rate = 'الثاني';

                break;
            case 2:
                # code...
                $rate = 'الثالث';

                break;
            case 3:
                # code...
                $rate = 'الرابع';

                break;
            case 4:
                # code...
                $rate = 'الخامس';

                break;
            case 5:
                # code...
                $rate = 'السادس';

                break;
            case 6:
                # code...
                $rate = 'السابع';

                break;
        }

        return $rate;
    }


    public function courseReviewDetailsView(Request $request)
    {

        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;

        $areas = Area::permissionssubarea($sub_area_id, $area_id)
            ->whereNull('area_id')->get();
        $value = array();
        $result_review = array();

        foreach ($areas as $index => $item) {
            $new_item = $item->course_reviews_row_data;
            $result_review[] = $new_item;
        }

        foreach ($result_review as $key => $row) {
            $name[$key]  = $row['name'];
            $percentage_38[$key] = $row['percentage_38'];
            $percentage_50[$key] = $row['percentage_50'];
            $percentage_total[$key] = $row['percentage_total'];
            $id[$key] = $row['id'];
        }

        array_multisort($percentage_total, SORT_DESC, $result_review);
        foreach ($result_review as $key => $row) {
            $scores[] = $row['percentage_total'];
        }
        $duplicates = array_unique( array_diff_assoc( $scores, array_unique( $scores ) ) );
        

        foreach ($result_review as $key1 => $row) {
            $label = $this->getOrderLabel($key1);
            $key = 0;
            if (array_key_exists($key1, $duplicates)) {
                $key = array_search ($row['percentage_total'], $duplicates);
                $label =$this->getOrderLabel($key-1).' مكرر'; 
            }
            // if(in_array($row['percentage_total'],$scores)){
            //     $key = array_search ($row['percentage_total'], $duplicates);
            //     $label =$this->getOrderLabel($key-1).' مكرر'; 
            // }

           

            $item = '                        
            <tr >
                <td>' . $key . '</td>
                <td>' . $row['name'] . '</td>
        
                <td>' . $row['percentage_38'] . '%</td>
                <td>5%</td>
                <td>2%</td>
                <td>3%</td>
                <td><b>' . $row['percentage_50'] . '%</b></td>
        
                <td><b>' . $row['percentage_total'] . '%</b></td>
                <td>' . $label . '</td>
                <td></td>
            </tr>
                
            ';

            array_push($value, $item);
        }


        // dd($result_review);

        return [
            'view' => view('control_panel.reports.departments.reviews.courseReviewsDetails', compact('areas', 'value'))->render()
        ];
    }
    public function asaneedReviewDetailsView(Request $request)
    {
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;

        $areas = Area::permissionssubarea($sub_area_id, $area_id)
            ->whereNull('area_id')->get();
        $value = array();
        $result_review = array();

        // foreach ($areas as $index => $item) {
        //     $new_item = $item->asaneed_reviews_row_data;
        //     array_push($value, $new_item);
        // }


        foreach ($areas as $index => $item) {
            $new_item = $item->asaneed_reviews_row_data;
            $result_review[] = $new_item;
        }
        foreach ($result_review as $key => $row) {
            $name[$key]  = $row['name'];
            $percentage_38[$key] = $row['percentage_38'];
            $percentage_50[$key] = $row['percentage_50'];
            $percentage_total[$key] = $row['percentage_total'];
            $id[$key] = $row['id'];
        }

        array_multisort($percentage_total, SORT_DESC, $result_review);
        foreach ($result_review as $key => $row) {
            $scores[] = $row['percentage_total'];
        }
        $duplicates = array_unique( array_diff_assoc( $scores, array_unique( $scores ) ) );

        foreach ($result_review as $key => $row) {
            $label = $this->getOrderLabel($key);
            // if (array_key_exists($key, $duplicates)) {
            //     $label =$this->getOrderLabel($key-1).' مكرر'; 
            // }
            if(in_array($row['percentage_total'],$scores)){
                $key = array_search ($row['percentage_total'], $duplicates);
                $label =$this->getOrderLabel($key-1).' مكرر'; 
            }

           

            $item = '                        
            <tr >
                <td>' . $key . '</td>
                <td>' . $row['name'] . '</td>
        
                <td>' . $row['percentage_38'] . '%</td>
                <td>5%</td>
                <td>2%</td>
                <td>3%</td>
                <td><b>' . $row['percentage_50'] . '%</b></td>
        
                <td><b>' . $row['percentage_total'] . '%</b></td>
                <td>' . $label . '</td>
                <td></td>
            </tr>
                
            ';

            array_push($value, $item);
        }

        return [
            'view' => view('control_panel.reports.departments.reviews.asaneedReviewsDetails', compact('areas', 'value'))->render()
        ];
    }






    public function getAnalysisView(Request $request)
    {
        $analysis_type = $request->analysis_type;

        switch ($analysis_type) {
            case 'courseAreaPlanProgress': {
                    return $this->courseAreaPlanProgressView($request);
                    break;
                }
            case 'coursePlanProgress': {
                    return $this->coursePlanProgressView($request);
                    break;
                }
            case 'safwaProgram': {
                    return $this->accomplishedSafwaProgramStudentsView($request);
                    break;
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
                    break;
                }

            case 'asaneedAreaPlanProgress': {
                    return $this->asaneedAreaPlanProgressView($request);
                    break;
                }
            case 'asaneedPlanProgress': {
                    return $this->asaneedPlanProgressView($request);
                    break;
                }
            case 'asaneedMostAccomplished': {
                    $analysis_sub_type = $request->analysis_sub_type;
                    if ($analysis_sub_type == 'teachers') {
                        return $this->mostAsaneedAccomplishedTeacherView($request);
                    } elseif ($analysis_sub_type == 'mosques') {
                        return $this->mostAsaneedAccomplishedMosquesView($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostAsaneedAccomplishedLocalAreaView($request);
                    }
                    break;
                }
        }
    }


    public function mostAsaneedAccomplishedTeacherView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.asaneed.mostAccomplishedCourseTeacher')->render(),
        ];
    }

    public function mostAsaneedAccomplishedMosquesView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.asaneed.mostAccomplishedCourseMosques')->render(),
        ];
    }

    public function mostAsaneedAccomplishedLocalAreaView(Request $request)
    {
        return [
            'view' => view('control_panel.reports.departments.asaneed.mostAccomplishedCourseLocalArea')->render(),
        ];
    }

    /**
     * analysis Views functions
     */
    private function asaneedAreaPlanProgressView(Request $request)
    {

        $areas = Area::whereNull('area_id')->get();
        $asaneed_plan_books = AsaneedBook::whereNotNull('author')->get();
        $value = array();


        foreach ($asaneed_plan_books as $index => $item) {

            $new_item = $item->asaneed_students_reports_by_area_row_data;
            array_push($value, $new_item);
        }



        return [
            'view' => view('control_panel.reports.departments.asaneed.asaneedAreaPlanProgress', compact('value', 'areas'))->render()
        ];
    }

    private function courseAreaPlanProgressView(Request $request)
    {

        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");

        $course_project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $course_project = json_decode($course_project);
        $project_books_value = array();

        $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();
        $in_plane_books_value = array();

        $out_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        $out_plane_books_value = array();




        foreach ($in_plane_books as $index => $item) {
            if (!in_array($item->id, $course_project)) {
                $new_item = $item->course_students_reports_by_area_row_data;
                array_push($in_plane_books_value, $new_item);
            }
        }

        foreach ($in_plane_books as $index => $item) {
            if (in_array($item->id, $course_project)) {
                $new_item = $item->course_students_reports_by_area_row_data;
                array_push($project_books_value, $new_item);
            }
        }

        foreach ($out_plane_books as $index => $item) {
            if (!in_array($item->id, $course_project)) {
                $new_item = $item->course_students_reports_by_area_row_data;
                array_push($out_plane_books_value, $new_item);
            }
        }



        return [
            'view' => view('control_panel.reports.departments.courses.courseAreaPlanProgress', compact('areas', 'in_plane_books_value', 'in_plane_books', 'out_plane_books_value', 'project_books_value'))->render()
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
            Cache::put('asaneed_acheivment_reports', $value, 600);
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

        $in_plane_books_value = array();
        $out_plane_books_value = array();

        $course_project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $course_project = json_decode($course_project);
        $project_books_value = array();


        if ($book_id) {

            $in_plane_books = Book::where('year', $year)->where('id', $book_id)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();

            $out_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        } else {
            // $books = Book::where('year', $year)->where('required_students_number' ,'>',0)->get();

            $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();

            $out_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        }

        foreach ($in_plane_books as $index => $item) {
            if (in_array($item->id, $course_project)) {
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($project_books_value, $new_item);
            }
        }

        foreach ($in_plane_books as $index => $item) {
            if (!in_array($item->id, $course_project)) {
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($in_plane_books_value, $new_item);
            }
        }


        foreach ($out_plane_books as $index => $item) {
            if (!in_array($item->id, $course_project)) {
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($out_plane_books_value, $new_item);
            }
        }


        return [
            'view' => view('control_panel.reports.coursesPlanProgress', compact(
                'in_plane_books_value',
                'out_plane_books_value',
                'project_books_value'
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

    public function accomplishedSafwaProgramStudentsView(Request $request)
    {

        return [
            'view' => view('control_panel.reports.departments.courses.accomplishedSafwaProgramStudents')->render(),
        ];
    }




















    public function getAnalysisData(Request $request)
    {
        $analysis_type = $request->analysis_type;

        switch ($analysis_type) {
            case 'coursePlanProgress': {
                    return $this->coursePlanProgressData($request);
                }
            case 'safwaProgram': {
                    return $this->accomplishedSafwaProgramStudentsData($request);
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

    public function accomplishedSafwaProgramStudentsData(Request $request)
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





        $value = array();



        // if (Cache::has('safwa_books_ids')) {
        //     $books_ids = Cache::get('safwa_books_ids');
        // } else {
        $year = date("Y");
        $books_ids = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $books_ids = json_decode($books_ids);
        //     Cache::put('safwa_books_ids', $books_ids,600);
        // }


        $count = User::subarea($sub_area_id, $area_id)
            ->BookStudents($book_id)
            ->place($place_id)
            ->teacher($teacher_id)
            ->whereHas('courses', function ($query) use ($books_ids) {
                $query->whereIn('book_id', $books_ids);
            })
            ->count();

        $users =  User::subarea($sub_area_id, $area_id)
            ->BookStudents($book_id)
            ->place($place_id)
            ->teacher($teacher_id)
            ->search($search)
            ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
            ->whereHas('courses', function ($query) use ($books_ids) {
                $query->whereIn('book_id', $books_ids);
            })
            ->get();







        foreach ($users as $index => $item) {
            array_push($value, $item->student_safwa_project_compelation_data);
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }

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
            ->permissionssubarea($sub_area_id, $area_id)
            ->teacher($teacher_id)
            ->book($book_id)
            ->place($place_id)
            ->count();
        $sub_areas = Area::whereNotNull('area_id')
            ->permissionssubarea($sub_area_id, $area_id)
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
                // ->has('courses')
                ->whereHas('courses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })

                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->count();
            $places = Place::select('id', 'name', 'area_id')
                ->search($search)
                // ->has('courses')
                ->whereHas('courses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Place::select('id', 'name', 'area_id')->teacher($teacher_id)
                ->book($book_id)
                // ->has('courses')
                ->whereHas('courses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)->count();
            $places = Place::select('id', 'name', 'area_id')
                ->teacher($teacher_id)
                ->book($book_id)
                // ->has('courses')
                ->whereHas('courses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
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
                // ->has('teacherCourses')
                ->whereHas('teacherCourses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::subarea($sub_area_id, $area_id)
                ->search($search)
                ->department(2)
                // ->has('teacherCourses')
                ->whereHas('teacherCourses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->book($book_id)
                ->place($place_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::subarea($sub_area_id, $area_id)
                ->department(2)
                // ->has('teacherCourses')
                ->whereHas('teacherCourses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->teacher($teacher_id)
                ->book($book_id)
                ->place($place_id)
                ->count();
            $teachers = User::subarea($sub_area_id, $area_id)
                ->department(2)
                // ->has('teacherCourses')
                ->whereHas('teacherCourses', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
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
            $count = Book::where('year', $year)->where('required_students_number', '>', 0)->search($search)
                ->book($book_id)
                ->count();
            $books = Book::where('year', $year)->where('required_students_number', '>', 0)->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->book($book_id)
                ->get();
        } else {
            $count = Book::where('year', $year)->where('required_students_number', '>', 0)->book($book_id)
                ->count();
            $books = Book::where('year', $year)->where('required_students_number', '>', 0)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
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
