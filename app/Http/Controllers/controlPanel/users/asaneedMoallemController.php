<?php

namespace App\Http\Controllers\controlPanel\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\asaneedMoallems\newAsaneedMoallemRequest;
use App\Http\Requests\controlPanel\asaneedMoallems\updateAsaneedMoallemRequest;
use App\Models\Area;
use App\Models\Place;
use App\Models\User;
use App\Models\UserNote;
use App\Models\UserOldCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class asaneedMoallemController extends Controller
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
        return view('control_panel.users.asaneedMoallem.basic.index',compact('areas'));
    }
    public function getData(Request $request)
    {
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'place_id',      'dt' => 2 ),
            array( 'db' => 'place_id',      'dt' => 3 ),
            array( 'db' => 'place_id',      'dt' => 4 ),
            array( 'db' => 'place_id',      'dt' => 5 ),
            array( 'db' => 'place_id',      'dt' => 6 ),
            array( 'db' => 'place_id',      'dt' => 7 ),
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

        if(!empty($search)){
            $count = User::subarea($sub_area_id,$area_id)
                ->search($search)
                ->department(7)
                ->count();
            $users = User::subarea($sub_area_id,$area_id)
                ->search($search)
                ->department(7)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::subarea($sub_area_id,$area_id)
                ->department(7)
                ->count();
            $users = User::subarea($sub_area_id,$area_id)
                ->department(7)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd(Auth::user()->assignRole('رئيس الدائرة'));
        }
        User::$counter = $start;
        foreach ($users as $index => $item){
            $item->setDepartmentValue(7);
            array_push($value , $item->asaneed_moallem_display_data);
        }
