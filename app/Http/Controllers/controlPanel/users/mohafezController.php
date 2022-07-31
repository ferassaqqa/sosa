<?php

namespace App\Http\Controllers\controlPanel\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\users\mohafez\newMohafezRequest;
use App\Http\Requests\controlPanel\users\mohafez\updateMohafezRequest;
use App\Models\Area;
use App\Models\Place;
use App\Models\User;
use App\Models\Circle;
use App\Models\UserNote;
use App\Models\UserOldCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class mohafezController extends Controller
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
        checkPermissionHelper('قائمة المحفظين');
        $areas = Area::whereNull('area_id')->get();
        return view('control_panel.users.mohafez.basic.index',compact('areas'));
    }
    public function getData(Request $request)
    {
        checkPermissionHelper('قائمة المحفظين');
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
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;

        $teacher_id = (int)$request->teacher_id ? (int)$request->teacher_id : 0;
        $circle_type = $request->circle_type ? $request->circle_type : '';
        $circle_status = $request->circle_status ? $request->circle_status : '';

        $value = array();
        User::$counter = $start;



        $mohafez_makfool = User::
                                department(1)
                                ->subarea($sub_area_id,$area_id)
                                ->whereHas('userExtraData', function($q) {
                                    $q->where('contract_type', 'مكفول');
                                })
                                ->get()->count();

        $mohafez_volunteer = User::
                            department(1)
                            ->subarea($sub_area_id,$area_id)
                            ->whereHas('userExtraData', function($q) {
                                $q->where('contract_type', 'متطوع');
                            })
                            ->get()->count();

        $total_mohafez_count = $mohafez_makfool + $mohafez_volunteer;

        $circle_volunteer = Circle::subarea($sub_area_id,$area_id)
                            ->circleStatus($circle_status)
                            ->contractType('متطوع')
                            ->get()->count();


        $circle_makfool = Circle::subarea($sub_area_id,$area_id)
                            ->circleStatus($circle_status)
                            ->contractType('مكفول')
                            ->get()->count();
        $total_circle_count = $circle_volunteer + $circle_makfool;

        $total_circlestudents_count =   User::department(3)->subarea($sub_area_id,$area_id)->count();
        $total_circlestudents_makfool =  User::department(3)
                                        ->subarea($sub_area_id,$area_id)
                                        ->whereHas('circleStudentTeacher',function($query){
                                            $query->whereHas('userExtraData',function($query){
                                                $query->where('contract_type', 'مكفول');
                                            });
                                        })
                                        ->count();

        $total_circlestudents_volunteer =   User::department(3)
                                        ->subarea($sub_area_id,$area_id)
                                        ->whereHas('circleStudentTeacher',function($query){
                                            $query->whereHas('userExtraData',function($query){
                                                $query->where('contract_type', 'متطوع');
                                            });
                                        })
                                        ->count();


        if(!empty($search)){
            $count = User::subarea($sub_area_id,$area_id)
                ->search($search)
                ->contractType($circle_type)
                ->department(1)
                ->count();
            $users = User::subarea($sub_area_id,$area_id)
                ->search($search)
                ->contractType($circle_type)
                ->department(1)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::subarea($sub_area_id,$area_id)
                ->department(1)
                ->contractType($circle_type)
                ->count();
            $users = User::subarea($sub_area_id,$area_id)
                ->department(1)
                ->contractType($circle_type)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd($users);
        }
        foreach ($users as $index => $item){
            $item->setDepartmentValue(1);
            array_push($value , $item->mohafez_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],

            'total_mohafez_count'  => $total_mohafez_count,
            'mohafez_makfool' => $mohafez_makfool,
            'mohafez_volunteer' => $mohafez_volunteer,

            'total_circle_count'  => $total_circle_count,
            'circle_makfool' => $circle_makfool,
            'circle_volunteer' => $circle_volunteer,

            'total_circlestudents_count'  => $total_circlestudents_count,
            'total_circlestudents_makfool' => $total_circlestudents_makfool,
            'total_circlestudents_volunteer' => $total_circlestudents_volunteer,
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
                return response()->json(['view' => view('control_panel.users.mohafez.basic.create',compact('user','areas'))->render(), 'errors' => 0]);
            } else {
                return response()->json(['view' => '', 'errors' => 1, 'msg' => 'رقم الهوية خطأ'], 404);
            }
        }else{
            $update_link =
                '&nbsp;<a href="#!" class="call-user-modal" onclick="callUserModal(this)" data-url="' . route('users.edit', $old_user->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">
                                هنا
                            </a>&nbsp;';
            return response()->json(['view' => '', 'errors' => 1,'msg'=>' رقم الهوية موجود مسبقا انقر '.$update_link.' للتعديل '],404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newMohafezRequest $request)
    {
        $user = new User();
        $user->id_num = $request->id_num;
        if($user->user_basic_data) {
            $avatar = 'logo.png';
            if ($request->user_profile) {
                $avatar = $request->user_profile->store('public', 'public');
            }

            $user = User::create(array_merge($request->all(),
                [
                    'avatar'=> $avatar,
                    'password'=>Hash::make($request->id_num),
                    'name'=>$user->name,
                    'material_status'=>$user->material_status,
                    'sons_count'=>$user->sons_count,
                    'dob'=>$user->dob,
                    'pob'=>$user->pob,
                    'role'=>$user->role,
                ]
            ));
            $user->userExtraData()->create($request->all());
            $user->assignRole('محفظ');
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
            return response()->json(['msg' => 'تم اضافة مستخدم جديد', 'title' => 'اضافة', 'type' => 'success']);
        }else{
            return response()->json(['msg'=>'رقم الهوية غير صحيح','title'=>'خطأ !','type'=>'danger']);
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
            return view('control_panel.users.mohafez.basic.update',compact('user','areas','sub_areas','places','user_old_courses','user_notes','media'));
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
    public function update(updateMohafezRequest $request, $id)
    {
        $user = User::with(['userExtraData'])->find($id);
        if($user){
            $avatar = $user->avatar;
            if ($request->user_profile) {
                $avatar = $request->user_profile->store('public', 'public');
            }
            $user->update(array_merge($request->only($user->getFillable()),['avatar'=>$avatar]));
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
            return response()->json(['msg' => 'المستخدم غير موجود', 'title' => 'خطأ !', 'type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $mohafez)
    {
        if ($mohafez->hasRole('محفظ')){
            if (count($mohafez->getRoleNames()->toArray()) && count($mohafez->getRoleNames()->toArray())>1){
                $mohafez->removeRole('محفظ');
            }else{
                if(!$mohafez->circles->count()){
//                    $mohafez->delete();
                }else{
                    return response()->json(['msg' => 'لا يمكن حذف بيانات المحفظ ، نظرا لوجود حلقات تحفيظ منسوبة إليه.', 'title' => 'حذف', 'type' => 'error']);
                }
            }
            return response()->json(['msg' => 'تم حذف بيانات المحفظ بنجاح', 'title' => 'حذف', 'type' => 'success']);
        }else{
            return response()->json(['msg' => 'المستخدم المطلوب حذفه ليس محفظ', 'title' => 'حذف', 'type' => 'error']);
        }
    }
}
