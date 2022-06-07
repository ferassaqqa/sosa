<?php

namespace App\Observers;

use App\Models\Circle;
use App\Models\CircleMonthlyReport;
use App\Models\CircleMonthlyReportStudent;
use App\Models\CircleBooks;


use Carbon\Carbon;



class CircleObserver
{


   public $afterCommit = true;

    /**
     * Handle the Circle "created" event.
     *
     * @param  \App\Models\Circle  $circle
     * @return void
     */
    public function created(Circle $circle)
    {
        //
            $start_date = getdate(strtotime($circle->start_date));
            $book = CircleBooks::orderBy('location', 'ASC')->first();


            for ($i=0; $i < 7; $i++) {

                $report = CircleMonthlyReport::create([
                    'circle_id' => $circle->id,
                    'date' => Carbon::create($start_date['year'], $start_date['mon'], $start_date['mday'], 0, 0, 0)->addMonths($i),
                    'status' => 0,
                    'is_delivered' => 0,
                    'is_approved' => 0,
                ]);
                    $report->save();

                $students = $circle->students;

                foreach ($students as $key => $student) {

                    $student = CircleMonthlyReportStudent::create([
                        'circle_monthly_report_id' => $report->id,
                        'student_id' => $student->id,
                        'book_id' => $book->id,
                        'previous_from' => 0,
                        'previous_to' => 0,
                        'current_from' => 1,
                        'current_to' => 0

                    ]);
                        $student->save();

                }


                }

    }

    /**
     * Handle the Circle "updated" event.
     *
     * @param  \App\Models\Circle  $circle
     * @return void
     */
    public function updated(Circle $circle)
    {
        //
    }

    /**
     * Handle the Circle "deleted" event.
     *
     * @param  \App\Models\Circle  $circle
     * @return void
     */
    public function deleted(Circle $circle)
    {
        //

        $circle_reports_ids =  CircleMonthlyReport::where('circle_id',$circle->id)->pluck('id');

        $students = CircleMonthlyReportStudent::whereIn('id', $circle_reports_ids)->delete();

        $reports = CircleMonthlyReport::where('circle_id',$circle->id)->delete();


    }

    /**
     * Handle the Circle "restored" event.
     *
     * @param  \App\Models\Circle  $circle
     * @return void
     */
    public function restored(Circle $circle)
    {
        //
    }

    /**
     * Handle the Circle "force deleted" event.
     *
     * @param  \App\Models\Circle  $circle
     * @return void
     */
    public function forceDeleted(Circle $circle)
    {
        //
    }
}
