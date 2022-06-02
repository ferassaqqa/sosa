<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\users\newUserRequest;
use App\Http\Requests\controlPanel\users\updateUserRequest;
use App\Models\Area;
use App\Models\CircleTeacher;
use App\Models\ContractType;
use App\Models\CourseStudent;
use App\Models\Income;
use App\Models\PersonalSkill;
use App\Models\Prefix;
use App\Models\Qualification;
use App\Models\User;
use App\Models\UserContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
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
        return view('control_panel.users.basic.index',compact('areas'));
    }
    public function getData(Request $request)
    {
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
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;
        $roles_select = $request->roles_select ? $request->roles_select : 0;

        $value = array();
        User::$counter = $start;

        if(!empty($search)){
            $count = User::subarea($sub_area_id,$area_id)
                ->userrole($roles_select)
                ->search($search)
                ->users()
                ->count();
            $users = User::subarea($sub_area_id,$area_id)->search($search)
                ->userrole($roles_select)
                ->users()
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd($search,$users);
        } else {
            $count = User::subarea($sub_area_id,$area_id)->users()
                ->userrole($roles_select)
                ->count();
            $users = User::subarea($sub_area_id,$area_id)->users()
                ->userrole($roles_select)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd($users[1]->supervisor_area);
        }
        foreach ($users as $index => $item){
            array_push($value , $item->user_display_data);
        }
//        dd($value);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_num)
    {
        $old_user = User::where('id_num',$id_num)->first();
//        dd($old_user->load('user_roles'));
        if(!$old_user){
            $user = new User();
            $user->id_num = $id_num;
            if ($user->user_basic_data){
                $roles = Role::withCount(['users' => function ($query) use ($user) {
                    $query->where('id', $user->id);
                }])->whereIn('name',['مدير الدائرة','مساعد اداري','مشرف عام','مشرف ميداني','مشرف جودة','مدير دائرة التخطيط والجودة','رئيس قسم الاختبارات'])->get();
                $create = true;
                $areas = $this->getAreasForGeneralSupervisor();
                return response()->json(['view' => view('control_panel.users.basic.create', compact('areas','user','roles','create'))->render(), 'errors' => 0]);
            } else {
                return response()->json(['view' => '', 'errors' => 1, 'msg' => 'رقم الهوية خطأ'], 404);
            }
        }else{
            if($old_user){
                if($old_user->hasRole('مدير الدائرة')||$old_user->hasRole('مساعد اداري')||$old_user->hasRole('مشرف عام')||$old_user->hasRole('مشرف ميداني')||$old_user->hasRole('مشرف جودة')||$old_user->hasRole('مدير دائرة التخطيط والجودة')||$old_user->hasRole('رئيس قسم الاختبارات')){
                    $update_link =
                        '&nbsp;<a href="#!" class="call-user-modal" onclick="callUserModal(this)" data-url="' . route('users.edit', $old_user->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">
                                    هنا
                                </a>&nbsp;';
                    return response()->json(['view' => '', 'errors' => 1, 'msg' => ' رقم الهوية موجود مسبقا انقر ' . $update_link . ' للتعديل '], 404);
                }
                if($old_user->hasRole('محفظ') || $old_user->hasRole('معلم') || $old_user->hasRole('طالب تحفيظ') || $old_user->hasRole('طالب دورات علمية')){
                    $user = $old_user;
                    $roles = Role::withCount(['users' => function ($query) use ($user) {
                        $query->where('id', $user->id);
                    }])->whereIn('name',['مدير الدائرة','مساعد اداري','مشرف عام','مشرف ميداني','مشرف جودة','مدير دائرة التخطيط والجودة','رئيس قسم الاختبارات'])->get();
                    $create = true;
                    $areas = $this->getAreasForGeneralSupervisor();

                    return response()->json(['view' => view('control_panel.users.basic.create', compact('areas','user','roles','create'))->render(), 'errors' => 0]);
//                    dd($old_user->toArray());
                }else {
                    $update_link =
                        '&nbsp;<a href="#!" class="call-user-modal" onclick="callUserModal(this)" data-url="' . route('users.edit', $old_user->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">
                                    هنا
                                </a>&nbsp;';
                    return response()->json(['view' => '', 'errors' => 1, 'msg' => ' رقم الهوية موجود مسبقا انقر ' . $update_link . ' للتعديل '], 404);
                }
            }
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newUserRequest $request)
    {
        $old_user = User::where('id_num',$request->id_num)->first();
        if($old_user){
            if ($request->has('role_id')) {
                $role_name = $request->role_id;
                $old_user->assignRole($role_name);
//                dd($request->all(),$role_name == 'مشرف ميداني',$role_name,'مشرف ميداني');
                switch ($role_name) {
                    case 'مشرف عام':
                        {
                            if ($request->area_id) {
                                $area = Area::find($request->area_id);
                                $area->update(['area_supervisor_id' => $old_user->id]);
                                $old_user->update(['supervisor_area_id' => $request->area_id,'place_id'=>$area->first_place_id]);
                            }
                        }
                        break;
                    case 'مشرف ميداني':
                        {
                            if ($request->sub_area_id) {
                                $area = Area::find($request->sub_area_id);
                                $area->update(['sub_area_supervisor_id' => $old_user->id]);
                                $old_user->update(['supervisor_area_id' => $request->sub_area_id,'place_id'=>$area->first_place_id]);
                            }
                        }
                        break;
                }
//                dd($user->supervisor_area_id);
            }

            return response()->json(['msg' => 'تم اضافة مستخدم جديد', 'title' => 'اضافة', 'type' => 'success']);
        }else {
            $user = new User();
            $user->id_num = $request->id_num;
            if ($user->user_basic_data) {
//            dd($user);
                $request->password = $request->password ? $request->password : $request->id_num;
                $password = array_merge(['password' => Hash::make($request->password)], $user->toArray());
                $user = User::create(array_merge(
                        $request->only(
                            'id_num', 'prefix'), $password)
                );
                if ($request->has('email') || $request->has('mobile')) {
                    $user->userExtraData()->create($request->only('email', 'mobile'));
                }
                if ($request->has('role_id')) {
                    $role_name = $request->role_id;
                    $user->assignRole($role_name);
//                dd($request->all(),$role_name == 'مشرف ميداني',$role_name,'مشرف ميداني');
                    switch ($role_name) {
                        case 'مشرف عام':
                            {
                                if ($request->area_id) {
                                    $area = Area::find($request->area_id);
                                    $area->update(['area_supervisor_id' => $user->id]);
                                    $place_id = $area->first_place_id ? $area->first_place_id : null;
                                    $user->update(['supervisor_area_id' => $request->area_id,'place_id'=>$place_id]);
                                }
                            }
                            break;
                        case 'مشرف ميداني':
                            {
                                if ($request->sub_area_id) {
                                    $area = Area::find($request->sub_area_id);
                                    $area->update(['sub_area_supervisor_id' => $user->id]);
                                    $place_id = $area->first_place_id ? $area->first_place_id : null;
                                    $user->update(['supervisor_area_id' => $request->sub_area_id,'place_id'=>$place_id]);
                                }
                            }
                            break;
                    }
//                dd($user->supervisor_area_id);
                }
                return response()->json(['msg' => 'تم اضافة مستخدم جديد', 'title' => 'اضافة', 'type' => 'success']);
            } else {
                return response()->json(['msg' => 'رقم الهوية غير صحيح', 'title' => 'خطأ !', 'type' => 'danger']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $tr = $user->show_user_data;
        $title = 'بيانات المستخدم';
        return view('control_panel.showModel',compact('tr','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::withCount(['users' => function ($query) use ($user) {
            $query->where('id', $user->id);
        }])->whereIn('name',['مدير الدائرة','مساعد اداري','مشرف عام','مشرف ميداني','مشرف جودة','مدير دائرة التخطيط والجودة','رئيس قسم الاختبارات'])->get();
        $father_area_id = 0;
        $sub_areas = '';
        $edit = false;
        if($user->hasRole('مشرف عام')){
            $father_area_id = $user->area_supervisor_area_id;
            $edit = true;
        }elseif ($user->hasRole('مشرف ميداني')){
            $area = Area::find($user->sub_area_supervisor_area_id);
            $father_area_id = $area ? $area->area_father_id : 0;
//            dd($area_id);
            $sub_areas = $this->getSubAreasForAreaSupervisor(Area::find($father_area_id),$user->sub_area_supervisor_area_id);
            $edit = true;
        }
//        $create = true;
        $areas = $this->getAreasForGeneralSupervisor($father_area_id);
//        dd($sub_areas);
        return view('control_panel.users.basic.update', compact('user','roles','edit','areas','sub_areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateUserRequest $request, User $user)
    {
        if ($user->user_basic_data) {
            $place_id = $user->place_id;
            if ($request->has('role_id')) {

                $user->update(array_merge(
                    $request->only(
                        'prefix'),
                    [
                        'password'=>Hash::make($request->id_num)
                    ]
                ));
                $role_name = $request->role_id;
                $user->assignRole($role_name);
//                dd($request->all(),$role_name == 'مشرف ميداني',$role_name,'مشرف ميداني');
                switch ($role_name) {
                    case 'مشرف عام':
                        {
                            if ($request->area_id) {
                                $area = Area::find($request->area_id);
                                $area->update(['area_supervisor_id' => $user->id]);
                                $place_id = $area->first_place_id ? $area->first_place_id : $place_id;
                                $user->update([
                                    'supervisor_area_id' => $request->area_id,
                                    'place_id'=>$place_id
                                ]);
                            }
                        }
                        break;
                    case 'مشرف ميداني':
                        {
                            if ($request->sub_area_id) {
                                $area = Area::find($request->sub_area_id);
                                $area->update(['sub_area_supervisor_id' => $user->id]);
                                $place_id = $area->first_place_id ? $area->first_place_id : $place_id;
//                                dd($area->first_place_id);
                                $user->update([
                                    'supervisor_area_id' => $request->sub_area_id,
                                    'place_id'=>$place_id
                                ]);
                            }
                        }
                        break;
                }
//                dd($user->supervisor_area_id);
            }

            if ($request->has('email') || $request->has('mobile')) {
                if ($user->userExtraData) {
                    $user->userExtraData->update($request->only('email', 'mobile'));
                } else {
                    $user->userExtraData()->create($request->only('email', 'mobile'));
                }
            }
            //        if($request->has('role_id')){
            //            $role_name = $request->only('role_id');
            //            $user->syncRoles($role_name);
            //        }
            return response()->json(['msg' => 'تم تعديل بيانات المستخدم بنجاح', 'title' => 'تعديل', 'type' => 'success']);
        }else{
            return response()->json(['msg' => 'رقم الهوية المدخل خطأ .', 'title' => 'خطأ !', 'type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
//        dd();
        $roles = ['مساعد اداري','مشرف عام','مشرف ميداني','مشرف جودة','مدير دائرة التخطيط والجودة','رئيس قسم الاختبارات'];
        if($user->user_roles->count() == 1 && in_array($user->roles[0]->name,$roles)){
            $area = Area::where('area_supervisor_id', $user->id)->orWhere('sub_area_supervisor_id', $user->id)->first();
            if ($area) {
                $area->update(['area_supervisor_id' => null, 'sub_area_supervisor_id' => null]);
                $user->update(['place_id' => null]);
            }
//            $user->delete();
        }else {
            if(in_array($user->roles[0]->name,$roles)) {
                if($user->hasRole('مشرف عام')){
                    $user->removeRole('مشرف عام');
                }
                if($user->hasRole('مشرف ميداني')){
                    $user->removeRole('مشرف ميداني');
                }
                if($user->hasRole('مشرف جودة')){
                    $user->removeRole('مشرف جودة');
                }
                if($user->hasRole('مدير دائرة التخطيط والجودة')){
                    $user->removeRole('مدير دائرة التخطيط والجودة');
                }
                if($user->hasRole('رئيس قسم الاختبارات')){
                    $user->removeRole('رئيس قسم الاختبارات');
                }
                if($user->hasRole('مساعد اداري')){
                    $user->removeRole('مساعد اداري');
                }
//                if($user->hasRole('مدير الدائرة')){
//                    $user->removeRole('مدير الدائرة');
//                }
            }
        }
        return response()->json(['msg'=>'تم حذف بيانات المستخدم بنجاح','title'=>'حذف','type'=>'success']);
    }
    public function getCourses(User $user){
        return view('control_panel.users.courseStudents.showStudentCourses',compact('user'));
    }
    public function getPassedCourses(User $user){
        $passed = 1;
        return view('control_panel.users.courseStudents.showStudentCourses',compact('user','passed'));
    }
    public function getFailedCourses(User $user){
        $failed = 1;
        return view('control_panel.users.courseStudents.showStudentCourses',compact('user','failed'));
    }
    public function getAreasForGeneralSupervisor($father_id = 0){
        $options = '';
//        dd($selected);
//        ->whereNull('area_supervisor_id')
        $areas = Area::whereNull('area_id')->get();
        foreach($areas as $key => $area) {
            if($area->id == $father_id) {
                $options .= '<option value="' . $area->id . '" selected>' . $area->name . '</option>';
            }else{
                $options .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
        }
           return '<td class="center-align" id="area_label" colspan="2" style="background-color: #f9fafb;">المنطقة الكبرى:</td>
            <td colspan="2" class="father_area" id="area_select">
                <select class="form-control" name="area_id">
                    <option value="0">-- اختر --</option>
                    '.$options.'
                </select>
            </td>';
    }
    public function getSubAreasForAreaSupervisor(Area $area,$father_id = 0){
        $options = '';
//        ->whereNull('sub_area_supervisor_id')
        $areas = Area::where('area_id',$area->id)->get();
//        dd($areas->toArray());
        foreach($areas as $key => $area_data) {
            if($area_data->id == $father_id) {
                $options .= '<option value="'.$area_data->id.'" selected>' . $area_data->name . '</option>';
            }else{
                $options .= '<option value="'.$area_data->id.'">' . $area_data->name . '</option>';
            }
        }
        if($father_id){
            return $options;
        }
//        dd($options);
           return '<td class="center-align" colspan="2" style="background-color: #f9fafb;">المنطقة المحلية:</td>
            <td colspan="2">
                <select class="form-control" name="sub_area_id">
                    <option value="0">-- اختر --</option>
                    '.$options.'
                </select>
            </td>';
    }
}
