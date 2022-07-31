<?php

namespace App\Http\Controllers\controlPanel\plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\plans\yearly\newYearRequest;
use App\Http\Requests\controlPanel\plans\yearly\updateYearRequest;
use App\Models\BookPlan;
use App\Models\BookPlanYear;
use Illuminate\Http\Request;

class PlanYearController extends Controller
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
        $plan = new BookPlanYear();
        $plan->book_plan_id = $bookPlan->id;
        return view('control_panel.plans.basic.year_plans.years.create',compact('bookPlan','plan','book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newYearRequest $request)
    {
        BookPlanYear::create($request->all());
        return response()->json(['msg'=>'تم إضافة بيانات السنة بنجاح','title'=>'إضافة','type'=>'success']);
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
    public function edit(BookPlanYear $planYear)
    {
        $book = $planYear->book;
        $plan = $planYear;
        $plan_name = $planYear->plan_name;
        return view('control_panel.plans.basic.year_plans.years.update',compact('plan','book','plan_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateYearRequest $request, BookPlanYear $planYear)
    {
        $planYear->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات السنة بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookPlanYear $planYear)
    {
        $planYear->delete();
        return response()->json(['msg'=>'تم حذف بيانات السنة بنجاح','title'=>'حذف','type'=>'success']);
    }
}