//        dd($users[0]);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
//        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_num)
    {
        $old_user = User::where('id_num',$id_num)->first();
        if(!$old_user){
            $user = new User();
            $user->id_num = $id_num;
//            dd($user->user_basic_data);
            if ($user->user_basic_data){
                $areas = Area::whereNUll('area_id')->get();
                return response()->json(['view' => view('control_panel.users.asaneedMoallem.basic.create',compact('user','areas'))->render(), 'errors' => 0]);
            } else {
                return response()->json(['view' => '', 'errors' => 1, 'msg' => 'رقم الهوية خطأ'], 404);
            }
        }else{
            if($old_user->hasRole('شيخ اسناد')){
                $update_link =
                    '&nbsp;<a href="#!" class="call-user-modal" onclick="callUserModal(this)" data-url="' . route('asaneedMoallem.edit', $old_user->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">
                                هنا
                            </a>&nbsp;';
                return response()->json(['view' => '', 'errors' => 1,'msg'=>' رقم الهوية موجود مسبقا انقر '.$update_link.' للتعديل '],404);
            }else{
                $areas = Area::whereNUll('area_id')->get();
                $user = $old_user;
                return response()->json(['user_id' => $user->id,'view' => $user->teachers_roles_select, 'errors' => 0,'teachers_roles_select'=>1]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAsaneedMoallemRequest $request)
    {
//        dd($this->id_num);
        $old_user = User::where('id_num',$request->id_num)->first();
        if($old_user){
            if( !$old_user->hasRole('شيخ اسناد')) {
                $old_user->assignRole('شيخ اسناد');
                return response()->json(['msg' => 'تم اضافة شيخ اسانيد واجازات جديد', 'title' => 'اضافة', 'type' => 'success']);
            }
        }else {
            $user = new User();
            $user->id_num = $request->id_num;
            if ($user->user_basic_data) {
                $avatar = 'logo.png';
                if ($request->user_profile) {
                    $avatar = $request->user_profile->store('public', 'public');
                }

                $user = User::create(array_merge($request->all(),
                    [
                        'name' => $user->name,
                        'dob' => $user->dob,
                        'pob' => $user->pob,
                        'role' => $user->role,
                        'avatar' => $avatar,
                        'password' => Hash::make($request->id_num)
                    ]
                ));
                $user->userExtraData()->create($request->all());
                $user->assignRole('شيخ اسناد');
                if (
                    (isset($request->course_name) && isset($request->course_teacher_name) && isset($request->course_year)) &&
                    (count($request->course_name) == count($request->course_teacher_name) && count($request->course_teacher_name) == count($request->course_year))
                ) {
                    foreach ($request->course_name as $key => $value) {
                        if ($request->course_name[$key] && $request->course_teacher_name[$key] && $request->course_year[$key]) {
                            $user->OldCourses()->create([
                                'course' => $request->course_name[$key],
                                'course_teacher' => $request->course_teacher_name[$key],
                                'year' => $request->course_year[$key]
                            ]);
                        }
                    }
                }
                if ($request->user_comment && count($request->user_comment)) {
                    foreach ($request->user_comment as $key => $comment) {
                        $user->notes()->create([
                            'note' => $comment
                        ]);
                    }
                }
                if ($request->encloses && count($request->encloses)) {
                    foreach ($request->encloses as $key => $enclose) {
                        $enclose_name = $enclose->store('public', 'public');
                        $user->media()->create([
                            'name' => $enclose_name,
                            'old_name' => (isset($request->enclose_comment) ? isset($request->enclose_comment[$key]) ? $request->enclose_comment[$key] : null : null)
                        ]);
                    }
                }
                return response()->json(['msg' => 'تم اضافة شيخ اسانيد واجازات جديد', 'title' => 'اضافة', 'type' => 'success']);
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
        $user = User::with(['userExtraData'])->find($id);
        if($user){
            $areas = Area::whereNUll('area_id')->get();
            $sub_areas = Area::where('area_id',$user->area_father_id)->get();
            $places = Place::where('area_id',$user->area_id)->get();
            $user_old_courses = UserOldCourse::where('user_id',$user->id)->get();
            $user_notes = UserNote::where('user_id',$user->id)->get();
            $media = $user->media;
            return view('control_panel.users.asaneedMoallem.basic.update',compact('user','areas','sub_areas','places','user_old_courses','user_notes','media'));
        }else{
            return response()->json(['view' => '', 'errors' => 1, 'msg' => 'المستخدم غير موجود'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAsaneedMoallemRequest $request, $id)
    {
        $user = User::with(['userExtraData'])->find($id);
//        dd($user);
        if($user){
            $avatar = $user->avatar;
            if ($request->user_profile) {
                $avatar = $request->user_profile->store('public', 'public');
            }
            $user->update(array_merge($request->only($user->getFillable()),[
                'avatar'=>$avatar,
                'name' => $user->name,
                'dob' => $user->dob,
                'pob' => $user->pob,
            ]));
            $user->userExtraData()->update($request->only($user->userExtraData->getFillable()));

            if (
                (isset($request->course_name) && isset($request->course_teacher_name) && isset($request->course_year)) &&
                (count($request->course_name) == count($request->course_teacher_name) && count($request->course_teacher_name) == count($request->course_year))
            ) {
                $user->OldCourses ? $user->OldCourses->each(function($course){
                    $course->delete();
                }) : '';
                foreach ($request->course_name as $key => $value) {
                    if ($request->course_name[$key] && $request->course_teacher_name[$key] && $request->course_year[$key]) {
                        $user->OldCourses()->create([
                            'course' => $request->course_name[$key],
                            'course_teacher' => $request->course_teacher_name[$key],
                            'year' => $request->course_year[$key]
                        ]);
                    }
                }
            }
            if ($request->user_comment && count($request->user_comment)) {
                $user->notes ? $user->notes->each(function($note){
                    $note->delete();
                }) : '';
                foreach ($request->user_comment as $key => $comment) {
                    $user->notes()->create([
                        'note' => $comment
                    ]);
                }
            }
            if ($request->encloses && count($request->encloses)) {
                foreach ($request->encloses as $key => $enclose) {
//                    dd($request->encloses,$enclose);
                    $enclose_name = $enclose->store('public', 'public');
                    $user->media()->create([
                        'name' => $enclose_name,
                        'old_name' => (isset($request->enclose_comment) ? isset($request->enclose_comment[$key]) ? $request->enclose_comment[$key] : null : null)
                    ]);
                }
            }
            return response()->json(['msg' => 'تم تعديل بيانات المحفظ بنجاح', 'title' => 'تعديل', 'type' => 'success']);
        }else{
//            $query->where('name','معلم')
            return response()->json(['msg' => 'المستخدم غير موجود', 'title' => 'خطأ !', 'type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $asaneedMoallem)
    {
//        dd(count($asaneedMoallem->getRoleNames()->toArray()),count($asaneedMoallem->getRoleNames()->toArray())>1,$asaneedMoallem->getRoleNames()->toArray());
        if ($asaneedMoallem->hasRole('شيخ اسناد')){
            if (count($asaneedMoallem->getRoleNames()->toArray()) && count($asaneedMoallem->getRoleNames()->toArray())>1){
                $asaneedMoallem->removeRole('شيخ اسناد');
            }else{
                $asaneedMoallem->delete();
            }
            return response()->json(['msg' => 'تم حذف بيانات الشيخ بنجاح', 'title' => 'حذف', 'type' => 'success']);
        }else{
            return response()->json(['msg' => 'المستخدم المطلوب حذفه ليس شيخ اسانيد', 'title' => 'حذف', 'type' => 'error']);
        }
    }
    public function getUserUpdateRolesSelect(User $user){
        return $user->teachers_roles_select;
    }
    public function updateUserRoles(User $user,$roles){
        $user->syncRoles(json_decode($roles));
        return response()->json(['msg' => 'تم تعديل الصلاحيات', 'title' => 'تعديل', 'type' => 'success']);
    }
}
