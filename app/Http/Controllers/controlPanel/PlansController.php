<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\plans\newPlanRequest;
use App\Http\Requests\controlPanel\plans\updatePlanRequest;
use App\Models\Area;
use App\Models\Book;
use App\Models\BookPlan;
use App\Models\CourseBookPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlansController extends Controller
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
    public function index($department)
    {
        switch ($department) {
            case 1 :{$department_name = 'الخطة السنوية لكتب التحفيظ';}break;
            case 2 :{$department_name = 'الخطة السنوية لكتب الدورات';}break;
        }
        return view('control_panel.plans.basic.plans.index',compact('department','department_name'));
    }
    public function getData(Request $request,$department)
    {
//        dd($department);
        if($department == 1) {
            $columns = array(
                array('db' => 'id', 'dt' => 0),
                array('db' => 'year', 'dt' => 1),
                array('db' => 'tools', 'dt' => 2),
            );

            $draw = (int)$request->draw;
            $start = (int)$request->start;
            $length = (int)$request->length;
            $order = $request->order[0]["column"];
            $direction = $request->order[0]["dir"];
            $search = trim($request->search["value"]);


            $value = array();

            if (!empty($search)) {
                $count = BookPlan::select('id', 'year', 'type', 'book_id')
                    ->search($search)
                    ->department($department)
                    ->count();
                $plans = BookPlan::select('id', 'year', 'type', 'book_id')
                    ->search($search)
                    ->department($department)
                    ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                    ->get()->groupBy('year');
            } else {
                $count = BookPlan::select('id', 'year', 'type', 'book_id')
                    ->department($department)
                    ->count();
                $plans = BookPlan::select('id', 'year', 'type', 'book_id')
                    ->department($department)
                    ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                    ->get()->groupBy('year');
            }
            foreach ($plans as $index => $item) {
//            dd((int)$index);
                array_push(
                    $value,
                    [
                        'id' => '#',
                        'year' => $index,
                        'tools' => '
                        <button type="button" class="btn btn-warning btn-sm" data-url="' . route('plans.edit', ['year' => (int)$index, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="' . route('plans.destroy', ['year' => (int)$index, 'department' => $department]) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
                    ]
                );
            }
            return [
                "draw" => $draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => (array)$value,
                "order" => $columns[$order]["db"]
            ];
        }elseif($department == 2){
            $columns = array(
                array('db' => 'id', 'dt' => 0),
                array('db' => 'year', 'dt' => 1),
//                array('db' => 'data_analysis', 'dt' => 2),
//                array('db' => 'tools', 'dt' => 3),
            );

            $draw = (int)$request->draw;
            $start = (int)$request->start;
            $length = (int)$request->length;
            $order = $request->order[0]["column"];
            $direction = $request->order[0]["dir"];
            $search = trim($request->search["value"]);


            $value = array();

            if (!empty($search)) {
                $count = CourseBookPlan::distinct()
                    ->search($search)
                    ->department($department)
                    ->get(['year'])->count();
                $plans = CourseBookPlan::distinct()
                    ->search($search)
                    ->department($department)
                    ->limit($length)->offset($start)
                    ->get(['year']);
            } else {
                $count = CourseBookPlan::distinct()
                    ->department($department)
                    ->get(['year'])->count();
                $plans = CourseBookPlan::distinct()
                    ->department($department)->limit($length)->offset($start)
                    ->get(['year']);
            }
//            dd($plans);
            foreach ($plans as $index => $item) {
//            dd($item->year , Carbon::now()->format('Y'));
                $updatePlan = $item->year == Carbon::now()->format('Y') ?
                    '<button type="button" class="btn btn-warning" title="تحديث الخطة" onclick="regeneratePlan(this,\'/plans/'.$item->year.'/regenerate/2/\')"><i class="mdi mdi-refresh-circle"></i></button>' : '';
                array_push(
                    $value,
                    [
                        'id' => '#',
                        'year' =>'<a href="#!" data-url="' . route('plans.showCoursePlan', ['year' => $item->year, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">'.$item->year.'</a>',
                        'data_analysis' =>'<a href="#!" data-url="' . route('plans.areaCoursesProgressPercentage', ['year' => $item->year, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">تحليل انجازات الخطة</a>',
                        'tools' => $updatePlan.'
                        <button type="button" class="btn btn-danger" title="حذف الخطة" data-url="' . route('plans.destroy', ['year' => $item->year, 'department' => $department]) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
                    ]
                );
            }
            return [
                "draw" => $draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => (array)$value,
                "order" => $columns[$order]["db"]
            ];
        }
//        return $users;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($department,$year = 0,$outOfPlanTotal = 0)
    {
        switch ($department) {
            case 1 :{
                $department_name = 'لقسم التحفيظ';
                $old_plan = BookPlan::where('year',$year)->count();
                if(!$old_plan) {
                    $books = Book::department($department)->where('year',$year)->with(['plans.years.semesters'])->get();
                    return response()->json(['view' => view('control_panel.plans.basic.plans.create',compact('year','books','department_name','department'))->render(), 'errors' => 0]);
                }else{
                    $update_link =
                        '&nbsp;<a href="#!" data-url="' . route('plans.edit', ['year' => (int)$year, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">
                                هنا
                            </a>&nbsp;';
                    return response()->json(['view' => '', 'errors' => 1,'msg'=>' توجد خطة سنوية لسنة '.$year.' لتعديل الخطة اضغط '.$update_link],404);
                }
            }break;
            case 2 :
                {
                    $department_name = 'لقسم الدورات العلمية';
//                $old_plan = CourseBookPlan::where('year',$year)->count();
//                if(!$old_plan){
//                    $books = Book::department($department)->where('year',(int)$year)->get();
//                    if($books->count()) {
//                        $areas = Area::with(['subArea'])->where('area_id', null)->get();
//                        return response()->json(['view' => view('control_panel.plans.basic.plans.create', compact('year', 'areas', 'books', 'department_name', 'department'))->render(), 'errors' => 0]);
//                    }else{
//                        return response()->json(['view' => '', 'errors' => 1,'msg'=>'لا يوجد كتب للخطة السنوية المطلوبة ، يرجى اضافة كتب'],404);
//                    }
//                }else{
//                    $update_link =
//                        '&nbsp;<a href="#!" data-url="' . route('plans.edit', ['year' => (int)$year, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">
//                                هنا
//                            </a>&nbsp;';
//                    return response()->json(['view' => '', 'errors' => 1,'msg'=>' توجد خطة سنوية لسنة '.$year.' لتعديل الخطة اضغط '.$update_link],404);
//                }
//                dd($year,$outOfPlanTotal);
                    $year_plan = CourseBookPlan::where('year', $year)->get();
                    if(!$year_plan->count()) {
                        $areas_collection = Area::all();
                        $books = Book::department($department)->where('included_in_plan', 'داخل الخطة')->where('year', $year)->get();
                        if ($books->count()) {
                            foreach ($books as $key => $book) {
                                foreach ($areas_collection as $areaKey => $area) {
//                                    echo '<pre>';var_dump($area->name,$area->percentage);echo '</pre>';
//                                    dd($book->required_students_number , $area->sub_area_percentage);
                                    CourseBookPlan::create([
                                        'year' => $year,
                                        'book_id' => $book->id,
                                        'area_id' => $area->id,
                                        'value' => $book->required_students_number * $area->sub_area_percentage,
                                        'percentage' => $area->percentage,
                                    ]);
                                }
                            }
//                            dd('----------------');
                            if ($outOfPlanTotal) {
                                foreach ($areas_collection as $areaKey => $area) {
                                    CourseBookPlan::create([
                                        'year' => $year,
                                        'book_id' => null,
                                        'area_id' => $area->id,
                                        'value' => $outOfPlanTotal * $area->sub_area_percentage,
                                        'percentage' => $area->percentage,
                                    ]);
                                }
                            }
                            $areas = Area::with(['subArea'])->where('area_id', null)->get();
                            return response()->json(['view' => view('control_panel.plans.basic.plans.coursePlanShow', compact('year', 'areas', 'books', 'department', 'department_name'))->render(), 'errors' => 0]);
                        }else{
                            return response()->json(['view' => '', 'errors' => 1,'msg'=>'يرجى اضافة كتب للسنة المطلوبة.']);
                        }
                    }else{
                        return response()->json(['view' => '', 'errors' => 1,'msg'=>'الخطة موجودة مسبقا للتحديث اضغط زر التحديث.']);
                    }
            }break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newPlanRequest $request)
    {
//        dd($request->all());
        $year =$request->year;// explode(',', $request->year)[1];
        if($year) {
            if ($request->department == 1) { // تحفيظ
                if (isset($request->yearly_count)) {
                    foreach ($request->yearly_count as $book_id => $yearly_counts) {
                        $book_plan_mk = BookPlan::create([ // مكفولة
                            'year' => $year,
                            'type' => 'مكفولة',
                            'book_id' => $book_id
                        ]);
                        $book_plan_mt = BookPlan::create([ // متطوعة
                            'year' => $year,
                            'type' => 'متطوعة',
                            'book_id' => $book_id
                        ]);
                        $mk_year_plan_record = $book_plan_mk->years()->create([
                            'yearly_count' => $yearly_counts[0]
                        ]);
                        $mt_year_plan_record = $book_plan_mt->years()->create([
                            'yearly_count' => $yearly_counts[1]
                        ]);
                        foreach ($request->year_semester[$book_id][0] as $key => $semester) {
//                dd($semester,$semester[$key]);
                            $mk_year_plan_record->semesters()->create([
                                'year_semester' => $semester,
                                'semester_count' => $request->semester_count[$book_id][0][$key],
                                'month_count' => $request->month_count[$book_id][0][$key]
                            ]);
                        }
                        foreach ($request->year_semester[$book_id][1] as $key => $semester) {
                            $mt_year_plan_record->semesters()->create([
                                'year_semester' => $semester,
                                'semester_count' => $request->semester_count[$book_id][1][$key],
                                'month_count' => $request->month_count[$book_id][1][$key]
                            ]);
                        }
                    }
                }
                return response()->json(['msg' => 'تم اضافة بيانات الخطة', 'title' => 'اضافة', 'type' => 'success']);
            } elseif ($request->department == 2) { // دورات
                foreach ($request->sub_area_value as $area_id => $subAreaArray) {
//                dd($subAreaArray);
                    foreach ($subAreaArray as $sub_area_id => $bookArray) {
                        foreach ($bookArray as $book_id => $bookVal) {
                            CourseBookPlan::create([
                                'year' => $year,
                                'book_id' => $book_id,
                                'area_id' => $sub_area_id,
                                'value' => $bookVal,
                            ]);
                        }
                    }
                }
                return response()->json(['msg' => 'تم اضافة بيانات الخطة', 'title' => 'اضافة', 'type' => 'success']);
            }
//        dd($request->toArray());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book,$type)
    {
        $plan = BookPlan::
        where('book_id', $book->id)
            ->where('name', $type)
            ->first();
        if($type == 'سنوية') {
            $plan->load(['years.semesters.months']);
//        dd($plan->toArray());
        }else{
            $plan->load(['hours']);
        }
        return view('control_panel.plans.basic.show',compact('plan','book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($year,$department)
    {
        switch ($department) {
            case 1 :{
                $department_name = 'تعديل الخطة السنوية لكتب التحفيظ';
                $plans = BookPlan::with(['years.semesters'])->where('year',(int)$year)->department($department)->get()->groupBy('book_name');
                return view('control_panel.plans.basic.plans.update',compact('year','plans','department','department_name'));
            }break;
            case 2 :{
                $department_name = 'تعديل الخطة السنوية لكتب الدورات';

                $books = Book::department($department)->where('year',$year)->get();
                $areas = Area::with(['subArea'])->where('area_id',null)->get();

//                $plans = BookPlan::with(['years.semesters'])->where('year',(int)$year)->department($department)->get()->groupBy('book_name');
                return view('control_panel.plans.basic.plans.update',compact('year','books','areas','department','department_name'));
            }break;
        }
    }
    public function regenerate($year,$department,$outOfPlanTotal)
    {
//        dd($year,$department,$outOfPlanTotal);
        $department_name = 'لقسم الدورات العلمية';
        $year_plan = CourseBookPlan::where('year', $year)->get();
        if($year_plan->count()) {
            $areas_collection = Area::all();
            $books = Book::department($department)->where('included_in_plan', 'داخل الخطة')->where('year', $year)->get();
//            dd($books);
            if ($books->count()) {
                foreach ($books as $key => $book) {
                    foreach ($areas_collection as $areaKey => $area) {
                        $CourseBookPlan = CourseBookPlan::
                                            where('year' , $year)
                                            ->where('book_id' , $book->id)
                                            ->where('area_id' , $area->id)
                                            ->first();
//                        dd($CourseBookPlan);

                        if($CourseBookPlan) {
//                            var_dump($area->sub_area_percentage,$book->required_students_number * ($area->sub_area_percentage),'<br>');
                            $CourseBookPlan->update([
                                'value' => $book->required_students_number * ($area->sub_area_percentage),
                                'percentage' => $area->percentage,
                            ]);
                        }else{
                            CourseBookPlan::create([
                                'year' => $year,
                                'book_id' => $book->id,
                                'area_id' => $area->id,
                                'value' => $book->required_students_number * $area->sub_area_percentage,
                                'percentage' => $area->percentage,
                            ]);
                        }
                    }
//                    exit;
                }
                if ($outOfPlanTotal) {
                    foreach ($areas_collection as $areaKey => $area) {
                        $CourseBookPlan = CourseBookPlan::
                            where('year' , $year)
                            ->whereNull('book_id')
                            ->where('area_id' , $area->id)
                            ->first();
                        if($CourseBookPlan) {
                            $CourseBookPlan->update([
                                'value' => $outOfPlanTotal * ($area->sub_area_percentage),
                                'percentage' => $area->percentage,
                            ]);
                        }else{
                            CourseBookPlan::create([
                                'year' => $year,
                                'book_id' => null,
                                'area_id' => $area->id,
                                'value' => $outOfPlanTotal * $area->sub_area_percentage,
                                'percentage' => $area->percentage,
                            ]);
                        }
                    }
                }
                $areas = Area::with(['subArea'])->where('area_id', null)->get();
                return response()->json(['view' => view('control_panel.plans.basic.plans.coursePlanShow', compact('year', 'areas', 'books', 'department', 'department_name'))->render(), 'errors' => 0]);
            }else{
                return response()->json(['view' => '', 'errors' => 1,'msg'=>'يرجى اضافة كتب للسنة المطلوبة.']);
            }
        }else{
            return response()->json(['view' => '', 'errors' => 1,'msg'=>'الخطة موجودة مسبقا للتحديث اضغط زر التحديث.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updatePlanRequest $request,$year,$department)
    {
        if($department == 1) {
            $plans = BookPlan::where('year', (int)$year)->department($department)->get();
//            dd($plans);
            $plans->each(function ($plan) {
                $plan->delete();
            });
//            dd($plans);
            $year = count(explode(',', $request->year)) > 1 ? explode(',', $request->year)[1] : $request->year;
            foreach ($request->yearly_count as $book_id => $yearly_counts) {
                $book_plan_mk = BookPlan::create([ // مكفولة
                    'year' => $year,
                    'type' => 'مكفولة',
                    'book_id' => $book_id
                ]);
                $book_plan_mt = BookPlan::create([ // متطوعة
                    'year' => $year,
                    'type' => 'متطوعة',
                    'book_id' => $book_id
                ]);
                $mk_year_plan_record = $book_plan_mk->years()->create([
                    'yearly_count' => $yearly_counts[0]
                ]);
                $mt_year_plan_record = $book_plan_mt->years()->create([
                    'yearly_count' => $yearly_counts[1]
                ]);
                foreach ($request->year_semester[$book_id][0] as $key => $semester) {
//                dd($semester,$semester[$key]);
                    $mk_year_plan_record->semesters()->create([
                        'year_semester' => $semester,
                        'semester_count' => $request->semester_count[$book_id][0][$key],
                        'month_count' => $request->month_count[$book_id][0][$key]
                    ]);
                }
                foreach ($request->year_semester[$book_id][1] as $key => $semester) {
                    $mt_year_plan_record->semesters()->create([
                        'year_semester' => $semester,
                        'semester_count' => $request->semester_count[$book_id][1][$key],
                        'month_count' => $request->month_count[$book_id][1][$key]
                    ]);
                }
            }
            return response()->json(['msg' => 'تم تعديل بيانات الخطة', 'title' => 'تعديل', 'type' => 'success']);
        }elseif ($department == 2){
            $year = count(explode(',', $request->year)) > 1 ? explode(',', $request->year)[1] : $request->year;
            foreach ($request->sub_area_value as $area_id => $subAreaArray) {
//                dd($subAreaArray);
                foreach ($subAreaArray as $sub_area_id => $bookArray) {
                    foreach ($bookArray as $book_id => $bookVal) {
                        CourseBookPlan::
                            where('year',$year)
                            ->where('book_id',$book_id)
                            ->where('area_id',$sub_area_id)
                            ->first()
                            ->update([
                                'year'=>$year,
                                'book_id'=>$book_id,
                                'area_id'=>$sub_area_id,
                                'value'=>$bookVal,
                            ]);
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($year,$department)
    {
        $plans = CourseBookPlan::where('year',(int)$year)->department($department)->get();
        return response()->json(['msg'=>'تم حذف بيانات الخطة','title'=>'حذف','type'=>'success']);
    }
    public function areaCoursesProgressPercentage($year,$department){
        if($department == 2){
            $areas = Area::whereNull('area_id')->get();
            $books = Book::with(['coursePlans'])->department(2)->where('year',$year)->where('included_in_plan','داخل الخطة')->get();
            return view('control_panel.plans.basic.plans.areaCoursesProgressPercentage',compact('books','areas','year'));
        }else{
        }
    }
    public function areaCoursesProgressPercentageToPrint($year,$department){
        if($department == 2){
            $areas = Area::whereNull('area_id')->get();
            $books = Book::with(['coursePlans'])->department(2)->where('year',$year)->where('included_in_plan','داخل الخطة')->get();
            return view('control_panel.plans.basic.plans.areaCoursesProgressPercentageToPrint',compact('books','areas','year'));
        }else{
        }
    }
    public function subAreaCoursesProgressPercentage($year,$area_id){
        $areas = Area::whereNull('area_id')->get();
        $books = Book::with(['coursePlans'])->where('year',$year)->where('included_in_plan','داخل الخطة')->get();
        return view('control_panel.plans.basic.plans.subAreaCoursesProgressPercentage',compact('books','areas','year'));
    }
    public function showCoursePlan($year,$department){
        if($department == 2){
            $department_name = 'تعديل الخطة السنوية لكتب الدورات';
            $books = Book::department($department)->where('year',$year)->where('included_in_plan', 'داخل الخطة')->get();
            $areas = Area::with(['subArea'])->where('area_id',null)->get();
            return view('control_panel.plans.basic.plans.coursePlanShow', compact('year', 'areas', 'books','department','department_name'));
        }else{
        }
    }
    public function CoursePlansFatherAreaSonsValues($year,$area_id,$book_id){
        $department_name = 'تحليل البيانات';
        $department = 2;
        $main_area = Area::find($area_id);
        if($main_area) {
            $areas = Area::where('area_id', $area_id)->get();
            $book = Book::findOrFail($book_id);
            return view('control_panel.plans.basic.plans.subAreaCoursesProgressPercentage', compact('year', 'main_area', 'areas', 'book', 'department', 'department_name'));
        }else{

        }
    }
    public function CoursePlansFatherAreaSonsAllBooksValues($year,$area_id){
        $department_name = 'تحليل البيانات';
        $department = 2;
        $main_area = Area::find($area_id);
        if($main_area) {
            $areas = Area::where('area_id', $area_id)->get();
            $books = Book::department($department)->where('year',$year)->where('included_in_plan', 'داخل الخطة')->get();
            return view('control_panel.plans.basic.plans.subAreaCoursesProgressPercentageAllBooks', compact('year', 'main_area', 'areas', 'books', 'department', 'department_name'));
        }else{

        }
    }
}
