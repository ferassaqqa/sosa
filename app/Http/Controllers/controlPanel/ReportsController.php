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
use Illuminate\Support\Facades\Auth;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', 1440); //12 minutes

    }
    public function allReports()
    {
        $areas = Area::whereNull('area_id')->get();
        $year = date("Y");
        $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->get();
        $asaneed_books = AsaneedBook::where('year', $year)->get();

        $books_ids = CourseProject::where('year', $year)->limit(1)->pluck('books')->first();
        $safwa_books = Book::select('id', 'name')->whereIn('id', json_decode($books_ids))->get();

        return view('control_panel.reports.courseAreaReport', compact('areas', 'in_plane_books', 'asaneed_books', 'safwa_books'));
    }

    public function allReviews()
    {


        $areas = Area::whereNull('area_id')->get();

        return view('control_panel.reports.departments.reviews.all', compact('areas'));
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
            case 7:
                # code...
                $rate = 'الثامن';
                break;
            case 8:
                # code...
                $rate = 'التاسع';
                break;
        }

        return $rate;
    }

    public function courseReviewDetailsView(Request $request)
    {

        $sub_area_id = $request->sub_area_id ? $request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;




        if($sub_area_id){
            if($sub_area_id == 'all'){
                $areas = Area::where('area_id', $area_id)->get();
            }else{
                $areas = Area::where('id', $sub_area_id)->get();
            }

        }else{
            $areas = Area::permissionssubarea($sub_area_id, $area_id)
            ->whereNull('area_id')->get();
        }

        // dd($areas);


        $value = array();
        $result_review = array();

        foreach ($areas as $index => $item) {
            $new_item = $item->course_reviews_row_data;
            $result_review[] = $new_item;
        }

        foreach ($result_review as $key => $row) {
            $name[$key]  = $row['name'];
            $test_quality_5[$key] = $row['test_quality_5'];
            $surplus_graduates_2[$key] = $row['surplus_graduates_2'];
            $safwa_graduates_2[$key] = $row['safwa_graduates_2'];
            $percentage_38[$key] = $row['percentage_38'];
            $students_category_3[$key] = $row['students_category_3'];
            $percentage_50[$key] = $row['percentage_50'];
            $percentage_total[$key] = $row['percentage_total'];
            $id[$key] = $row['id'];
        }

        array_multisort($percentage_total, SORT_DESC, $result_review);
        foreach ($result_review as $key => $row) {
            $scores[] = $row['percentage_total'];
        }
        $duplicates = array_unique(array_diff_assoc($scores, array_unique($scores)));

        $i=0;
        foreach ($result_review as $key1 => $row) {
            $i++;
            $label = $this->getOrderLabel($key1);
            $key = 0;
            if (array_key_exists($key1, $duplicates)) {
                $key = array_search($row['percentage_total'], $duplicates);
                $label = $this->getOrderLabel($key - 1) . ' مكرر';
            }
            if($row['percentage_total'] == 0){$label = '-';}

            $item = '
            <tr >
                <td>' . $i . '</td>
                <td>' . $row['name'] . '</td>

                <td>' . $row['percentage_38'] . '%</td>
                <td>'.$row['test_quality_5'].'%</td>
                <td>'.$row['surplus_graduates_2'].'%</td>
                <td>'.$row['students_category_3'].'%</td>
                <td>'.$row['safwa_graduates_2'].'%</td>

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

        $asaneed_books = AsaneedBook::all();
        $value = array();
        $result_review = array();
        $scores = array();

        foreach ($areas as $index => $item) {
            $new_item = $item->asaneed_reviews_row_data;
            array_push($result_review, $new_item);
        }


        foreach ($result_review as $key => $row) {

            $name[$key]  = $row['name'];
            $superplus_graduates[$key]  = $row['superplus_graduates'];
            $total_score_10[$key]  = $row['total_score_10'];
            $id[$key]  = $row['id'];
            $total_score_100[$key]  = $row['total_score_100'];

        }

        array_multisort($total_score_100, SORT_DESC, $result_review);

        // dd($result_review);


        foreach ($value as $key => $row) {
            $scores[] = $row['total_score_100'];
        }
        $duplicates = array_unique(array_diff_assoc($scores, array_unique($scores)));


        $i=0;
        foreach ($result_review as $key => $row) {
            $i++;
            $label = $this->getOrderLabel($key);
            // if (array_key_exists($key, $duplicates)) {
            //     $label =$this->getOrderLabel($key-1).' مكرر';
            // }
            if (in_array($row['total_score_100'], $scores)) {
                $key = array_search($row['total_score_100'], $duplicates);
                $label = $this->getOrderLabel($key - 1) . ' مكرر';
            }



            $item = '
            <tr >
                <td>' . $i . '</td>
                <td>' . $row['name'] . '</td>';

            foreach ($asaneed_books as $key => $book) {
                    $item.=  '<td>' . $row[$book->id] . '%</td>';
            }

            $item.='<td><b>' . $row['superplus_graduates'] . '%</b></td>
                <td><b>' . $row['total_score_10'] . '%</b></td>
                <td><b>' . $row['total_score_100'] . '%</b></td>
                <td>' . $label . '</td>
                <td></td>
            </tr>';

            array_push($value, $item);
        }

        return [
            'view' => view('control_panel.reports.departments.reviews.asaneedReviewsDetails', compact('areas', 'asaneed_books','value'))->render()
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

    private function asaneedAreaPlanProgressView(Request $request)
    {

        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;
        $asaneed_book_id = $_REQUEST ? $_REQUEST['asaneed_book_id'] : 0;
        $asaneedResult = array();
        $value = array();


        if ($area_id) {
            $areas = Area::where('id', $area_id)->get();
        } else {
            $areas = Area::whereNull('area_id')->get();
        }

        if ($asaneed_book_id) {
            $asaneed_plan_books = AsaneedBook::where('id', $asaneed_book_id)->get();
        } else {
            $asaneed_plan_books = AsaneedBook::all();
        }



        foreach ($asaneed_plan_books as $index => $item) {
            $new_item = $item->asaneed_students_reports_by_area_row_data;
            $asaneedResult[] = $new_item;
        }

        foreach ($asaneedResult as $key => $row) {
            $name[$key]  = $row['name'];
            $required_students_number[$key] = $row['required_students_number'];
            $data[$key] = $row['data'];
            $total_pass[$key] = $row['total_pass'];
            $total_rest[$key] = $row['total_rest'];
            $total_plus[$key] = $row['total_plus'];
            $pass_percentage[$key] = $row['pass_percentage'];
            $id[$key] = $row['id'];
        }

        array_multisort($pass_percentage, SORT_DESC, $asaneedResult);

        $required_students_number = 0;
        $pass_students_number = 0;
        $rest_students_number = 0;
        $plus_students_number = 0;

        $i = 0;
        foreach ($asaneedResult as $key => $row) {
            $i++;

            $required_students_number += $row['required_students_number'];
            $pass_students_number += $row['total_pass'];
            $rest_students_number += $row['total_rest'];
            $plus_students_number += $row['total_plus'];

            $item = '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['required_students_number'] . '</td>
                   ' . $row['data'] . '
                    <td>' . $row['total_pass'] . '</td>
                    <td>' . $row['total_rest'] . '</td>
                    <td>' . $row['pass_percentage'] . ' %</td>
                    <td>' . $row['plus_percentage'] . ' %</td>
                </tr>
                ';

            array_push($value, $item);
        }

        $total_row = '<tr>
                        <td></td>
                        <td>المجموع</td>
                        <td>' . $required_students_number . '</td>';


        if ($_REQUEST['area_id']) {
            $total_row .= '<td colspan="2"></td>';
        } else {
            $total_row .= '<td colspan="14"></td>';
        }

        $total_percentage =  ($required_students_number > 0) ? round((($pass_students_number / $required_students_number) * 100), 2) : 0;
        if ($total_percentage > 100) {
            $total_percentage = 100;
        }

        $plus_percentage =  ($plus_students_number > 0) ? round((($plus_students_number / $required_students_number) * 100), 2) : 0;


        $total_row .=  '<td>' . $pass_students_number . '</td>
                                <td>' . $rest_students_number . '</td>
                                <td><b>' . $total_percentage . ' % </b></td>
                                <td><b>' . $plus_percentage . ' % </b></td>
                        </tr>';

        array_push($value, $total_row);

        return [
            'view' => view('control_panel.reports.departments.asaneed.asaneedAreaPlanProgress', compact('value', 'areas'))->render()
        ];
    }

    private function getTotalRowCourseAreaPlanProgress($required_result = array(), $safwa_flag = false, $safwa_array = array())
    {

        $result_report_in_plane = array();
        $in_plane_books_value = array();


        foreach ($required_result as $index => $item) {

            if (in_array($item->id, $safwa_array) === $safwa_flag) {
                $new_item = $item->course_students_reports_by_area_row_data;
                // array_push($in_plane_books_value, $new_item);
                $result_report_in_plane[] = $new_item;
            }
        }

        $pass_percentage = array();
        $total_pass = array();

        foreach ($result_report_in_plane as $key => $row) {
            $name[$key]  = $row['name'];
            $required_students_number[$key] = $row['required_students_number'];
            $data[$key] = $row['data'];
            $total_pass[$key] = $row['total_pass'];
            $total_rest[$key] = $row['total_rest'];
            $total_plus[$key] = $row['total_plus'];
            $pass_percentage[$key] = $row['pass_percentage'];
            $id[$key] = $row['id'];
        }


        array_multisort($total_pass, SORT_DESC, $result_report_in_plane);





        $required_students_number = 0;
        $pass_students_number = 0;
        $rest_students_number = 0;
        $plus_students_number = 0;


        $i = 0;
        foreach ($result_report_in_plane as $key => $row) {
            $i++;
            if (in_array($row['id'], $safwa_array)  ==  $safwa_flag) {

                $required_students_number += $row['required_students_number'];
                $pass_students_number += $row['total_pass'];
                $rest_students_number += $row['total_rest'];
                $plus_students_number += $row['total_plus'];


                $item = '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['required_students_number'] . '</td>
                   ' . $row['data'] . '
                    <td>' . $row['total_pass'] . '</td>
                    <td>' . $row['total_rest'] . '</td>

                    <td>' . $row['pass_percentage'] . ' %</td>
                    <td>' . $row['plus_percentage'] . ' %</td>

                </tr>
                ';

                array_push($in_plane_books_value, $item);
            }
        }

        // dd($result_report_in_plane);

        $total_in_plan_row = '<tr>
                                <td></td>
                                <td>المجموع</td>
                                <td>' . $required_students_number . '</td>';


        if ($_REQUEST['area_id']  || Auth::user()->hasRole('مشرف عام')) {
            $total_in_plan_row .= '<td colspan="2"></td>';
        } else {
            $total_in_plan_row .= '<td colspan="14"></td>';
        }

        $total_percentage =  ($required_students_number > 0) ? round((($pass_students_number / $required_students_number) * 100), 2) : 0;
        if ($total_percentage > 100) {
            $total_percentage = 100;
        }

        $plus_percentage =  ($required_students_number > 0) ? round((($plus_students_number / $required_students_number) * 100), 2) : 0;


        $total_in_plan_row .=  '<td>' . $pass_students_number . '</td>
                                <td>' . $rest_students_number . '</td>


                                <td><b>' . $total_percentage . ' % </b></td>
                                <td><b>' . $plus_percentage . ' % </b></td>

                            </tr>';




        array_push($in_plane_books_value, $total_in_plan_row);

        // dd($in_plane_books_value);


        return $in_plane_books_value;
    }

    private function courseAreaPlanProgressView(Request $request)
    {

        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;
        $book_id = $_REQUEST ? $_REQUEST['book_id'] : 0;

        $year = date("Y");
        $in_plane_books_value = array();
        $out_plane_books_value = array();

        if ($book_id) {
            $in_plane_books = Book::where('id', $book_id)->where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();
            $out_plane_books = Book::where('id', $book_id)->where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        } else {
            $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();
            $out_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        }

        if ($area_id) {
            $areas = Area::where('id', $area_id)->get();
        } else {
            $areas = Area::whereNull('area_id')->get();
        }






        $project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $project = json_decode($project);
        $project_books_value = array();



        $in_plane_books_value = $this->getTotalRowCourseAreaPlanProgress($in_plane_books, false, $project);
        $project_books_value = $this->getTotalRowCourseAreaPlanProgress($in_plane_books, true, $project);
        $out_plane_books_value = $this->getTotalRowCourseAreaPlanProgress($out_plane_books, false, $project);




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
        $book_id = $request->asaneed_book_id;

        $year = Carbon::parse($request->start_date)->format('Y');

        if ($book_id) {
            $books = AsaneedBook::where('id', $book_id)->get();
        } else {
            $books = AsaneedBook::where('year', $year)->get();
        }
        $value = array();
        $asaneed_result = array();

        foreach ($books as $index => $item) {
            $new_item = $item->asaneed_students_reports_by_students_categories_row_data;
            $asaneed_result[] = $new_item;
            // array_push($value, $new_item);
        }


        $total_pass = array();
        foreach ($asaneed_result as $key => $row) {
            $name[$key]  = $row['name'];
            $required_student_total[$key] = $row['required_student_total'];
            $total_pass[$key] = $row['total_pass'];
            $completed_num_percentage[$key] = $row['completed_num_percentage'];
            $excess_num_percentage[$key] = $row['excess_num_percentage'];
            $id[$key] = $row['id'];
        }

        array_multisort($total_pass, SORT_DESC, $asaneed_result);


        $value = [];
        $required = 0;
        $pass = 0;


        $i = 0;
        foreach ($asaneed_result as $key => $row) {
            $i++;
            $required += $row['required_student_total'];
            $pass += $row['total_pass'];


            $item = '
                    <tr>
                        <td>' . $i . '</td>
                        <td>' . $row['name'] . '</td>
                        <td>' . $row['required_student_total'] . '</td>
                        <td>' . $row['total_pass'] . '</td>
                        <td>' . $row['completed_num_percentage'] . ' %</td>
                        <td>' . $row['excess_num_percentage'] . ' %</td>

                    </tr>
                ';
            array_push($value, $item);
        }

        $total_percentage =  ($required > 0) ? round((($pass / $required) * 100), 2) : 0;
        if ($total_percentage > 100) {
            $total_percentage = 100;
        }

        $totalRow =        '<tr>
                                    <td></td>
                                    <td>المجموع</td>
                                    <td>' . $required . '</td>
                                    <td>' . $pass . '</td>
                                    <td>' .  $total_percentage . ' %</td>
                                    <td>0 %</td>
                            </tr>';
        array_push($value, $totalRow);



        return [
            'view' => view('control_panel.reports.departments.asaneed.asaneedPlanProgress', compact(
                'value'
            ))->render()

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

            $out_plane_books = Book::where('year', $year)->where('id', $book_id)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        } else {
            // $books = Book::where('year', $year)->where('required_students_number' ,'>',0)->get();

            $in_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'داخل الخطة')->get();

            $out_plane_books = Book::where('year', $year)->where('required_students_number', '>', 0)->where('included_in_plan', 'خارج الخطة')->get();
        }

        $in_plane_books_value = $this->getTotalRowCoursePlanProgress($in_plane_books, false, $course_project);
        $out_plane_books_value = $this->getTotalRowCoursePlanProgress($in_plane_books, true, $course_project);
        $project_books_value = $this->getTotalRowCoursePlanProgress($out_plane_books, true, $course_project);


        // dd($in_plane_books_value);





        return [
            'view' => view('control_panel.reports.coursesPlanProgress', compact(
                'in_plane_books_value',
                'out_plane_books_value',
                'project_books_value'
            ))->render()
        ];
    }

    public function getTotalRowCoursePlanProgress($required_result = array(), $safwa_flag = false, $safwa_array = array())
    {
        $result = array();
        $total_required = 0;
        $total_pass_var = 0;

        $index = 0;


        $result_report = array();

        foreach ($required_result as $index => $item) {
            if (in_array($item->id, $safwa_array) == $safwa_flag) {
                $new_item = $item->students_reports_by_students_categories_row_data;
                array_push($result_report, $new_item);
            }
        }

        $total_pass = array();

        foreach ($result_report as $key => $row) {

            $name[$key]  = $row['name'];
            $required_number[$key] = $row['required_number'];


            $required_student_primary[$key] = $row['required_student_primary'];
            $passed_students_count_primary[$key] = $row['passed_students_count_primary'];
            $completed_num_percentage_primary[$key] = $row['completed_num_percentage_primary'];
            $excess_num_percentage_primary[$key] = $row['excess_num_percentage_primary'];


            $required_student_middle[$key] = $row['required_student_middle'];
            $passed_students_count_middle[$key] = $row['passed_students_count_middle'];
            $completed_num_percentage_middle[$key] = $row['completed_num_percentage_middle'];
            $excess_num_percentage_middle[$key] = $row['excess_num_percentage_middle'];


            $required_student_high[$key] = $row['required_student_high'];
            $passed_students_count_high[$key] = $row['passed_students_count_high'];
            $completed_num_percentage_high[$key] = $row['completed_num_percentage_high'];
            $excess_num_percentage_high[$key] = $row['excess_num_percentage_high'];

            $total_pass[$key] = $row['total_pass'];
            $completed_num_percentage[$key] = $row['completed_num_percentage'];
            $excess_num_percentage[$key] = $row['excess_num_percentage'];

            $id[$key] = $row['id'];
        }


        array_multisort($total_pass, SORT_DESC, $result_report);



        foreach ($result_report as $index => $new_item) {

            $total_required += $new_item['required_number'];
            $total_pass_var += $new_item['total_pass'];

            $index += 1;

            $item = '
                <tr>
                            <tr>
                                <th rowspan="4">' . $index . '</th>
                                <th rowspan="4" style="background: #f0f0f0">' . $new_item['name'] . '</th>
                                <th>ابتدائية ( 7 - 12 )</th>
                                <td>' . $new_item['required_student_primary'] . '</td>
                                <td>' . $new_item['passed_students_count_primary'] . '</td>
                                <td>' . $new_item['completed_num_percentage_primary'] . ' %</td>
                                <td>' . $new_item['excess_num_percentage_primary'] . ' %</td>

                            </tr>
                            <tr>
                                <th>اعدادية ( 13 - 15 )</th>
                                <td>' . $new_item['required_student_middle'] . '</td>
                                <td>' . $new_item['passed_students_count_middle'] . '</td>
                                <td>' . $new_item['completed_num_percentage_middle'] . ' %</td>
                                <td>' . $new_item['excess_num_percentage_middle'] . ' %</td>
                            </tr>
                            <tr>
                                <th>ثانوية فما فوق ( 16 فما فوق )</th>
                                <td>' . $new_item['required_student_high'] . '</td>
                                <td>' . $new_item['passed_students_count_high'] . '</td>
                                <td>' . $new_item['completed_num_percentage_high'] . ' %</td>
                                <td>' . $new_item['excess_num_percentage_high'] . ' %</td>
                            </tr>
                            <tr style="background: #f0f0f0">
                                <th>المجموع</th>
                                <td>' . $new_item['required_number'] . '</td>
                                <td>' . $new_item['total_pass'] . '</td>
                                <td>' . $new_item['completed_num_percentage'] . ' %</td>
                                <td>' . $new_item['excess_num_percentage'] . ' %</td>
                            </tr>
                    </tr>
                ';

            array_push($result, $item);
        }

        $total = ($total_required > 0) ? floor(($total_pass_var / $total_required) * 100) : 0;

        $complet_percentage = ($total > 100) ? 100 : $total;

        $total_in_plan_row = '<tr>

                                <td colspan="3"><b>المجموع الكلي </b></td>
                                <td>' . $total_required . '</td>
                                <td>' . $total_pass_var . '</td>
                                <td>' . $complet_percentage . ' %</td>
                                <td></td>
                            </tr>';




        array_push($result, $total_in_plan_row);



        return $result;
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
                        return $this->mostAsaneedAccomplishedMosquesData($request);
                    } elseif ($analysis_sub_type == 'local_areas') {
                        return $this->mostAsaneedAccomplishedLocalAreaData($request);
                    }
                }

                break;
        }
    }

    public function mostAsaneedAccomplishedLocalAreaData(Request $request)
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
            ->asaneedteacher($teacher_id)
            ->asaneedbook($book_id)
            ->asaneedplace($place_id)
            ->count();
        $sub_areas = Area::whereNotNull('area_id')
            ->permissionssubarea($sub_area_id, $area_id)
            ->asaneedteacher($teacher_id)
            ->asaneedbook($book_id)
            ->asaneedplace($place_id)
            ->get();



        // foreach ($sub_areas as $index => $item) {
        //     array_push($value, $item->most_accomplished_course_row_data);
        // }


        $result_mostaccomplish_course = array();

        foreach ($sub_areas as $index => $item) {
            $new_item = $item->most_accomplished_asaneed_row_data;
            $result_mostaccomplish_course[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_mostaccomplish_course as $key => $row) {
            $subarea_name[$key]  = $row['subarea_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }


        array_multisort($total_accomplished_students, SORT_DESC, $result_mostaccomplish_course);


        $i = 0;
        foreach ($result_mostaccomplish_course as $key => $row) {
            $i++;
            $result_mostaccomplish_course[$key]['id'] = $i;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_mostaccomplish_course,
            "order" => $columns[$order]["db"]
        ];
    }


    public function mostAsaneedAccomplishedMosquesData(Request $request)
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
                ->whereHas('asaneed', function ($query) {
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
                ->whereHas('asaneed', function ($query) {
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
                ->whereHas('asaneed', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)->count();
            $places = Place::select('id', 'name', 'area_id')
                ->teacher($teacher_id)
                ->book($book_id)
                // ->has('courses')
                ->whereHas('asaneed', function ($query) {
                    $query->whereHas('manyStudents', function ($query) {
                    });
                })
                ->place($place_id)
                ->permissionssubarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }




        $result_mostaccomplish_course = array();

        foreach ($places as $index => $item) {
            $new_item = $item->most_asaneed_accomplished_course_row_data;
            if ($new_item['total_accomplished_students'] > 0)
                $result_mostaccomplish_course[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_mostaccomplish_course as $key => $row) {
            $mosque_name[$key]  = $row['mosque_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }


        array_multisort($total_accomplished_students, SORT_DESC, $result_mostaccomplish_course);

        $i = 0;
        foreach ($result_mostaccomplish_course as $key => $row) {
            $i++;
            $result_mostaccomplish_course[$key]['id'] = $i;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_mostaccomplish_course,
            "order" => $columns[$order]["db"]
        ];
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
        $book_id = (int)$request->safwa_books_id ? (int)$request->safwa_books_id : 0;
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;

        $value = array();

        if ($book_id) {
            $books_ids = [$book_id];
        } else {
            $year = date("Y");
            $books_ids = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
            $books_ids = json_decode($books_ids);
        }


        $count = User::subarea($sub_area_id, $area_id)
            ->BookStudents($book_id)
            ->place($place_id)
            ->teacher($teacher_id)
            ->search($search)
            ->whereHas('courses', function ($query) use ($books_ids) {
                $query->whereHas('book', function ($query) use ($books_ids) {
                    $query->whereIn('book_id', $books_ids);
                })->whereBetween('mark', [60, 101]);
            })
            ->count();


        $users =  User::subarea($sub_area_id, $area_id)
            ->BookStudents($book_id)
            ->place($place_id)
            ->teacher($teacher_id)
            ->search($search)
            ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
            ->whereHas('courses', function ($query) use ($books_ids) {
                $query->whereHas('book', function ($query) use ($books_ids) {
                    $query->whereIn('book_id', $books_ids);
                })->whereBetween('mark', [60, 101])->groupBy();
            })
            ->get();






        $result = array();
        $completed_books_count = array();
        foreach ($users as $index => $item) {
            array_push($result, $item->student_safwa_project_compelation_data);
        }


        foreach ($result as $key => $row) {
            $name[$key]  = $row['name'];
            $dob[$key] = $row['dob'];
            $place_dob[$key] = $row['place_dob'];
            $completed_books[$key] = $row['completed_books'];
            $rest_books[$key] = $row['rest_books'];
            $completed_books_count[$key] = $row['completed_books_count'];

            $id[$key] = $row['id'];
        }


        array_multisort($completed_books_count, SORT_DESC, $result);
        $i = 0;

        foreach ($result as $key => $value) {
            $i++;
            $result[$key]['id'] = $i;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result,
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



        // foreach ($sub_areas as $index => $item) {
        //     array_push($value, $item->most_accomplished_course_row_data);
        // }





        $result_mostaccomplish_course = array();

        foreach ($sub_areas as $index => $item) {
            $new_item = $item->most_accomplished_course_row_data;
            $result_mostaccomplish_course[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_mostaccomplish_course as $key => $row) {
            $subarea_name[$key]  = $row['subarea_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }


        array_multisort($total_accomplished_students, SORT_DESC, $result_mostaccomplish_course);


        $i = 0;
        foreach ($result_mostaccomplish_course as $key => $row) {
            $i++;
            $result_mostaccomplish_course[$key]['id'] = $i;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_mostaccomplish_course,
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




        $result_mostaccomplish_course = array();

        foreach ($places as $index => $item) {
            $new_item = $item->most_accomplished_course_row_data;
            $result_mostaccomplish_course[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_mostaccomplish_course as $key => $row) {
            $mosque_name[$key]  = $row['mosque_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }


        array_multisort($total_accomplished_students, SORT_DESC, $result_mostaccomplish_course);

        $i = 0;
        foreach ($result_mostaccomplish_course as $key => $row) {
            $i++;
            $result_mostaccomplish_course[$key]['id'] = $i;
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_mostaccomplish_course,
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


        $result_mostaccomplish_course = array();

        foreach ($teachers as $index => $item) {
            $new_item = $item->most_accomplished_course_row_data;
            $result_mostaccomplish_course[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_mostaccomplish_course as $key => $row) {
            $teacher_name[$key]  = $row['teacher_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }

        array_multisort($total_accomplished_students, SORT_DESC, $result_mostaccomplish_course);

        $i = 0;
        foreach ($result_mostaccomplish_course as $key => $row) {
            $i++;
            $result_mostaccomplish_course[$key]['id'] = $i;
        }


        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_mostaccomplish_course,
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

        $result_asaneed_teacher = array();

        foreach ($teachers as $index => $item) {
            $new_item = $item->most_accomplished_asaneed_row_data;
            $result_asaneed_teacher[] = $new_item;
        }

        $total_accomplished_students = array();
        foreach ($result_asaneed_teacher as $key => $row) {
            $teacher_name[$key]  = $row['teacher_name'];
            $total_accomplished_course[$key] = $row['total_accomplished_course'];
            $total_accomplished_students[$key] = $row['total_accomplished_students'];
            $most_accomplished_course[$key] = $row['most_accomplished_course'];
            $id[$key] = $row['id'];
        }
        array_multisort($total_accomplished_students, SORT_DESC, $result_asaneed_teacher);

        $i = 0;
        foreach ($result_asaneed_teacher as $key => $row) {
            $i++;
            $result_asaneed_teacher[$key]['id'] = $i;
        }



        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$result_asaneed_teacher,
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
            $index++;
            $new_item = $item->book_courses_plan_progress_display_data;
            $new_item['id'] = $index;
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
