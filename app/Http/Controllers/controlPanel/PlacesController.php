<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Places\newPlaceRequest;
use App\Http\Requests\controlPanel\Places\updatePlaceRequest;
use App\Models\Area;
use App\Models\Circle;
use App\Models\Place;
use Illuminate\Http\Request;

class PlacesController extends Controller
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
        return view('control_panel.settings.areas.places.basic.index');
    }
    public function getData(Request $request)
    {
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'area_id',      'dt' => 2 ),
            array( 'db' => 'tools',      'dt' => 3 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = Place::select('id','name','area_id')
                ->search($search)
                ->count();
            $places = Place::select('id','name','area_id')
                ->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Place::select('id','name','area_id')->count();
            $places = Place::select('id','name','area_id')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        foreach ($places as $index => $item){
            array_push($value , $item->place_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
//        return $places;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $place = new Place();
        return view('control_panel.settings.areas.places.basic.create',compact('place'));
    }
    public function searchAreaForPlaces($search,$count)
    {
        $areas_results = '';
        $areas = Area::
            whereNotNull('area_id')
            ->search($search)
            ->get();
        foreach($areas as $area){
            $areas_results .= $area->area_searched_result_for_place;
        }
        return [$areas_results,$count];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newPlaceRequest $request)
    {
        Place::create($request->all());
        return response()->json(['msg'=>'تم اضافة مكان (مسجد) جديد','title'=>'اضافة','type'=>'success']);
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
    public function edit(Place $place)
    {
        return view('control_panel.settings.areas.places.basic.update',compact('place'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updatePlaceRequest $request, Place $place)
    {
//        dd($request->area_id);
        $place->update([
                'name'=>$request->name,
                'area_id'=>$request->area_id
            ]);
//        dd($place);
        return response()->json(['msg'=>'تم تعديل بيانات المكان (مسجد) بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        $place->delete();
        return response()->json(['msg'=>'تم حذف بيانات المكان (مسجد) بنجاح','title'=>'حذف','type'=>'success']);
    }
    public function getPlaceAreaSupervisorForCircles(Place $place,Circle $circle)
    {
        return getPlaceAreaSupervisorForCircles($place->area_father_id,$circle->supervisor_id);
    }
}
