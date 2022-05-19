<?php

namespace App\Http\Controllers\controlPanel\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\courses\updateCourseRequest;
use App\Http\Requests\controlPanel\users\courseStudents\newCourseStudentsRequest;
use App\Http\Requests\controlPanel\users\courseStudents\updateCourseStudentsRequest;
use App\Models\Area;
use App\Models\Book;
use App\Models\Course;
use App\Models\CourseBookPlan;
use App\Models\CourseStudent;
use App\Models\Place;
use App\Models\User;
use App\Models\UserNote;
use App\Models\UserOldCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function PHPSTORM_META\type;

class courseStudentsController extends Controller
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
//        checkPermissionHelper('طلاب الدورات');
        $moallems = User::whereIn('id',Course::select('teacher_id')->get())->get();

        $training_course_count = Course::all()->count();

        return view('control_panel.users.courseStudents.basic.index',compact('moallems','training_course_count'));


    }
    public function getData(Request $request)
    {
        checkPermissionHelper('طلاب الدورات');
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'name',      'dt' => 2 ),
            array( 'db' => 'name',      'dt' => 3 ),
            array( 'db' => 'name',      'dt' => 4 ),
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


        $value = array();

        if(!empty($search)){
            $count = User::coursebookorteacher($teacher_id,$book_id,$place_id)
                ->search($search)
                ->department(4)
                ->count();
            $users = User::coursebookorteacher($teacher_id,$book_id,$place_id)
                ->search($search)
                ->department(4)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = User::coursebookorteacher($teacher_id,$book_id,$place_id)
                ->department(4)
                ->count();
            $users = User::coursebookorteacher($teacher_id,$book_id,$place_id)
                ->department(4)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();

        }
        User::$counter = $start;
        foreach ($users as $index => $item){
            $item->setDepartmentValue(4);
            array_push($value , $item->user_display_data);
        }


       

        $passed_students_count = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [60, 101])->distinct('user_id')->count();

        $failed_students_count = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [1, 59])->distinct('user_id')->count();

        // $awaiting_students_count = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
        //                             ->whereNull('mark')->count();

        // $awaiting_students_count = $count - ($passed_students_count + $failed_students_count);
        $awaiting_students_count = 0;
        $teacher_course_count = 0;

     

        $students_100 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [90, 101])->count();
                                    
        $students_89 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [85, 89])->count();
        
        $students_84 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [80, 84])->count();

        $students_79 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [75, 79])->count();

        $students_74 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [70, 74])->count();

        $students_69 = CourseStudent::coursebookorteacher($teacher_id,$book_id,$place_id)
                                    ->whereBetween('mark', [60, 69])->count();
    
        $students_count_success = $students_100 + $students_89 + $students_84 + $students_79 + $students_74 + $students_69;

        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"],

            "students_count" => '('.$count.')',
            "students_count_success" => '('.$students_count_success.')',
            "students_count_certificate" => '(0)',
            "passed_students_count" => $passed_students_count,
            "failed_students_count" => $failed_students_count,
            "awaiting_students_count" => $awaiting_students_count,
            "students_100" => $students_100,
            "students_89" => $students_89,
            "students_84" => $students_84,
            "students_79" => $students_79,
            "students_74" => $students_74,
            "students_69" => $students_69,
            "training_course_count" => $teacher_course_count,         
        ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_num,Course $course)
    {
        checkPermissionHelper('اضافة طالب جديد - دورات علمية');
        if($course->teacher_id_num == $id_num){
            return response()->json(['view' => '', 'errors' => 1, 'msg' => ' لا يمكن اضافة المعلم '.$course->teacher_name.' كطالب في دورته '], 404);
        }else {
            $old_user = User::withoutGlobalScope('relatedUsers')->where('id_num', $id_num)->first();
//            dd($old_user);
            if (!$old_user) {

                $user = new User();
                $user->id_num = $id_num;
                if ($user->user_basic_data) {
                    if(in_array($user->student_category,$course->student_categories)) {
//                        return response()->json(['view' => 0, 'errors' => 0, 'msg' => 'تم اضافة الطالب بنجاح ' . $user->name .' لدورة المعلم '. $course->teacher_name .' كتاب '. $course->book_name], 404);
                        return response()->json(['view' => view('control_panel.users.courseStudents.basic.create', compact('user', 'course'))->render(), 'errors' => 0]);
                    }else{
                        $excludeButton = hasPermissionHelper('استثناء طالب خارج الخطة') ? '<button type="button" class="btn btn-primary" onclick="excludeStudent('.$id_num.','.$course->id.')">استثناء</button>' : '';
                        return response()->json(['view' => 0, 'errors' => 1, 'msg' => ' الطالب خارج الفئة المستهدفة | '.$excludeButton], 404);
                    }
                } else {
                    return response()->json(['view' => 0, 'errors' => 1, 'msg' => 'رقم الهوية خطأ'], 404);
                }
            } else {
//                dd(in_array($old_user->student_category,$course->student_categories));
                if(!in_array($old_user->student_category,$course->student_categories)) {
                    $excludeButton = hasPermissionHelper('استثناء طالب خارج الخطة') ? '<button type="button" class="btn btn-primary" onclick="excludeStudent('.$id_num.','.$course->id.')">استثناء</button>' : '';
                    return response()->json(['view' => 0, 'errors' => 1, 'msg' => ' الطالب خارج الفئة المستهدفة | '.$excludeButton], 202);
                }
                if(!$old_user->hasRole('طالب دورات علمية')) {
                    $old_user->assignRole('طالب دورات علمية');
                }
                $studentCourses = CourseStudent::where([
                    'user_id' => $old_user->id,
                    'course_id' => $course->id
                ])->count();
//                dd($studentCourses);
                if(!$studentCourses) {
                    CourseStudent::create([
                        'user_id' => $old_user->id,
                        'course_id' => $course->id
                    ]);
                }
                $users = User::whereHas('courses',function($query) use ($course){
                    $query->where('course_id',$course->id);
                })->get();
                return response()->json(['view' => 0, 'errors' => 0, 'msg' => 'تم اضافة الطالب بنجاح ' . $old_user->name .' لدورة المعلم '. $course->teacher_name .' كتاب '. $course->book_name], 404);
//                return response()->json(['view' => view('control_panel.users.courseStudents.showCourseStudents',compact('users','course'))->render(), 'errors' => 0]);
            }
        }
    }
    public function excludeStudent($user_id_num,Course $course){
        checkPermissionHelper('استثناء طالب خارج الخطة');
        $user = User::where('id_num',$user_id_num)->withOutGlobalScope('relatedUsers')->first();
//        dd($user);
        if(!$user){
            $user = new User();
            $user->id_num = $user_id_num;
            if ($user->user_basic_data) {
                $user->password = Hash::make($user_id_num);
                $user->place_id = $course->place_id;
                $user->save();
                $user->assignRole('طالب دورات علمية');

//                dd($user);
                CourseStudent::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id
                ]);
            }else{
                return response()->json(['msg' => 'رقم الهوية خطأ','title'=>'خطأ!','type'=>'danger']);
            }
        }else {
            if (!$user->hasRole('طالب دورات علمية')) {
                $user->assignRole('طالب دورات علمية');
            }
            $studentCourses = CourseStudent::where([
                'user_id' => $user->id,
                'course_id' => $course->id
            ])->count();
            if (!$studentCourses) {
                CourseStudent::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id
                ]);
            }
        }
        $users = User::whereHas('courses', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->get();
        return response()->json(['view' => 0, 'errors' => 0, 'msg' => 'تم اضافة الطالب بنجاح ' . $user->name .' لدورة المعلم '. $course->teacher_name .' كتاب '. $course->book_name], 202);
//        return view('control_panel.users.courseStudents.showCourseStudents',compact('users','course'));
    }
    public function ShowCourseStudents(Course $course)
    {
//        dd(in_array('ثانوية',$course->student_categories));
        if(hasPermissionHelper('الدورات المجاز فيها') || hasPermissionHelper('الدورات الغير مجاز فيها') || hasPermissionHelper('جميع الدورات') ){
//            $users = User::whereHas('courses', function ($query) use ($course) {
//                $query->where('course_id', $course->id);
//            })->get();
//
//            $users = DB::table('users')->join('course_students',function($q) use ($course){
//                $q->on('course_students.user_id', '=', 'users.id')->where('course_id', $course->id);
//            })->get();
            $users = CourseStudent::with('user')->where('course_id',$course->id)->get()->pluck('user');
//            dd($users);
            return view('control_panel.users.courseStudents.showCourseStudents', compact('users', 'course'));
        }else{
            abort(403);
        }

    }
    public function showLoadingCourseStudents(Course $course)
    {
        return view('control_panel.users.courseStudents.showLoadingCourseStudents', compact( 'course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCourseStudentsRequest $request)
    {
        checkPermissionHelper('اضافة طالب جديد - دورات علمية');
        $old_user = User::where('id_num', $request->id_num)->first();
        if (!$old_user) {
            $user = new User();
            $user->id_num = $request->id_num;
            if ($user->user_basic_data) {
                $course = Course::find($request->course_id);
                if($course) {
                    $password = array_merge(
                        ['password' => Hash::make($request->password),'place_id' => $course->place_id],
                        $user->toArray()
                    );
                    $user = User::create(array_merge($request->all(), $password));
                    $user->assignRole('طالب دورات علمية');
                    CourseStudent::create([
                        'user_id' => $user->id,
                        'course_id' => $request->course_id
                    ]);
                    return response()->json(['msg' => 'تم اضافة مستخدم جديد', 'title' => 'اضافة', 'type' => 'success']);
                }else{
                    return response()->json(['msg' => 'يوجد خطأ في بيانات الدورة', 'title' => 'خطأ !', 'type' => 'danger']);
                }
            } else {
                return response()->json(['msg' => 'رقم الهوية غير صحيح', 'title' => 'خطأ !', 'type' => 'danger']);
            }
        } else {
            if (!$old_user->hasRole('طالب دورات علمية')) {
                $old_user->update($request->all());
                $old_user->assignRole('طالب دورات علمية');
                CourseStudent::create([
                    'user_id' => $old_user->id,
                    'course_id' => $request->course_id
                ]);
                return response()->json(['msg' => 'تم اضافة مستخدم جديد', 'title' => 'اضافة', 'type' => 'success']);
            } else {
                return response()->json(['msg' => ' الطالب ' . $old_user->name . ' مضاف لنفس الدورة مسبقا ', 'title' => 'خطأ !', 'type' => 'danger']);
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
        checkPermissionHelper('');
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
        $user = User::withoutGlobalScope('relatedUsers')->find($id);
        if($user){
            return view('control_panel.users.courseStudents.basic.update',compact('user'));
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
    public function update(updateCourseStudentsRequest $request, $id)
    {
        $user = User::find($id);
        if($user){
            $user->update(array_merge($request->only($user->getFillable()),['place_id'=>$user->place_id]));
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
    public function destroy(User $user,Course $course)
    {
        checkPermissionHelper('حذف طالب من دورة علمية');
//        dd($course->has_next_exam);
        if($course->status != 'منتهية') {
            if(!$course->has_next_exam) {
                $courseStudent = CourseStudent::where('user_id' , $user->id)
                    ->where('course_id' , $course->id)->first();
                $courseStudent->delete();
                $studentCourses = CourseStudent::where('user_id',$user->id)->count();
                if (!$studentCourses) {
                    if ($user->hasRole('طالب دورات علمية')) {
                        $user->removeRole('طالب دورات علمية');
                    }
                }
    //            dd($user->roles);
//                if(!$user->user_roles->count()){
//                    $user->delete();
//                }
                    return response()->json(['msg' => 'تم حذف بيانات الطالب من الدورة بنجاح', 'title' => 'حذف', 'type' => 'success']);
            }else{
                return response()->json(['msg' => 'لا يمكن حذف طلاب من الدورة بعد اعتماد موعد الاختبار', 'title' => 'خطأ!', 'type' => 'danger']);
            }
        }else{
            return response()->json(['msg' => 'لا يمكن حذف الطالب بعد انتهاء الدورة', 'title' => 'خطأ!', 'type' => 'danger']);
        }
    }
    public function getTeacherCourseBooks($user_id){
        $user = User::find($user_id);
        if($user) {
            $courses = $user->teacherCourses->load('book');
            $books = '<option value="0">اختر الكتاب</option>';
            foreach ($courses->pluck('book')->unique() as $value) {
                $books .= '<option value="' . $value->id . '">' . $value->name . '</option>';
            }
            return $books;
        }
    }
    public function getBookCoursePlaces($book_id,$teacher_id){
        $book = Book::find($book_id);
        if($book) {
            $courses = $book->load(['courses'=>function($query) use($teacher_id){
                $query->where('teacher_id',$teacher_id);
            },'courses.place']);
            $places = '<option value="0">اختر المسجد</option>';
            foreach ($courses->courses->pluck('place')->unique() as $value) {
                $places .= '<option value="' . $value->id . '">' . $value->name . '</option>';
            }
            return $places;
        }
    }
}
