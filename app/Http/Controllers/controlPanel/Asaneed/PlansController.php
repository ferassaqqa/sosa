<?php

namespace App\Http\Controllers\controlPanel\Asaneed;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\AsaneedBook;
use App\Models\AsaneedBookPlan;
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
    public function index()
    {
        return view('control_panel.asaneed.plans.index');
    }
    public function getData(Request $request)
    {
        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'year', 'dt' => 1),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if (!empty($search)) {
            $count = AsaneedBookPlan::distinct()
                ->search($search)
                ->get(['year'])->count();
            $plans = AsaneedBookPlan::distinct()
                ->search($search)
                ->limit($length)->offset($start)
                ->get(['year']);
        } else {
            $count = AsaneedBookPlan::distinct()
                ->get(['year'])->count();
            $plans = AsaneedBookPlan::distinct()
                ->limit($length)->offset($start)
                ->get(['year']);
        }
//            dd($plans);
        foreach ($plans as $index => $item) {
//            dd($index,$item);
            array_push(
                $value,
                [
                    'id' => '#',
                    'year' =>'<a href="#!" data-url="' . route('asaneedPlans.showCoursePlan', ['year' => $item->year]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">'.$item->year.'</a>',
                    'data_analysis' =>'<a href="#!" data-url="' . route('asaneedPlans.areaCoursesProgressPercentage', ['year' => $item->year]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">تحليل انجازات الخطة</a>',
                    'tools' => '
                    <button type="button" class="btn btn-warning" title="تحديث الخطة" onclick="regeneratePlan(this,\'/asaneedPlans/'.$item->year.'/regenerate/\')"><i class="mdi mdi-refresh-circle"></i></button>
                    <button type="button" class="btn btn-danger" title="حذف الخطة" data-url="' . route('asaneedPlans.destroy', ['year' => $item->year]) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
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
//        return $users;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($year = 0,$outOfPlanTotal = 0)
    {
        $year_plan = AsaneedBookPlan::where('year', $year)->get();
        if(!$year_plan->count()) {
            $areas_collection = Area::all();
            $books = AsaneedBook::where('included_in_plan', 'داخل الخطة')->where('year', $year)->get();
            if ($books->count()) {
                foreach ($books as $key => $book) {
                    foreach ($areas_collection as $areaKey => $area) {
//                                    echo '<pre>';var_dump($area->name,$area->percentage);echo '</pre>';
//                                    dd($book->required_students_number , $area->sub_area_percentage);
                        AsaneedBookPlan::create([
                            'year' => $year,
                            'book_id' => $book->id,
                            'area_id' => $area->id,
                            'value' => $book->required_students_number * $area->sub_area_percentage,
                            'percentage' => $area->percentage,
                        ]);
                    }
                }
                if ($outOfPlanTotal) {
                    foreach ($areas_collection as $areaKey => $area) {
                        AsaneedBookPlan::create([
                            'year' => $year,
                            'book_id' => null,
                            'area_id' => $area->id,
                            'value' => $outOfPlanTotal * $area->sub_area_percentage,
                            'percentage' => $area->percentage,
                        ]);
                    }
                }
                $areas = Area::with(['subArea'])->where('area_id', null)->get();
                return response()->json(['view' => view('control_panel.asaneed.plans.coursePlanShow', compact('year', 'areas', 'books'))->render(), 'errors' => 0]);
            }else{
                return response()->json(['view' => '', 'errors' => 1,'msg'=>'يرجى اضافة كتب للسنة المطلوبة.']);
            }
        }else{
            return response()->json(['view' => '', 'errors' => 1,'msg'=>'الخطة موجودة مسبقا للتحديث اضغط زر التحديث.']);
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
        return view('control_panel.asaneed.show',compact('plan','book'));
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
                return view('control_panel.asaneed.plans.update',compact('year','plans','department','department_name'));
            }break;
            case 2 :{
                $department_name = 'تعديل الخطة السنوية لكتب الدورات';

                $books = Book::department($department)->where('year',$year)->get();
                $areas = Area::with(['subArea'])->where('area_id',null)->get();

//                $plans = BookPlan::with(['years.semesters'])->where('year',(int)$year)->department($department)->get()->groupBy('book_name');
                return view('control_panel.asaneed.plans.update',compact('year','books','areas','department','department_name'));
            }break;
        }
    }
    public function regenerate($year,$outOfPlanTotal)
    {
//        dd($year,$department,$outOfPlanTotal);
        $year_plan = AsaneedBookPlan::where('year', $year)->get();
        if($year_plan->count()) {
            $areas_collection = Area::all();
            $books = AsaneedBook::where('included_in_plan', 'داخل الخطة')->where('year', $year)->get();
            if ($books->count()) {
                foreach ($books as $key => $book) {
                    foreach ($areas_collection as $areaKey => $area) {
                        $CourseBookPlan = AsaneedBookPlan::
                        where('year' , $year)
                            ->where('book_id' , $book->id)
                            ->where('area_id' , $area->id)
                            ->first();
//                        dd($book->toArray(),$area->toArray(),$CourseBookPlan);
                        if($CourseBookPlan) {
//                            dd($area->sub_area_percentage);
                            $CourseBookPlan->update([
                                'value' => $book->required_students_number * ($area->sub_area_percentage),
                                'percentage' => $area->percentage,
                            ]);
                        }else{
                            AsaneedBookPlan::create([
                                'year' => $year,
                                'book_id' => $book->id,
                                'area_id' => $area->id,
                                'value' => $book->required_students_number * $area->sub_area_percentage,
                                'percentage' => $area->percentage,
                            ]);
                        }
                    }
                }
                if ($outOfPlanTotal) {
                    foreach ($areas_collection as $areaKey => $area) {
                        $CourseBookPlan = AsaneedBookPlan::
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
                            AsaneedBookPlan::create([
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
                return response()->json(['view' => view('control_panel.asaneed.plans.coursePlanShow', compact('year', 'areas', 'books'))->render(), 'errors' => 0]);
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
    public function areaCoursesProgressPercentage($year){
        $areas = Area::whereNull('area_id')->get();
        $books = AsaneedBook::with(['coursePlans'])->where('year',$year)->where('included_in_plan','داخل الخطة')->get();
        return view('control_panel.asaneed.plans.areaCoursesProgressPercentage',compact('books','areas','year'));
    }
    public function subAreaCoursesProgressPercentage($year,$area_id){
        $areas = Area::whereNull('area_id')->get();
        $books = Book::with(['coursePlans'])->where('year',$year)->where('included_in_plan','داخل الخطة')->get();
        return view('control_panel.asaneed.plans.subAreaCoursesProgressPercentage',compact('books','areas','year'));
    }
    public function showCoursePlan($year){
            $books = AsaneedBook::where('year',$year)->where('included_in_plan', 'داخل الخطة')->get();
            $areas = Area::with(['subArea'])->where('area_id',null)->get();
            return view('control_panel.asaneed.plans.coursePlanShow', compact('year', 'areas', 'books'));
    }
    public function CoursePlansFatherAreaSonsValues($year,$area_id,$book_id){
        $department_name = 'تحليل البيانات';
        $department = 2;
        $main_area = Area::find($area_id);
        if($main_area) {
            $areas = Area::where('area_id', $area_id)->get();
            $book = AsaneedBook::findOrFail($book_id);
            return view('control_panel.asaneed.plans.subAreaCoursesProgressPercentage', compact('year', 'main_area', 'areas', 'book', 'department', 'department_name'));
        }else{

        }
    }
}
