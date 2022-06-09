<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Circle;
use App\Models\CircleAgenda;
use App\Models\CircleBooks;
use App\Models\CircleMonthlyReport;
use App\Models\CircleMonthlyReportStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleMonthlyReportsController extends Controller
{
    public function getCircleMonthlyReports(Circle $circle){
        $teacher = $circle->teacher;

        $circleMonthlyReports = CircleMonthlyReport::where('circle_id',$circle->id)->get();
//        dd($teacher,$circle->teacher_id,$circle->load('teacher'));
        return view('control_panel.circles.monthly_reports.basic.getCircleMonthlyReports',compact('circle','teacher','circleMonthlyReports'));
    }


    public function getCircleMonthlyReportsData(Request $request,Circle $circle){
//        dd($circle);
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'date',      'dt' => 1 )
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = CircleMonthlyReport::search($search)
                ->where('circle_id',$circle->id)->count();
            $CircleMonthlyReport = CircleMonthlyReport::search($search)
                ->where('circle_id',$circle->id)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = CircleMonthlyReport::where('circle_id',$circle->id)->count();
            $CircleMonthlyReport = CircleMonthlyReport::limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->where('circle_id',$circle->id)->get();
        }
        foreach ($CircleMonthlyReport as $index => $item){
            array_push($value , $item->circle_monthly_report_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getTeacherMonthlyReports(User $user){

        return view('control_panel.circles.monthly_reports.basic.getTeacherMonthlyReports',compact('user'));
    }
    public function getTeacherMonthlyReportsData(Request $request,User $user){
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'date',      'dt' => 1 )
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = CircleMonthlyReport::search($search)
                ->teachercircles($user->id)->count();
            $CircleMonthlyReport = CircleMonthlyReport::search($search)
                ->teachercircles($user->id)->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = CircleMonthlyReport::teachercircles($user->id)->count();
            $CircleMonthlyReport = CircleMonthlyReport::teachercircles($user->id)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        foreach ($CircleMonthlyReport as $index => $item){
            array_push($value , $item->circle_monthly_report_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }



    public function createCircleMonthlyReports(Circle $circle,$date){
        // if(Carbon::parse($date)->isFuture() && !Carbon::parse($date)->isCurrentMonth()){
        //     return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري لشهر في المستقبل']);
        // }elseif (Carbon::parse($date)->lt(Carbon::parse($circle->start_date)->startOfMonth())){
        //     return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري لشهر قبل تاريخ بداية الحلقة']);
        // }
        // $isMonthInYearAgenda = CircleAgenda::where('year',Carbon::now()->year)->where('exam_month',Carbon::parse($date)->month)->first();
        // if($isMonthInYearAgenda){
        //     return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري لشهر اختبار ضمن الاجندة السنوية']);
        // }else {
//            dd($date,Carbon::parse($date)->isCurrentMonth());
            // if(Carbon::parse($date)->isCurrentMonth()) {
            //     $startDate = Carbon::now()->startOfMonth()->addDays(24);
            //     $endDate = Carbon::now()->endOfMonth();
            //     $check = Carbon::now()->between($startDate, $endDate, true);

            //     if ($check) {
                    $isMonthInYearAgenda = CircleAgenda::where('year', Carbon::now()->year)->where('exam_month', Carbon::parse($date)->subMonth()->month)->first() ? 2 : 1;
                    $prevDate = Carbon::parse($date)->subMonths($isMonthInYearAgenda)->format('Y-m-d');
                    $prevFirstDay = Carbon::parse($prevDate)->startOfMonth()->format('Y-m-d');
                    $prevLastDay = Carbon::parse($prevDate)->endOfMonth()->format('Y-m-d');
                    $circlePrevMonthlyReport = CircleMonthlyReport::where('circle_id', $circle->id)->whereDate('date', '<=', $prevLastDay)->whereDate('date', '>=', $prevFirstDay)->first();
                    if ($circlePrevMonthlyReport) {
                        $circleMonthlyReport = CircleMonthlyReport::create([
                            'circle_id' => $circle->id,
                            'date' => Carbon::parse($date)->format('Y-m-d')
                        ]);
                        $circleMonthlyReport->startReport($circlePrevMonthlyReport);
                        return response()->json(['view' => view('control_panel.circles.monthly_reports.basic.circleMonthlyReportEntryForm', compact('circleMonthlyReport','circle'))->render(), 'errors' => 0]);
                    } else {
                        // $circleReportsCount = CircleMonthlyReport::where('circle_id', $circle->id)->count();
                        // if ($circleReportsCount) {
                        //     return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة التقرير للشهر الحالي ، يوجد تقارير مسبقة غير مدخلة']);
                        // } else {
                            $circleMonthlyReport = CircleMonthlyReport::create([
                                'circle_id' => $circle->id,
                                'date' => Carbon::parse($date)->format('Y-m-d')
                            ]);
                            $circleMonthlyReport->startReport(null);
                            return response()->json(['view' => view('control_panel.circles.monthly_reports.basic.circleMonthlyReportEntryForm', compact('circleMonthlyReport','circle'))->render(), 'errors' => 0]);
                        // }
                    }
                // }else{
                //     if (Carbon::parse($date)->isCurrentMonth()) {
                //         return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري الا في فترة من 25 الى 30 الشهر.']);
                //     } else {
                //         return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري بعد انقضاء فترة الادخال']);
                //     }                }
            // }else{
                $limitDate = $circle->teacher->monthly_report_limit;
//                dd($limitDate);
                if($limitDate || Auth::user()->hasRole('مدير الدائرة') || Auth::user()->hasRole('مساعد اداري') || Auth::user()->hasRole('رئيس الدائرة')) {
                    $acceptedLimit = Carbon::parse($limitDate)->gte(Carbon::now());
                    if($acceptedLimit){
                        $isMonthInYearAgenda = CircleAgenda::where('year', Carbon::now()->year)->where('exam_month', Carbon::parse($date)->subMonth()->month)->first() ? 2 : 1;
                        $prevDate = Carbon::parse($date)->subMonths($isMonthInYearAgenda)->format('Y-m-d');
                        $prevFirstDay = Carbon::parse($prevDate)->startOfMonth()->format('Y-m-d');
                        $prevLastDay = Carbon::parse($prevDate)->endOfMonth()->format('Y-m-d');
                        $circlePrevMonthlyReport = CircleMonthlyReport::where('circle_id', $circle->id)->whereDate('date', '<=', $prevLastDay)->whereDate('date', '>=', $prevFirstDay)->first();

                        if ($circlePrevMonthlyReport) {
                            $circleMonthlyReport = CircleMonthlyReport::create([
                                'circle_id' => $circle->id,
                                'date' => Carbon::parse($date)->format('Y-m-d'),
                                'status'=>2
                            ]);
                            $circleMonthlyReport->startReport($circlePrevMonthlyReport);
                            return response()->json(['view' => view('control_panel.circles.monthly_reports.basic.circleMonthlyReportEntryForm', compact('circleMonthlyReport','circle'))->render(), 'errors' => 0]);
                        } else {
                            $circleReportsCount = CircleMonthlyReport::where('circle_id', $circle->id)->count();
                            if ($circleReportsCount) {
                                return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة التقرير للشهر الحالي ، يوجد تقارير مسبقة غير مدخلة']);
                            } else {
                                $circleMonthlyReport = CircleMonthlyReport::create([
                                    'circle_id' => $circle->id,
                                    'date' => Carbon::parse($date)->format('Y-m-d'),
                                    'status'=>2
                                ]);
                                $circleMonthlyReport->startReport(null);
                                return response()->json(['view' => view('control_panel.circles.monthly_reports.basic.circleMonthlyReportEntryForm', compact('circleMonthlyReport','circle'))->render(), 'errors' => 0]);
                            }
                        }
                    }else{
                        if (!Carbon::now()->diffInMonths(Carbon::parse($date))) {
                            return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري الا في فترة من 25 الى 30 الشهر.']);
                        } else {
                            return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري بعد انقضاء فترة الادخال']);
                        }
                    }
                }else{
                    if (!Carbon::now()->diffInMonths(Carbon::parse($date))) {
                        return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري الا في فترة من 25 الى 30 الشهر.']);
                    } else {
                        return response()->json(['view' => '', 'errors' => 1, 'msg' => 'لا يمكن اضافة تقرير شهري بعد انقضاء فترة الادخال']);
                    }
                }
            // }
        // }
    }

    public function makeReportDelivered(CircleMonthlyReport $circleMonthlyReport){
        CircleMonthlyReport::where('id', $circleMonthlyReport->id)->update([
                'is_delivered' => 1,
                'delivered_by' => Auth::user()->id,
                'delivered_at' => Carbon::now()
        ]);
        return response()->json(['title' => 'تسليم', 'type' => 'success','msg'=>'تم تسليم التقرير بنجاح']);
    }

    public function makeReportApproved(CircleMonthlyReport $circleMonthlyReport){
        CircleMonthlyReport::where('id', $circleMonthlyReport->id)->update([
                'is_approved' => 1,
                'approved_by' => Auth::user()->id,
                'approved_at' => Carbon::now()
        ]);
        return response()->json(['title' => 'إعتماد التقرير', 'type' => 'success','msg'=>'تم إعتماد التقرير بنجاح']);
    }


    public function changeCurrentToValue($value,$report_student_id){
        $circleMonthlyReportStudent = CircleMonthlyReportStudent::find($report_student_id);
        $circleMonthlyReport = CircleMonthlyReport::find($circleMonthlyReportStudent->circle_monthly_report_id);
        $date = $circleMonthlyReport->date;
        $student_id = $circleMonthlyReportStudent->student_id;
        $circle_id =  $circleMonthlyReport->circle_id;

        $nextDate = Carbon::parse($date)->addMonths(1)->format('Y-m-d');
        // $prevFirstDay = Carbon::parse($prevDate)->startOfMonth()->format('Y-m-d');
        // $prevLastDay = Carbon::parse($prevDate)->endOfMonth()->format('Y-m-d');
        $circleNextMonthlyReport = CircleMonthlyReport::where('circle_id', $circle_id)->where('date', $nextDate)->first();
        $circleNextMonthlyReport = ($circleNextMonthlyReport)? $circleNextMonthlyReport : '';

        if($circleMonthlyReportStudent){
            $circleMonthlyReportStudent->update(['current_to'=>$value]);

            if($circleNextMonthlyReport){
                CircleMonthlyReportStudent::where('student_id', $student_id)
                ->where('circle_monthly_report_id', $circleNextMonthlyReport->id)
                ->update(['previous_from'=>1,'previous_to'=>$value,'current_from' => $value + 1]);

            }
            return [$circleMonthlyReportStudent->current_category,$circleMonthlyReportStudent->current_storage,$circleMonthlyReportStudent->student_progress_percentage];
        }else{

        }
    }


    public function updateCircleMonthlyReports(CircleMonthlyReport $circleMonthlyReport){


        // dd($circleMonthlyReport->date);

        $date = $circleMonthlyReport->date;

        $circle = $circleMonthlyReport->circle;


        // $isMonthInYearAgenda = CircleAgenda::where('year', Carbon::now()->year)->where('exam_month', Carbon::parse($date)->subMonth()->month)->first() ? 2 : 1;
        $prevDate = Carbon::parse($date)->subMonths(1)->format('Y-m-d');
        $prevFirstDay = Carbon::parse($prevDate)->startOfMonth()->format('Y-m-d');
        $prevLastDay = Carbon::parse($prevDate)->endOfMonth()->format('Y-m-d');
        $circlePrevMonthlyReport = CircleMonthlyReport::where('circle_id', $circleMonthlyReport->circle->id)->where('date', $prevDate)->first();
        $circlePrevMonthlyReport = ($circlePrevMonthlyReport)? $circlePrevMonthlyReport : '';

        // dd($circlePrevMonthlyReport);

        return view('control_panel.circles.monthly_reports.basic.circleMonthlyReportEntryForm',compact('circle','circleMonthlyReport'));


    }

    public function deleteCircleMonthlyReport(CircleMonthlyReport $circleMonthlyReport){
        $circleMonthlyReport->delete();
        return response()->json(['title' => 'حذف', 'type' => 'success','msg'=>'تم حذف التقرير بنجاح']);
    }
    public function showCircleMonthlyReport(CircleMonthlyReport $circleMonthlyReport){
        $circle = $circleMonthlyReport->circle;
        return view('control_panel.circles.monthly_reports.basic.showCircleMonthlyReport',compact('circleMonthlyReport','circle'));
    }
    public function letEnterLateReports(User $user,$date){
        $user->update(['monthly_report_limit'=>$date]);
        return response()->json(['title' => 'تعديل', 'type' => 'success','msg'=>' تم السماح بادخال التقارير المتأخرة الى يوم ' . $date]);
    }
}
