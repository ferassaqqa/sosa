<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\User;
use App\Models\CourseStudent;
use App\Models\Book;
use App\Models\CourseProject;
use Illuminate\Support\Facades\DB;



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





        foreach ($areas as $key => $area) {

            $total_pass = 0;
            $total_required  = 0;
            $pass = 0;
            $avg = 0;
            $total_avg = array();
            $total_pass_all = 0;

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


            $surplus_graduates_2 = ($total_surplus_graduates_by_area / $total_surplus_graduates_all_area) * 2 ;
            $surplus_graduates_2 = sprintf('%.2f', $surplus_graduates_2);


            Area::where('id', $area->id)
            ->update([
                'plan_score_38' => $percentage_38,
                'test_quality_5'=> $test_quality_5,
                'super_plus_2'=> $surplus_graduates_2,
                ]);


        }


        $this->info('Reviews Score has been updated successfully');
    }
}
