<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\User;
use App\Models\CourseProject;
use Illuminate\Support\Facades\DB;



class SafwaUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'safwa:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update the safwa marks for all areas';

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
        $areas = Area::whereNull('area_id')->get();
        $project = CourseProject::where('year', $year)->limit(1)->pluck('books')->first(); //get safwa project
        $project = json_decode($project);

        $final_result = array();

        foreach ($areas as $key => $area) {
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

                $result =array_count_values($result);
                ksort($result);

                $key = array_key_last($result);
                $value = $result[array_key_last($result)];
                if($value >= 15 ){$value = 15;}

                // echo $key.' '.$value; exit;

              $safwa_score =   round((($key * $value) / 90)*2,2);



              Area::where('id', $area->id)
                    ->update([
                        'safwa_score_2' => $safwa_score
                        ]);


        }

        $this->info('Safwa Score has been updated successfully');


    }
}
