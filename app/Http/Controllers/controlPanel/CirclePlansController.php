<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookPlan;
use App\Models\CircleAgenda;
use App\Models\CircleBooks;
use App\Models\CirclePlan;
use Illuminate\Http\Request;

class CirclePlansController extends Controller
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
        return view('control_panel.circles.plans.index');
    }
    public function getData(Request $request)
    {
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
            $count = CirclePlan::search($search)->distinct()
                ->get(['year'])->count();
            $plans = CirclePlan::search($search)->distinct()
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get(['year']);
        } else {
            $count = CirclePlan::distinct()->get(['year'])->count();
            $plans = CirclePlan::distinct()->limit($length)->offset($start)
//                ->orderBy($columns[$order]["db"], $direction)
                ->get(['year']);
        }
        foreach ($plans as $index => $item) {
//            dd((int)$index);
            array_push(
                $value,
                [
                    'id' => $item->id,
                    'year' => '<a href="#!" data-url="' . route('circlePlans.show', $item->year) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">'.$item->year.'</a>',
                    'agenda' => '<a href="#!" data-url="' . route('circlePlans.agenda', $item->year) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">الاجندة لسنة '.$item->year.'</a>',
//                    'tools' => '
//                    <button type="button" class="btn btn-warning btn-sm" data-url="' . route('plans.edit', ['year' => (int)$index, 'department' => $department]) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
//                    <button type="button" class="btn btn-danger btn-sm" data-url="' . route('plans.destroy', ['year' => (int)$index, 'department' => $department]) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
//                '
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($year)
    {
        if($year) {
            $planYear = CirclePlan::where('year', 'like', '%' . $year . '%')->first();
            if ($planYear) {
                $update_link =
                    '&nbsp;<a href="#!" data-url="' . route('circlePlans.show', $year) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">
                            هنا
                        </a>&nbsp;';
                return response()->json(['view' => '', 'errors' => 1, 'msg' => ' توجد خطة سنوية لسنة ' . $year . ' لتعديل الخطة اضغط ' . $update_link], 404);
            }
            $books = CircleBooks::all();
            foreach ($books as $book) {
                CirclePlan::create(['year' => $year,'book_id'=>$book->id]);
            }
            $this->show($year);
        }else{
            return response()->json(['view' => '', 'errors' => 1, 'msg' => ' يرجى اضافة سنة دراسية '], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CirclePlan  $circlePlan
     * @return \Illuminate\Http\Response
     */
    public function show($year)
    {
        $books = CircleBooks::all();
//        dd($books[0]->bookPlanDataForTable($year));
        return view('control_panel.circles.plans.show',compact('year','books'));
    }
    public function agenda($year)
    {
        $agendas = CircleAgenda::where('year',$year)->get();
        return view('control_panel.circles.plans.agenda',compact('year','agendas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CirclePlan  $circlePlan
     * @return \Illuminate\Http\Response
     */
    public function edit(CircleBooks $circleBooks)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CirclePlan  $circlePlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CirclePlan $circlePlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CirclePlan  $circlePlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(CirclePlan $circlePlan)
    {
        //
    }
    public function updateCircleBookPlan($year,$book_id,$value,Request $request){
//        dd($request);
        $plan = CirclePlan::where('year',$year)->where('book_id',$book_id)->first();
        if($plan) {
            if ((int)$value && $request->input_name) {
                $plan->update([
                    $request->input_name => $value,
                ]);
            }
        }
    }
    public function getAddNewCirclePlanAgendaSemester($year){
        $allMonths = [1,2,3,4,5,6,7,8,9,10,11,12];
        $months = [];
        $allUsedMonths = CircleAgenda::where('year',$year)->get();
        $usedMonths = $allUsedMonths->pluck('months')->toArray();
        $usedExamMonths = $allUsedMonths->pluck('exam_month')->toArray();
        if(!count($usedMonths) && !count($usedExamMonths)){
            $months = $allMonths;
        }else{
            $allMergedMonths = [];
            foreach ($usedMonths as $usedMonth){
                $allMergedMonths = array_merge($allMergedMonths,json_decode($usedMonth));
            }
            foreach ($usedExamMonths as $usedExamMonth){
                array_push($allMergedMonths,(int)$usedExamMonth);
            }
            foreach ($allMonths as $key => $allMonth){
                if(in_array($allMonth, $allMergedMonths)) {
                    unset($allMonths[$key]);
//                    var_dump($allMonth);
                }
            }
            $months = $allMonths;
        }
        return view('control_panel.circles.plans.getAddNewCirclePlanAgendaSemester',compact('months','year'));
    }
    public function storeNewCirclePlanAgendaSemester(Request $request,$year){
        $old_semester = CircleAgenda::where('semester',$request->semester)->where('year',$year)->first();
        if(!$old_semester){
            $previous_value = 0;
            foreach ($request->months as $key => $value){
                if($key){
                    $previous_value++;
                    if($value != $previous_value){
                        return response()->json(['msg'=>'يجب ان تكون الأشهر متسلسلة','errors'=>1]);
                    }
                    if ($key==(count($request->months)-1)){
                        if($request->exam_month != $value+1){
                            return response()->json(['msg'=>'يجب ان يكون شهر الاختبار الشهر الذي يلي أخر شهر في أشهر الدراسة','errors'=>1]);
                        }
                    }
                }else{
                    if(count($request->months) == 1 && $request->exam_month != $value+1){
                        return response()->json(['msg'=>'يجب ان يكون شهر الاختبار الشهر الذي يلي أخر شهر في أشهر الدراسة','errors'=>1]);
                    }
                    $previous_value=$value;
                }
            }

            // code
            CircleAgenda::create([
                'year'=>$year,
                'semester'=>$request->semester,
                'months'=>$request->months,
                'exam_month'=>$request->exam_month
            ]);
            return response()->json(['msg'=>'تم اضافة فصل دراسي جديد','errors'=>0]);
        }else{
            return response()->json(['msg'=>'الفصل '.$request->semester.' موجود مسبقا ','errors'=>1]);
        }
//        dd($request->all(),$year);
    }
    public function deleteCirclePlanAgendaSemester($agenda_id){
        CircleAgenda::destroy($agenda_id);
        return response()->json(['msg'=>'تم حذف الفصل الدراسي بنجاح','errors'=>0]);
    }
}
