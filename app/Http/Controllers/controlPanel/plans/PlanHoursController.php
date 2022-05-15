<?php

namespace App\Http\Controllers\controlPanel\plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\plans\hourly\newHoursRequest;
use App\Http\Requests\controlPanel\plans\hourly\updateHoursRequest;
use App\Models\BookPlan;
use App\Models\BookPlanHour;
use Illuminate\Http\Request;

class PlanHoursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BookPlan $plan)
    {
        $bookPlan = $plan;
        $book = $bookPlan->book;
        $plan = new BookPlanHour();
        $plan->book_plan_id = $bookPlan->id;
        return view('control_panel.plans.basic.hours_plans.hours.create',compact('bookPlan','plan','book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newHoursRequest $request)
    {
        BookPlanHour::create($request->all());
        return response()->json(['msg'=>'تم إضافة بيانات الساعات للخطة بنجاح','title'=>'إضافة','type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BookPlanHour $planHour)
    {
        $book = $planHour->book;
        $bookPlan = $planHour->plan;
        $plan = $planHour;
        $plan_name = $planHour->plan_name;
        return view('control_panel.plans.basic.hours_plans.hours.update',compact('plan','book','bookPlan','plan_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateHoursRequest $request, BookPlanHour $planHour)
    {
        $planHour->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات ساعات الخطة بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookPlanHour $planHour)
    {
        $planHour->delete();
        return response()->json(['msg'=>'تم حذف بيانات ساعات الخطة بنجاح','title'=>'حذف','type'=>'success']);
    }
}
