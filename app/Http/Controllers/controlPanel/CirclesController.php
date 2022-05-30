<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Circles\newCircleRequest;
use App\Http\Requests\controlPanel\Circles\updateCircleRequest;
use App\Models\Area;
use App\Models\Circle;
use App\Models\CircleDate;
use App\Models\CircleTeacher;
use App\Models\UserExtraData;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CirclesController extends Controller
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
        $areas = Area::whereNull('area_id')->get();
        return view('control_panel.circles.basic.index',compact('areas'));
    }
    public function getData(Request $request)
    {
//        dd($request->all());
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'teacher_id',      'dt' => 1 ),
            array( 'db' => 'start_date',      'dt' => 2 ),
            array( 'db' => 'supervisor_id',      'dt' => 3 ),
            array( 'db' => 'supervisor_id',      'dt' => 4 ),
            array( 'db' => 'place_id',      'dt' => 5 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;

        $value = array();

        $mohafez_makfool =    Circle::query()
                                ->rightJoin('users', 'users.id', '=', 'circles.teacher_id')
                                ->rightJoin('user_extra_data', 'user_extra_data.user_id', '=', 'circles.teacher_id')
                                ->where('user_extra_data.contract_type' , '=', 'مكفول')
                                ->groupBy('users.id')
                                ->select('users.id')->subarea($sub_area_id,$area_id)
                                ->get()->count();



        $mohafez_volunteer =  Circle::query()
                                ->rightJoin('users', 'users.id', '=', 'circles.teacher_id')
                                ->rightJoin('user_extra_data', 'user_extra_data.user_id', '=', 'circles.teacher_id')
                                ->where('user_extra_data.contract_type' , '=', 'متطوع')
                                ->groupBy('users.id')
                                ->select('users.id')->subarea($sub_area_id,$area_id)
                                ->get()->count();

        $total_mohafez_count = $mohafez_makfool + $mohafez_volunteer;

        $circle_volunteer = Circle::query()
                                ->leftJoin('users', 'users.id', '=', 'circles.teacher_id')
                                ->leftJoin('user_extra_data', 'user_extra_data.user_id', '=', 'circles.teacher_id')
                                ->where('user_extra_data.contract_type' , '=', 'متطوع')
                                ->groupBy('circles.id')
                                ->select('users.id')->subarea($sub_area_id,$area_id)
                                ->get()->count();


        $circle_makfool = Circle::query()
                                ->leftJoin('users', 'users.id', '=', 'circles.teacher_id')
                                ->leftJoin('user_extra_data', 'user_extra_data.user_id', '=', 'circles.teacher_id')
                                ->where('user_extra_data.contract_type' , '=', 'مكفول')
                                ->groupBy('circles.id')
                                ->select('users.id')->subarea($sub_area_id,$area_id)
                                ->get()->count();




        $total_circlestudents_count =   User::department(3)->subarea($sub_area_id,$area_id)->count();
        $total_circlestudents_makfool = User::query()
                                                ->department(3)
                                                ->rightJoin('user_extra_data', function($join){
                                                    $join->on('user_extra_data.user_id', '=', 'users.teacher_id');
                                                    $join->where('user_extra_data.contract_type' , '=', 'مكفول');
                                                })
                                                ->select('users.*')->subarea($sub_area_id,$area_id)
                                                ->get()->count();

        $total_circlestudents_volunteer =  User::query()
                                                ->department(3)
                                                ->rightJoin('user_extra_data', function($join){
                                                    $join->on('user_extra_data.user_id', '=', 'users.teacher_id');
                                                    $join->where('user_extra_data.contract_type' , '=', 'متطوع');
                                                })
                                                ->select('users.*')->subarea($sub_area_id,$area_id)
                                                ->get()->count();




        if(!empty($search)){
            $count = Circle::search($search)
                ->subarea($sub_area_id,$area_id)
                ->count();
            $circles = Circle::search($search)
                ->subarea($sub_area_id,$area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Circle::subarea($sub_area_id,$area_id)->count();
            $circles = Circle::limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->subarea($sub_area_id,$area_id)
                ->get();
        }
        foreach ($circles as $index => $item){
            array_push($value , $item->circle_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],

            'total_mohafez_count'  => '('.$total_mohafez_count.')',
            'mohafez_makfool' => '('.$mohafez_makfool.')',
            'mohafez_volunteer' => '('.$mohafez_volunteer.')',

            'total_circle_count'  => '('.$count.')',
            'circle_makfool' => '('.$circle_makfool.')',
            'circle_volunteer' => '('.$circle_volunteer.')',

            'total_circlestudents_count'  => '('.$total_circlestudents_count.')',
            'total_circlestudents_makfool' => $total_circlestudents_makfool,
            'total_circlestudents_volunteer' => $total_circlestudents_volunteer,

        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $circle = new Circle();
        $areas = Area::whereNull('area_id')->get();
        return view('control_panel.circles.basic.create',compact('circle','areas'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCircleRequest $request)
    {
        $circle = Circle::create($request->only('start_date','place_id','teacher_id','supervisor_id','notes'));
        return response()->json(['msg'=>'تم اضافة حلقة جديدة','title'=>'اضافة','type'=>'success']);
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
    public function edit(Circle $circle)
    {

        $areas = Area::whereNull('area_id')->get();
        $sub_areas = Area::where('area_id',$circle->area_father_id)->get();
        $places = Place::where('area_id',$circle->area_id)->get();
        $place = Place::findOrFail($circle->place_id);
        $teachers = getPlaceTeachersForCircles($place->area_father_id,$circle->teacher_id);
        $supervisors = getPlaceAreaSupervisorForCircles($place->area_father_id,$circle->supervisor_id);
        return view('control_panel.circles.basic.update',compact('circle','places','areas','teachers','supervisors','sub_areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateCircleRequest $request, Circle $circle)
    {
        $circle->update($request->only('start_date','place_id','teacher_id','supervisor_id','notes'));
        return response()->json(['msg'=>'تم تعديل بيانات الحلقة بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Circle $circle)
    {
        $circle->delete();
        return response()->json(['msg'=>'تم حذف بيانات الحلقة بنجاح','title'=>'حذف','type'=>'success']);
    }
    public function getCircleStudents(Circle $circle)
    {
        return view('control_panel.circles.circleStudents',compact('circle'));
    }
    public function getSubAreasOfAreaForCircles(Area $area)
    {
        $areas = '<option value="0">اختر المنطقة المحلية</option>';
        foreach ($area->subArea as $key => $subArea){
            $areas .= '<option value="'.$subArea->id.'">'.$subArea->name.'</option>';
        }
        return $areas;
    }
    public function getPlaceTeachersForCircles(Place $place,Circle $circle)
    {
        return getPlaceTeachersForCircles($place->area_father_id,$circle->teacher_id);
    }
    public function changeCircleStatus(Circle $circle,$status,$note='')
    {
        if(in_array($status,['انتظار الموافقة','قائمة','معلقة'])) {
            if($circle->students->count()) {
                if($status == 'انتظار الموافقة'){
                    if($circle->reports->count()){
                        return response()->json(['msg'=>'لا يمكن تحديث الحالة الى انتظار الموافقة ، الحلقة قائمة ويوجد تقارير شهرية.','title'=>'خطأ!','type'=>'danger']);
                    }
                }
                $circle->update([
                    'status' => $status,
                    'notes'=>$circle->notes . ' || ' .$note
                ]);
                return response()->json(['msg'=>'تم تغيير حالة الحلقة بنجاح','title'=>'الحالة','type'=>'success']);
            }else{
                return response()->json(['msg'=>'يرجى ادخال طلاب للدورة','title'=>'خطأ!','type'=>'danger']);
            }
            return response()->json(['msg'=>'تم تعديل بيانات الدورة بنجاح','title'=>'اضافة','type'=>'success']);
        }else{
            return response()->json(['msg'=>'يرجى ادخال قيمة صحيحة للحالة','title'=>'خطأ!','type'=>'danger']);
        }
    }

}
