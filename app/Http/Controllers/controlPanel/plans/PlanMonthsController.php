<?php

namespace App\Http\Controllers\controlPanel\plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\plans\yearly\newMonthRequest;
use App\Http\Requests\controlPanel\plans\yearly\updateMonthRequest;
use App\Models\BookPlanYearSemester;
use App\Models\BookPlanYearSemesterMonth;
use Illuminate\Http\Request;

class PlanMonthsController extends Controller
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
    public function create(BookPlanYearSemester $planSemester)
    {
        $plan = new BookPlanYearSemesterMonth();
        $plan->book_plan_year_semester_id = $planSemester->id;
        return view('control_panel.plans.basic.year_plans.months.create',compact('planSemester','plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newMonthRequest $request)
    {
        BookPlanYearSemesterMonth::create($request->all());
        return response()->json(['msg'=>'تم اضافة بيانات الشهر بنجاح','title'=>'اضافة','type'=>'success']);
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
    public function edit(BookPlanYearSemesterMonth $planMonth)
    {
//        dd($planMonth->book);
        $book = $planMonth->book;
        $plan = $planMonth;
        $plan_name = $planMonth->plan_name;
        return view('control_panel.plans.basic.year_plans.months.update',compact('plan','book','plan_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateMonthRequest $request, BookPlanYearSemesterMonth $planMonth)
    {
        $planMonth->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات الشهر بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookPlanYearSemesterMonth $planMonth)
    {
        $planMonth->delete();
        return response()->json(['msg'=>'تم حذف بيانات الشهر بنجاح','title'=>'حذف','type'=>'success']);
    }
}
