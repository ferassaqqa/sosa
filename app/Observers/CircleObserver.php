<?php

namespace App\Observers;

use App\Models\Circle;
use App\Models\CircleMonthlyReport;
  
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

            for ($i=0; $i < 7; $i++) { 

                $report = CircleMonthlyReport::create([
                    'circle_id' => $circle->id,
                    'date' => Carbon::create($start_date['year'], $start_date['mon'], $start_date['mday'], 0, 0, 0)->addMonths($i),
                    'status' => 0,
                    'is_delivered' => 0,
                    'is_approved' => 0,
                ]);     
                    $report->save();
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
