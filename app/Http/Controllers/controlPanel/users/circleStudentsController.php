<?php

namespace App\Http\Controllers\controlPanel\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\users\circleStudents\newCircleStudentsRequest;
use App\Http\Requests\controlPanel\users\circleStudents\updateCircleStudentsRequest;
use App\Models\Area;
use App\Models\Place;
use App\Models\User;
use App\Models\UserExtraData;
use App\Models\UserNote;
use App\Models\UserOldCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class circleStudentsController extends Controller
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
        return view('control_panel.users.circleStudents.basic.index');
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


        $value = array();
        User::$counter = 0;

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
            $count = User::search($search)
                ->department(3)
                ->count();
            $users = User::search($search)
                ->department(3)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::department(3)
                ->count();
            $users = User::department(3)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd($users);
        }
        foreach ($users as $index => $item){
            $item->setDepartmentValue(3);
            array_push($value , $item->circle_student_display_data);
        }
//        dd($value);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],

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
            if ($user->user_basic_data){
                $areas = Area::whereNUll('area_id')->get();
                $note = UserNote::where('user_id',$user->id)->first();
                return response()->json(['view' => view('control_panel.users.circleStudents.basic.create',compact('user','areas','note'))->render(), 'errors' => 0]);
            } else {
                return response()->json(['view' => '', 'errors' => 1, 'msg' => 'رقم الهوية خطأ'], 404);
            }
        }else{


            if(!$old_user->hasRole('طالب تحفيظ')){
                $user = $old_user;
                $areas = Area::whereNUll('area_id')->get();
                $sub_areas = Area::where('area_id',$user->area_father_id)->get();
                $places = Place::where('area_id',$user->area_id)->get();
                $user_old_courses = UserOldCourse::where('user_id',$user->id)->get();
                $user_notes = UserNote::where('user_id',$user->id)->get();
                $media = $user->media;
                $note = UserNote::where('user_id',$user->id)->first();
                $teachers = getPlaceTeachersForCircles($user->area_father_id,$user->teacher_id);
                return view('control_panel.users.circleStudents.basic.update',compact('teachers','note','user','areas','sub_areas','places','user_old_courses','user_notes','media'));
            }else {
                $update_link =
                    '&nbsp;<a href="#!" class="btn btn-primary" data-url="' . route('circleStudents.edit', $old_user->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">
                                هنا
                            </a>&nbsp;';
                return response()->json(['view' => '', 'errors' => 1, 'msg' => ' رقم الهوية موجود مسبقا انقر ' . $update_link . ' للتعديل '], 404);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCircleStudentsRequest $request)
    {
        $user = new User();
        $user->id_num = $request->id_num;
        if($user->user_basic_data) {
            $avatar = 'logo.png';
            if ($request->user_profile){
                $avatar = $request->user_profile->store('public', 'public');
            }

            $user = User::create(array_merge($request->all(), [
                'name' => $user->name,
                'dob' => $user->dob,
                'pob' => $user->pob,
                'role' => $user->role,
                'avatar'=> $avatar,
                'password'=>Hash::make($request->id_num),
            ]));
            $user->userExtraData()->create($request->all());
            $user->assignRole('طالب تحفيظ');
            if (!empty($request->notes)) {
                $user->notes()->create([
                    'note' => $request->notes
                ]);
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
            $note = UserNote::where('user_id',$user->id)->first();
            $teachers = getPlaceTeachersForCircles($user->area_father_id,$user->teacher_id);
//            dd($teachers);
            return view('control_panel.users.circleStudents.basic.update',compact('teachers','note','user','areas','sub_areas','places','user_old_courses','user_notes','media'));
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
    public function update(updateCircleStudentsRequest $request, $id)
    {
        $user = User::with(['userExtraData'])->find($id);
        if($user){
            $avatar = $user->avatar;
            if ($request->user_profile){
                $avatar = $request->user_profile->store('public', 'public');
            }
//            dd($user->getFillable());
            $user->update(array_merge($request->only($user->getFillable()),['avatar'=>$avatar]));
            $user->userExtraData()->update($request->only((new UserExtraData())->getFillable()));
            if (!empty($request->notes)) {
                $user->notes ? $user->notes->each(function($note){
                    $note->delete();
                }) : '';
                $user->notes()->create([
                    'note' => $request->notes
                ]);
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
    public function destroy(User $circleStudent)
    {
//        dd($circleStudent);
        if(!$circleStudent->circle_student_current_circle){
            if ($circleStudent->hasRole('طالب تحفيظ')){
                $circleStudent->removeRole('طالب تحفيظ');
            }
            return response()->json(['msg' => 'تم حذف بيانات الطالب من الحلقة بنجاح', 'title' => 'حذف', 'type' => 'success']);
        }else{
            return response()->json(['msg' => 'لا يمكن حذف الطالب بعد انتهاء الحلقة', 'title' => 'خطأ!', 'type' => 'danger']);
        }
    }
}
