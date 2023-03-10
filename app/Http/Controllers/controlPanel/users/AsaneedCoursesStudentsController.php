<?php

namespace App\Http\Controllers\controlPanel\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\asaneedCoursesStudents\newAsaneedCourseStudentsRequest;
use App\Models\AsaneedBook;
use App\Models\AsaneedCourse;
use App\Models\AsaneedCourseStudent;
use App\Models\User;
use App\Models\Area;
use App\Models\Exam;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AsaneedCoursesStudentsController extends Controller
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
        $moallems = User::whereHas('teacherAsaneedCourses')->get();
        $areas = Area::whereNull('area_id')->get();

        return view('control_panel.users.asaneedCourseStudents.basic.index',compact('moallems','areas'));
    }
    public function getData(Request $request)
    {
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
//            array( 'db' => 'tools',      'dt' => 2 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];


        $search = trim($request->search["value"]);
        $teacher_id = (int)$request->teacher_id ? (int)$request->teacher_id : 0;
        $book_id = (int)$request->book_id ? (int)$request->book_id : 0;
        $place_id = (int)$request->place_id ? (int)$request->place_id : 0;
        $sub_area_id = (int)$request->sub_area_id ? (int)$request->sub_area_id : 0;
        $area_id = (int)$request->area_id ? (int)$request->area_id : 0;


        $value = array();

        if(!empty($search)){
            $count = User::asaneedcoursebookorteacher($teacher_id,$book_id)
                ->search($search)
                ->department(8)
                ->subarea($sub_area_id, $area_id)
                ->count();
            $users = User::asaneedcoursebookorteacher($teacher_id,$book_id)
                ->search($search)
                ->department(8)
                ->subarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::asaneedcoursebookorteacher($teacher_id,$book_id)
                ->department(8)
                ->subarea($sub_area_id, $area_id)
                ->count();
            $users = User::asaneedcoursebookorteacher($teacher_id,$book_id)
                ->department(8)
                ->subarea($sub_area_id, $area_id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
//            dd($users);
        }
        foreach ($users as $index => $item){
            $item->setDepartmentValue(8);
            array_push($value , $item->asaneed_students_display_data);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_num,AsaneedCourse $asaneedCourse)
    {
        if($asaneedCourse->teacher_id_num == $id_num){
            return response()->json(['view' => '', 'errors' => 1, 'msg' => ' ???? ???????? ?????????? ?????? ?????????????? '.$asaneedCourse->teacher_name.' ?????????? ???? ?????????? '], 404);
        }else {
            $old_user = User::where('id_num', $id_num)->first();
            if (!$old_user) {
                $user = new User();
                $user->id_num = $id_num;
                if ($user->user_basic_data) {
//                    return in_array($user->student_category,$course->student_categories);
                    if(in_array($user->student_category,$asaneedCourse->student_categories)) {
                        return response()->json(['view' => view('control_panel.users.asaneedCourseStudents.basic.create', compact('user', 'asaneedCourse'))->render(), 'errors' => 0]);
                    }else{
                        $excludeButton = '<button type="button" class="btn btn-primary" onclick="excludeStudent('.$id_num.','.$asaneedCourse->id.')">??????????????</button>';
                        return response()->json(['view' => '', 'errors' => 1, 'msg' => ' ???????????? ???????? ?????????? ?????????????????? | '.$excludeButton], 404);
                    }
                } else {
                    return response()->json(['view' => '', 'errors' => 1, 'msg' => '?????? ???????????? ??????'], 404);
                }
            } else {
                if(!in_array($old_user->student_category,$asaneedCourse->student_categories)) {
                    $excludeButton = '<button type="button" class="btn btn-primary" onclick="excludeStudent('.$id_num.','.$asaneedCourse->id.')">??????????????</button>';
                    return response()->json(['view' => '', 'errors' => 1, 'msg' => ' ???????????? ???????? ?????????? ?????????????????? | '.$excludeButton], 404);
                }
                if(!$old_user->hasRole('???????? ?????????? ???????????? ??????????????')) {
                    $old_user->assignRole('???????? ?????????? ???????????? ??????????????');
                }
                $studentCourses = AsaneedCourseStudent::where([
                    'user_id' => $old_user->id,
                    'asaneed_course_id' => $asaneedCourse->id
                ])->count();
                if(!$studentCourses) {
                    AsaneedCourseStudent::create([
                        'user_id' => $old_user->id,
                        'asaneed_course_id' => $asaneedCourse->id
                    ]);
                    $students_count = $asaneedCourse->students->count();
                    $has_exam = Exam::where('examable_id',$asaneedCourse->id)->where('examable_type','App\Models\AsaneedCourse')->exists();

                    if($students_count >= 10 && !$has_exam){
                        $asaneedCourse->exam()->create();
                        $asaneedCourse->exam()->update([
                            'status' => 5
                        ]);
                    }
                }
                $users = User::whereHas('asaneedCourses',function($query) use ($asaneedCourse){
                    $query->where('asaneed_course_id',$asaneedCourse->id);
                })->get();
                return response()->json(['view' => view('control_panel.users.asaneedCourseStudents.showCourseStudents',compact('users','asaneedCourse'))->render(), 'errors' => 0]);
            }
        }
    }
    public function excludeStudent($user_id_num,AsaneedCourse $asaneedCourse)
    {
        $user = User::where('id_num',$user_id_num)->withOutGlobalScope('relatedUsers')->first();
        if (!$user) {
            $user = new User();
            $user->id_num = $user_id_num;
            if ($user->user_basic_data) {
                $user->password = Hash::make($user_id_num);
                $user->assignRole('???????? ?????????? ???????????? ??????????????');
                AsaneedCourseStudent::create([
                    'user_id' => $user->id,
                    'asaneed_course_id' => $asaneedCourse->id
                ]);
                $students_count = $asaneedCourse->students->count();
                $has_exam = Exam::where('examable_id',$asaneedCourse->id)->where('examable_type','App\Models\AsaneedCourse')->exists();

                if($students_count >= 10 && !$has_exam){
                    $asaneedCourse->exam()->create();
                    $asaneedCourse->exam()->update([
                        'status' => 5
                    ]);
                }
            } else {
                return response()->json(['msg' => '?????? ???????????? ??????', 'title' => '??????!', 'type' => 'danger']);
            }
        } else {
            if (!$user->hasRole('???????? ?????????? ???????????? ??????????????')) {
                $user->assignRole('???????? ?????????? ???????????? ??????????????');
            }
            $studentCourses = AsaneedCourseStudent::where([
                'user_id' => $user->id,
                'asaneed_course_id' => $asaneedCourse->id
            ])->count();
            if (!$studentCourses) {
                AsaneedCourseStudent::create([
                    'user_id' => $user->id,
                    'asaneed_course_id' => $asaneedCourse->id
                ]);
            }
            $users = User::whereHas('asaneedCourses', function ($query) use ($asaneedCourse) {
                $query->where('asaneed_course_id', $asaneedCourse->id);
            })->get();

            $students_count = $asaneedCourse->students->count();
            $has_exam = Exam::where('examable_id',$asaneedCourse->id)->where('examable_type','App\Models\AsaneedCourse')->exists();

            if($students_count >= 10 && !$has_exam){
                $asaneedCourse->exam()->create();
                $asaneedCourse->exam()->update([
                    'status' => 5
                ]);
            }

            return view('control_panel.users.asaneedCourseStudents.showCourseStudents', compact('users', 'asaneedCourse'));
        }
    }
    public function ShowAsaneedCourseStudents(AsaneedCourse $asaneedCourse)
    {
    //    dd($asaneedCourse);
        $users = User::whereHas('asaneedCourses',function($query) use ($asaneedCourse){
            $query->where('asaneed_course_id',$asaneedCourse->id);
        })->get();
        return view('control_panel.users.asaneedCourseStudents.showCourseStudents',compact('users','asaneedCourse'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAsaneedCourseStudentsRequest $request)
    {
        $old_user = User::where('id_num', $request->id_num)->first();
        $asaneedCourse = AsaneedCourse::find($request->asaneed_course_id);
        if (!$old_user) {
            $user = new User();
            $user->id_num = $request->id_num;
            if ($user->user_basic_data) {
                $password = array_merge(['password'=>Hash::make($request->password)],$user->toArray());
                $user = User::create(array_merge($request->all(), $password));
                $user->assignRole('???????? ?????????? ???????????? ??????????????');
                AsaneedCourseStudent::create([
                    'user_id' => $user->id,
                    'asaneed_course_id' => $request->asaneed_course_id
                ]);

                $students_count = $asaneedCourse->students->count();
                $has_exam = Exam::where('examable_id',$asaneedCourse->id)->where('examable_type','App\Models\AsaneedCourse')->exists();

                if($students_count >= 10 && !$has_exam){
                    $asaneedCourse->exam()->create();
                    $asaneedCourse->exam()->update([
                        'status' => 5
                    ]);
                }
                return response()->json(['msg' => '???? ?????????? ???????????? ????????', 'title' => '??????????', 'type' => 'success']);
            } else {
                return response()->json(['msg' => '?????? ???????????? ?????? ????????', 'title' => '?????? !', 'type' => 'danger']);
            }
        } else {
            if (!$old_user->hasRole('???????? ?????????? ???????????? ??????????????')) {
                $old_user->update($request->all());
                $old_user->assignRole('???????? ?????????? ???????????? ??????????????');
                AsaneedCourseStudent::create([
                    'user_id' => $old_user->id,
                    'asaneed_course_id' => $request->asaneed_course_id
                ]);
                $students_count = $asaneedCourse->students->count();
                $has_exam = Exam::where('examable_id',$asaneedCourse->id)->where('examable_type','App\Models\AsaneedCourse')->exists();

                if($students_count >= 10 && !$has_exam){
                    $asaneedCourse->exam()->create();
                    $asaneedCourse->exam()->update([
                        'status' => 5
                    ]);
                }
                return response()->json(['msg' => '???? ?????????? ???????????? ????????', 'title' => '??????????', 'type' => 'success']);
            } else {
                return response()->json(['msg' => ' ???????????? ' . $old_user->name . ' ???????? ???????? ???????????? ?????????? ', 'title' => '?????? !', 'type' => 'danger']);
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
        $user = User::find($id);
        if($user){
            return view('control_panel.users.asaneedCourseStudents.basic.update',compact('user'));
        }else{
            return response()->json(['view' => '', 'errors' => 1, 'msg' => '???????????????? ?????? ??????????'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAsaneedCourseStudentsRequest $request, $id)
    {
        $user = User::find($id);
        if($user){
            $user->update(array_merge($request->only($user->getFillable())));
            return response()->json(['msg' => '???? ?????????? ???????????? ???????????? ??????????', 'title' => '??????????', 'type' => 'success']);
        }else{
            return response()->json(['msg' => '???????????????? ?????? ??????????', 'title' => '?????? !', 'type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user,AsaneedCourse $asaneedCourse)
    {
        if($asaneedCourse->status != '????????????') {
            $courseStudent = AsaneedCourseStudent::where([
                'user_id' => $user->id,
                'asaneed_course_id' => $asaneedCourse->id
            ])->first();
            $courseStudent->delete();
            $studentCourses = AsaneedCourseStudent::where([
                'user_id' => $user->id,
                // 'asaneed_course_id' => $asaneedCourse->id
            ])->count();
            if (!$studentCourses) {
                if ($user->hasRole('???????? ?????????? ???????????? ??????????????')) {
                    $user->removeRole('???????? ?????????? ???????????? ??????????????');
                }
            }
            return response()->json(['msg' => '???? ?????? ???????????? ???????????? ???? ???????????? ??????????', 'title' => '??????', 'type' => 'success']);
        }else{
            return response()->json(['msg' => '???? ???????? ?????? ???????????? ?????? ???????????? ????????????', 'title' => '??????!', 'type' => 'danger']);
        }
    }
    public function getTeacherCourseBooks($user_id){
        $user = User::find($user_id);
        if($user) {
            $courses = $user->teacherAsaneedCourses->load('book');
            $books = '<option value="0">???????? ????????????</option>';
            foreach ($courses->pluck('book')->unique() as $value) {
                $books .= '<option value="' . $value->id . '">' . $value->name . '</option>';
            }
            return $books;
        }
    }
    public function getBookCoursePlaces($book_id,$teacher_id){
        $book = AsaneedBook::find($book_id);
        if($book) {
            $courses = $book->load(['courses'=>function($query) use($teacher_id){
                $query->where('teacher_id',$teacher_id);
            },'courses.place']);
            $places = '<option value="0">???????? ????????????</option>';
            foreach ($courses->courses->pluck('place')->unique() as $value) {
                $places .= '<option value="' . $value->id . '">' . $value->name . '</option>';
            }
            return $places;
        }
    }


    public function getCourses(User $user){
        return view('control_panel.users.asaneedCourseStudents.showStudentCourses',compact('user'));
    }
    public function getPassedCourses(User $user){
        $passed = 1;
        return view('control_panel.users.asaneedCourseStudents.showStudentCourses',compact('user','passed'));
    }
    public function getFailedCourses(User $user){
        $failed = 1;
        return view('control_panel.users.asaneedCourseStudents.showStudentCourses',compact('user','failed'));
    }
}
