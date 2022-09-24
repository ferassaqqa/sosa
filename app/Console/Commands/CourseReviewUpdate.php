<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\Review;
use App\Models\CourseStudent;
use App\Models\Book;
use App\Models\User;
use App\Models\CourseProject;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class CourseReviewUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'review:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update reviews scores for all areas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $year = date("Y");
        $books = Book::where('year', $year)->get();
        $books_ids = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $books_ids = json_decode($books_ids);
        $areas = Area::whereNull('area_id')->get();
        $project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $project = json_decode($project);




        foreach ($areas as $key => $area) {

            $total_pass = 0;
            $total_required  = 0;
            $pass = 0;
            $avg = 0;
            $total_avg = array();
            $total_pass_all = 0;
            $area_total_books_percentage = [];

            $primary_point = 1;
            $middle_point = 2;
            $high_point = 3;
            $total_point = $primary_point + $middle_point + $high_point;

            foreach ($books as $key => $book) {

                if ($book->required_students_number == 0) {
                    continue;
                } else {

                    /* start percenage_38*/
                    $rest = 0;
                    $coll = CourseStudent::book($book->id)
                        ->subarea(0, $area->id)
                        ->whereBetween('mark', [60, 101]);

                    $pass = $coll->count();

                    $total_pass_all +=  $pass;
                    $total_required += floor(($area->percentage * $book->required_students_number)  / 100);

                    $rest =  $pass - floor(($area->percentage * $book->required_students_number)  / 100);
                    if ($rest > 0) {
                        $total_pass += floor(($area->percentage * $book->required_students_number)  / 100);
                    } elseif ($rest < 0) {
                        $total_pass += $pass;
                    }
                    /*end percentage 38 */

                    /*start avg */
                    $avg = $coll->pluck('mark')->toArray();
                    $total_avg += $avg;
                    /* end avg*/






                    /*start graduate class */
                    $primary = CourseStudent::whereHas('user', function ($query) {
                        $to = Carbon::now()->subYears(4)->startOfYear()->format('d-m-Y');
                        $from = Carbon::now()->subYears(12)->startOfYear()->format('d-m-Y');
                        $query->whereBetween('dob', [$from, $to]);
                    })->book($book->id)
                        ->subarea(0, $area->id)
                        ->course('منتهية')->whereBetween('mark', [60, 101])->count();
                    $total_primary_points = $primary * $total_point;


                    $middle = CourseStudent::whereHas('user', function ($query) {
                        $to = Carbon::now()->subYears(13)->startOfYear()->format('d-m-Y');
                        $from = Carbon::now()->subYears(15)->startOfYear()->format('d-m-Y');
                        $query->whereBetween('dob', [$from, $to]);
                    })->book($book->id)
                        ->subarea(0, $area->id)
                        ->course('منتهية')->whereBetween('mark', [60, 101])->count();
                    $total_middle_points = $middle * $total_point;


                    $high = CourseStudent::whereHas('user', function ($query) {
                        $from = Carbon::now()->subYears(16)->startOfYear()->format('d-m-Y');
                        $query->where('dob', '>=', $from);
                    })->book($book->id)
                        ->subarea(0, $area->id)
                        ->course('منتهية')->whereBetween('mark', [60, 101])->count();
                    $total_high_points = $high * $total_point;

                    $total_points = $total_primary_points + $total_middle_points + $total_high_points;
                    $required_student_total = floor($book->required_students_number * ($area->percentage / 100));
                    $total_required_point = $required_student_total * $total_point;



                    $point_percentage = $total_required_point ? round((($total_points / $total_required_point) * 100), 2) : 0;
                    $point_percentage = $point_percentage > 100 ? 100 : $point_percentage;


                    array_push($area_total_books_percentage, $point_percentage);
                    /*end graduate class */
                }
            }



            $sucess_percentage = round($total_pass / $total_required, 2) * 100;
            $percentage_38 = ($sucess_percentage * 38) / 100;
            $percentage_38 = sprintf('%.2f', $percentage_38);


            $total_avg = array_sum($total_avg) / count($total_avg);
            $test_quality_5 = $total_avg * 0.05;
            $test_quality_5 = sprintf('%.2f', $test_quality_5);



            $total_surplus_graduates_by_area =  $total_pass_all - $total_required;
            $total_surplus_graduates_all_area = $area->getSurplusGraduatesForAllAreas();


            $surplus_graduates_2 = ($total_surplus_graduates_by_area > 0) ? ($total_surplus_graduates_by_area / $total_surplus_graduates_all_area) * 2 : 0;
            $surplus_graduates_2 = sprintf('%.2f', $surplus_graduates_2);



            $total_avg = array_sum($area_total_books_percentage) / count($area_total_books_percentage);
            $graduate_class = $total_avg * 0.03;
            $graduate_class = sprintf('%.2f', $graduate_class);



            /* start safwa */
            $limit = 500;
            $users = User::subarea(0, $area->id)->limit($limit)
                ->whereHas('courses', function ($query) use ($project) {
                    $query->whereIntegerInRaw('book_id', $project);
                    $query->whereBetween('mark', [60, 101]);
                })
                ->pluck('id')->toArray();


            $result = array();
            foreach ($users as $index => $user) {
                $count =  DB::table('course_students')
                    ->leftJoin('courses', 'courses.id', '=', 'course_students.course_id')
                    ->whereIntegerInRaw('book_id', $project)
                    ->where('course_students.user_id', '=', $user)
                    ->select('courses.book_id')
                    ->distinct('courses.book_id')
                    ->count();

                array_push($result, $count);
            }

            $result = array_count_values($result);
            ksort($result);

            $key = array_key_last($result);
            $value = $result[array_key_last($result)];
            if ($value >= 15) {
                $value = 15;
            }
            $safwa_score =   round((($key * $value) / 90) * 2, 2);
            /*end safwa*/


            // Review::insert([
            //     'area_id' => $area->id,
            //     'plan_score_38' => $percentage_38,
            //     'test_quality_5' => $test_quality_5,
            //     'super_plus_2' => $surplus_graduates_2,
            //     'students_category_3' => $graduate_class,
            //     'safwa_score_2' => $safwa_score,
            //     'created_at' => Carbon::now()
            // ]);
            
            Review::insert([
                'area_id' => $area->id,
                'sub_area_id' => 0,
                'plan_score_38' => $percentage_38,
                'test_quality_5' => $test_quality_5,
                'super_plus_2' => $surplus_graduates_2,
                'students_category_3' => $graduate_class,
                'safwa_score_2' => $safwa_score,
                'created_at' => Carbon::now()
            ]);
        }


        $this->info('Reviews Score has been updated successfully');
    }
}
