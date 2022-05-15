<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class activitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function activities(){
        return view('control_panel.activities.index');
    }
    public function activitiesData(Request $request)
    {
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'description', 'dt' => 1),
            array('db' => 'causer_id', 'dt' => 2),
            array('db' => 'subject_id', 'dt' => 3),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);
        $status = $request->status ? $request->status : '';
        $start_date = $request->start_date ? $request->start_date : 0;
        $end_date = $request->end_date ? $request->end_date : 0;

        $value = array();

        Activity::$counter = 0;

        if(!empty($search)){
            $count = Activity::where('description', 'like', "%" . $search . "%")
//                ->orWhereHas('causer',function($query) use ($search){
//                    $query->search($search);
//                })
//                ->orWhereHas('subject',function($query) use ($search){
////                    $query->search($search);
//                })
                ->filteractivities($status,$start_date,$end_date)
                ->count();
            $activities = Activity::where('description', 'like', "%" . $search . "%")
//                ->orWhereHas('causer',function($query) use ($search){
//                    $query->search($search);
//                })
//                ->orWhereHas('subject',function($query) use ($search){
////                    $query->search($search);
//                })
                ->filteractivities($status,$start_date,$end_date)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Activity::
                filteractivities($status,$start_date,$end_date)->count();
            $activities = Activity::
                limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->filteractivities($status,$start_date,$end_date)
                ->get();
//            dd($activities);
        }
        foreach ($activities as $index => $item) {
//            dd($index,$item);
            array_push(
                $value,$item->activities_display_data
            );
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
//        return $users;
    }
    public function showActivityModel(Activity $activity){

        $changes = $activity->changes->toArray();
        switch ($activity->log_name){
            case 'created':{
                $view = $activity->ShowChanges($changes['attributes']);
            }break;
            case 'updated':{
                $view = $activity->ShowChanges($changes['attributes'],$changes['old']);
            }break;
            case 'deleted':{
                $view = $activity->ShowChanges($changes['attributes']);
            }break;
        }
        $attributes_label = $activity->log_name == 'updated' ? 'البيانات بعد التعديل' :
            ($activity->log_name == 'created' ? 'البيانات بعد الاضافة' : 'البيانات قبل الحذف');
//        dd($view);
        return view('control_panel.activities.showChanges',compact('view','activity','attributes_label'));
    }
    public function undoCreated(Activity $activity){
        if($activity->subject){
            $activity->subject->delete();
            return response()->json(['msg'=>'تم التراجع عن الإضافة','title'=>'تراجع','type'=>'success']);
        }else{
            return response()->json(['msg'=>'تم التراجع عن الإضافة مسبقاّ','title'=>'خطأ','type'=>'danger']);
        }
    }
    public function undoUpdated(Activity $activity){
//        dd($activity->changes['attributes']);
        $activity->subject->update($activity->changes['old']);
        return response()->json(['msg'=>'تم التراجع عن التعديل','title'=>'تراجع','type'=>'success']);
    }
    public function undoDeleted(Activity $activity){
//        dd($activity->subject_type);
        $model = $activity->subject_type;
        $modelData = app($model)->where('id',$activity->changes['attributes']['id'])->first();
//        dd($modelData);
        if(!$modelData) {
            $deletedModel = new $model($activity->changes['attributes']);
            $deletedModel->id = $activity->changes['attributes']['id'];
            $deletedModel->save();
            return response()->json(['msg' => 'تم التراجع عن الحذف', 'title' => 'تراجع', 'type' => 'success']);
        }else{
            return response()->json(['msg'=>'تم التراجع عن الحذف مسبقاّ','title'=>'تراجع','type'=>'danger']);
        }
    }
}
