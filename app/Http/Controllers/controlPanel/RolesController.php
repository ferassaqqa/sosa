<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Roles\newRoleReuest;
use App\Http\Requests\controlPanel\Roles\updateRoleReuest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
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
        //checkPermissionHelper('تصفح جميع الادوار');
        return view('control_panel.settings.users.roles.basic.index');
    }
    public function getData(Request $request)
    {
        //checkPermissionHelper('تصفح جميع الادوار');
//        dd($roles);
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
            $count = Role::select('id','name')
                ->where('id', 'like', "%" . $search . "%")
                ->orWhere('name', 'like', "%" . $search . "%")->count();
            $roles = Role::select('id','name')
                ->where('id', 'like', "%" . $search . "%")
                ->orWhere('name', 'like', "%" . $search . "%")
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Role::select('id','name')->count();
            $roles = Role::select('id','name')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        foreach ($roles as $index => $item){
            array_push($value , $item->role_display_data);
//            array_push($value , array('id'=>$item->id,'name'=>$item->name,'permissions'=>0,'tools'=>''));
        }
//        dd($value,$roles);

//        dd($draw,$start,$length,$order,$direction,$search);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
//        return $roles;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //checkPermissionHelper('اضافة دور جديد');
        $role = new Role();
        return view('control_panel.settings.users.roles.basic.create',compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newRoleReuest $request)
    {
        //checkPermissionHelper('اضافة دور جديد');
        $role = Role::create($request->all());
        return response()->json(['msg'=>'تم اضافة دور جديد','title'=>'رسالة','type'=>'success']);
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
    public function edit(Role $role)
    {
        //checkPermissionHelper('تعديل بيانات دور');
        return view('control_panel.settings.users.roles.basic.update',compact('role'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateRoleReuest $request, Role $role)
    {
        //checkPermissionHelper('تعديل بيانات دور');
        $role->update($request->all());
        return response()->json(['msg' => 'تم تعديل بيانات الدور بنجاح', 'title' => 'تعديل', 'type' => 'success']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //checkPermissionHelper('حذف بيانات دور');
//        $role->delete();
//        return response()->json(['msg' => 'تم حذف بيانات الدور بنجاح.', 'title' => 'حذف', 'type' => 'success']);
        return response()->json(['msg' => 'حذف الأدوار متوقف يرجى مراجعة المبرمج.', 'title' => 'حذف', 'type' => 'danger']);
    }
    public function showDeletedItem(Role $role){
        //checkPermissionHelper('تصفح بيانات دور محذوف');
        return view('control_panel.settings.users.roles.restoreItemView',compact('role'));
    }
    public function deleteSelected(Request $request){
        //checkPermissionHelper('حذف الادوار المحددة');
        Role::destroy($request->ids);
//        return $request;
        return response()->json(['msg' => 'تم حذف كافة البيانات المحددة بنجاح.', 'title' => 'حذف', 'type' => 'success']);
    }
    public function deletedItems(){
        //checkPermissionHelper('تصفح بيانات الادوار المحذوفة');
        return view('control_panel.settings.users.roles.deletedIndex');
    }
    public function deletedItemsData(Request $request){
        //checkPermissionHelper('تصفح بيانات الادوار المحذوفة');
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = Role::select('id','name')
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->orWhere('name', 'like', "%" . $search . "%");
                })
                ->get()
                ->count();
            $roles = Role::select('id','name')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->orWhere('name', 'like', "%" . $search . "%");
                })
                ->get();
        } else {
            $count = Role::select('id','name')->onlyTrashed()->count();
            $roles = Role::select('id','name')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->onlyTrashed()->get();
        }
        foreach ($roles as $index => $item){
            array_push($value , $item->role_display_data);
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
        //checkPermissionHelper('استرجاع الادوارالمحذوفة المحددة');
        Role::onlyTrashed()->whereIn('id',$request->ids)->get()->each(function($item){
            $item->restore();
        });
        return response()->json(['msg' => 'تم استرجاع كافة البيانات المحددة بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
    public function restoreItem(Role $role){
        //checkPermissionHelper('استرجاع بيانات دور محذوف');
        $role->restore();
        return response()->json(['msg' => 'تم استرجاع بيانات الدور بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
    public function permissions(Role $role){
        //checkPermissionHelper('تصفح صلاحيات الدور');
        $permissionsBag = Permission::withCount(['roles'=>function($query) use($role){
            $query->where('id',$role->id);
        }])->get()->groupBy(['department','title']);
//        dd($permissionsBag);
        return view('control_panel.settings.users.roles.rolePermissions',compact('role','permissionsBag'));
    }
    public function updatePermissions(Request $request,Role $role){
        //checkPermissionHelper('تصفح صلاحيات الدور');
//        dd($role->name,$role->name != 'رئيس الدائرة');
//        if($role->name != 'رئيس الدائرة' && $role->name != 'مدير الدائرة') {
//        dd($request->permissions,$role->permissions->pluck('id')->toArray());
            $role->revokePermissionTo($role->permissions);
            if ($request->permissions) {
                $role->givePermissionTo($request->permissions);
            }
            return response()->json(['msg' => 'تم تحديث صلاحيات الدور بنجاح.', 'title' => 'تحديث', 'type' => 'success']);
//        }else{
//            return response()->json(['msg' => 'لا يمكن تحديث صلاحيات '.$role->name.'.', 'title' => 'خطأ !', 'type' => 'danger']);
//        }
    }
}
