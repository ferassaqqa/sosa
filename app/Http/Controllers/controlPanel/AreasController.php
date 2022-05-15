<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Areas\newAreaRequest;
use App\Http\Requests\controlPanel\Areas\updateAreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('control_panel.settings.areas.basic.index');

    }
    public function getData(Request $request)
    {
//        dd($areas);
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'tools',      'dt' => 2 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = Area::whereNull('area_id')
                ->search($search)
                ->count();
            $areas = Area::whereNull('area_id')
                ->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Area::whereNull('area_id')
                ->count();
            $areas = Area::whereNull('area_id')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        foreach ($areas as $index => $item){
            array_push($value , $item->area_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
//        return $areas;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $area = new Area();
        return view('control_panel.settings.areas.basic.create',compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAreaRequest $request)
    {
        $area = Area::create(['name'=>$request->name,'percentage'=>$request->area_percentage]);
        if(isset($request->subArea)&& count($request->subArea)) {
            $values = [];
            foreach ($request->subArea as $key => $value) {
                $percentage = $request->percentage ?
                    (count($request->percentage) ? (isset($request->percentage[$key]) ? $request->percentage[$key] : null) : null )
                    : null;
                $values[$key]['name'] = $value;
                $values[$key]['percentage'] = $percentage;
            }
            $area->subArea()->createMany($values);
        }
        return response()->json(['msg'=>'تم اضافة بيانات منطقة جديدة','title'=>'اضافة','type'=>'success']);
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
    public function edit(Area $area)
    {
        $area->load('subArea');
        return view('control_panel.settings.areas.basic.update',compact('area'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAreaRequest $request, Area $area)
    {
//        dd($request->subArea);
        $area->update(['name'=>$request->name,'percentage'=>$request->area_percentage]);
        if(isset($request->subArea)&& count($request->subArea)) {
            $values = [];
            foreach ($request->subArea as $key => $value) {
                if(isset($request->subArea_id[$key]) && $request->subArea_id[$key]){
                    $sub_area = Area::find($request->subArea_id[$key]);
                    if($sub_area){
                        $sub_area->update([
                            'name'=>$value,
                            'percentage'=>($request->percentage ?
                                (count($request->percentage) ? (isset($request->percentage[$key]) ? $request->percentage[$key] : null) : null )
                                : null),
                            ]);
                    }
                }else {
                    $values[$key]['name'] = $value;
                }
            }
            if(count($values)) {
                $area->subArea()->createMany($values);
            }
        }
        return response()->json(['msg' => 'تم تعديل بيانات المنطقة بنجاح', 'title' => 'تعديل', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return response()->json(['msg' => 'تم حذف البيانات بنجاح.', 'title' => 'حذف', 'type' => 'success']);
    }

    public function checkAreaTotalPercentage($area_id,$percentage){
        return checkAreaTotalPercentage($area_id,$percentage);
    }
    public function getSubAreas(Area $area){
        return getSubAreas($area->id);
    }
    public function getSubAreaPlaces(Area $area){
        return getSubAreaPlaces($area->id);
    }

    public function deleteSubArea(Area $area)
    {
        $area->delete();
        return response()->json(['msg' => 'تم حذف البيانات بنجاح', 'title' => 'حذف', 'type' => 'success']);
    }
    public function showDeletedSubAreaItem(Area $area)
    {
        return view('control_panel.settings.areas.restoreSubAreaView',compact('area'));
    }
    public function restoreSubArea(Area $area){
        $area->restore();
        return response()->json(['msg' => 'تم استرجاع البيانات  بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
    public function showDeletedItem(Area $area){
        return view('control_panel.settings.areas.restoreItemView',compact('area'));
    }
    public function deleteSelected(Request $request){
        Area::destroy($request->ids);
//        return $request;
        return response()->json(['msg' => 'تم حذف كافة البيانات المحددة بنجاح.', 'title' => 'حذف', 'type' => 'success']);
    }
    public function deletedItems(){
        return view('control_panel.settings.areas.deletedIndex');
    }
    public function deletedItemsData(Request $request){
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'select',      'dt' => 2 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = Area::whereNull('area_id')
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->whereNull('area_id')
                        ->orWhere('name', 'like', "%" . $search . "%")
                        ->orWhereHas('subArea',function($query) use ($search){
                            $query->where('name','like','%'. $search .'%');
                        });
                })
                ->get()
                ->count();
            $areas = Area::whereNull('area_id')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->orWhere('name', 'like', "%" . $search . "%")
                        ->orWhereHas('subArea',function($query) use ($search){
                            $query->where('name','like','%'. $search .'%');
                        });
                })
                ->get();
        } else {
            $count = Area::whereNull('area_id')
                ->count();
            $areas = Area::whereNull('area_id')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->onlyTrashed()->get();
        }
        foreach ($areas as $index => $item){
            if(!empty($search)){
                $item->load(['subArea'=>function($query) use ($search){
                    $query->where('name','like','%'. $search .'%');
                }]);
                if($item->subArea->count()) {
                    array_push($value, $item->searchedAreaDisplayData($item->subArea[0]->name));
                }else{
                    array_push($value, $item->searched_area_display_data);
                }
            }else{
                array_push($value , $item->area_display_data);
            }
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function restoreSelected(Request $request){
        Area::onlyTrashed()->whereIn('id',$request->ids)->get()->each(function($item){
            $item->restore();
        });
//        return $request;
        return response()->json(['msg' => 'تم استرجاع كافة البيانات المحددة بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
    public function restoreItem(Area $area){
        $area->restore();
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
}
