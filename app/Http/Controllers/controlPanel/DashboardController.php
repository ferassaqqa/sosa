<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        checkPermissionHelper('تصفح احصائيات البرنامج');
        $areas = Area::whereNull('area_id')->get();
        $moallems = User::department(2)->count();
        $courses = Course::count();
        $course_students_count = CourseStudent::whereHas('course',function ($query){
                $query->whereHas('placeForPermissions', function ($query) {
//                $query->whereHas('areaForPermissions');
                });
        })->count();
        return view('control_panel.dashboard.dashboard',compact('areas','moallems','courses','course_students_count'));
    }
    public function updateCourseAndStudentsStatisticsInDashboard(Request $request){
        $courses =
            Course::subarea($request->dashboard_sub_areas_id,$request->dashboard_area_id)
                ->status($request->status)
                ->count();
        $course_students_count =
            CourseStudent::subarea($request->dashboard_sub_areas_id,$request->dashboard_area_id)
                ->course($request->status)
                ->count();
        $moallems =
            User::subarea($request->dashboard_sub_areas_id,$request->dashboard_area_id)
                ->course($request->status)
                ->department(2)->count();
        return view('control_panel.dashboard.courseAndStudentsFilter',compact('request','moallems','courses','course_students_count'));
//        dd($request->all());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
