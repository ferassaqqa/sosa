<?php

namespace App\Http\Controllers\controlPanel\plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\plans\yearly\newMonthRequest;
use App\Http\Requests\controlPanel\plans\yearly\newSemesterRequest;
use App\Http\Requests\controlPanel\plans\yearly\updateSemesterRequest;
use App\Models\BookPlanYear;
use App\Models\BookPlanYearSemester;
use Illuminate\Http\Request;

class PlanSemestersController extends Controller
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
    public function create(BookPlanYear $planYear)
    {
        $plan = new BookPlanYearSemester();
        $plan->book_plan_year_id = $planYear->id;
        return view('control_panel.plans.basic.year_plans.semesters.create',compact('planYear','plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newSemesterRequest $request)
    {
        BookPlanYearSemester::create($request->all());
        return response()->json(['msg'=>'تم حفظ بيانات الفصل بنجاح','title'=>'اضافة','type'=>'success']);
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
    public function edit(BookPlanYearSemester $planSemester)
    {

        $book = $planSemester->book;
        $plan = $planSemester;
        $plan_name = $planSemester->plan_name;
        return view('control_panel.plans.basic.year_plans.semesters.update',compact('plan','book','plan_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateSemesterRequest $request, BookPlanYearSemester $planSemester)
    {
        $planSemester->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات الفصل بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookPlanYearSemester $planSemester)
    {
        $planSemester->delete();
        return response()->json(['msg'=>'تم حذف بيانات الفصل بنجاح','title'=>'حذف','type'=>'success']);
    }
}
