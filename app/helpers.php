<?php

function checkPermissionHelper($helper){
    if(!\Illuminate\Support\Facades\Auth::guest()){
        if(!\Illuminate\Support\Facades\Auth::user()->can($helper)){
            return abort(403);
        }
    }
//    dd($helper,\Illuminate\Support\Facades\Auth::user()->can($helper));
}
function hasPermissionHelper($helper){
    if(!\Illuminate\Support\Facades\Auth::guest()){
        if(!\Illuminate\Support\Facades\Auth::user()->can($helper)){
            return false;
        }else{
            return true;
        }
    }
}
function getCoursePlanSubAreaBookValue($sub_area_id,$book_id,$year){
    if($year){
        $sub_area_value = \App\Models\CourseBookPlan::
                            where('area_id',$sub_area_id)
                            ->where('book_id',$book_id)
                            ->where('year',$year)
                            ->first();
        if($sub_area_value){
            return $sub_area_value->value;
        }else{
            return '';
        }
    }else{
        return '';
    }
}
function getCoursePlanAreaTotalValue($area_id,$year){
    if($year){
        $area = \App\Models\CourseBookPlan::
                            whereHas('area',function($query) use($area_id){
                                $query->where('area_id',$area_id);
                            })
                            ->where('year',$year)
                            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getOutOfPlanAreaTotalValue($area_id,$year){
    if($year){
        $area = \App\Models\CourseBookPlan::
                            whereHas('area',function($query) use($area_id){
                                $query->where('area_id',$area_id);
                            })
                            ->where('year',$year)
                            ->whereNull('book_id')
                            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getOutOfPlanAllAreasTotalRequiredValue($year){
    if($year){
        $area = \App\Models\CourseBookPlan::where('year',$year)
                            ->whereNull('book_id')
                            ->whereHas('area',function ($query){
                                $query->whereNull('area_id');
                            })
                            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getOutOfPlanAllAreasTotalDoneValue($year){
    if($year){
        $finishedCourses = \App\Models\Course::where('status','منتهية')->where('included_in_plan','داخل الخطة');
        if($finishedCourses->count()){
            $total = 0;
            foreach($finishedCourses as $course){
                $total += $course->passedStudents->count();
            }
            return $total;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}


function getAsaneedCoursePlanSubAreaBookValue($sub_area_id,$book_id,$year){
    if($year){
        $sub_area_value = \App\Models\AsaneedBookPlan::
        where('area_id',$sub_area_id)
            ->where('book_id',$book_id)
            ->where('year',$year)
            ->first();
        if($sub_area_value){
            return $sub_area_value->value;
        }else{
            return '';
        }
    }else{
        return '';
    }
}
function getAsaneedCoursePlanAreaTotalValue($area_id,$year){
    if($year){
        $area = \App\Models\AsaneedBookPlan::
        whereHas('area',function($query) use($area_id){
            $query->where('area_id',$area_id);
        })
            ->where('year',$year)
            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getAsaneedOutOfPlanAllAreasTotalRequiredValue($year){
    if($year){
        $area = \App\Models\AsaneedBookPlan::where('year',$year)
            ->whereNull('book_id')
            ->whereHas('area',function ($query){
                $query->whereNull('area_id');
            })
            ->get();
//        dd($area);
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getAsaneedOutOfPlanAllAreasTotalDoneValue($year){
    if($year){
        $finishedCourses = \App\Models\AsaneedCourse::where('status','منتهية')->where('included_in_plan','داخل الخطة');
        if($finishedCourses->count()){
            $total = 0;
            foreach($finishedCourses as $course){
                $total += $course->passedStudents->count();
            }
            return $total;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getAsaneedBookPlanAreaTotalValue($book_id,$year){
    if($year){
        $area = \App\Models\AsaneedBookPlan::
                            where('year',$year)
                            ->where('book_id',$book_id)
                            ->whereHas('area',function($query){
                                $query->whereNull('area_id');
                            })
                            ->get();
//        dd($area->each(function($ar){$ar->value ? var_dump($ar->value,$ar->id) : 0;}));
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getAsaneedOutOfPlanAreaTotalValue($area_id,$year){
    if($year){
        $area = \App\Models\AsaneedBookPlan::
        whereHas('area',function($query) use($area_id){
            $query->where('area_id',$area_id);
        })
            ->where('year',$year)
            ->whereNull('book_id')
            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getAsaneedBookPlanAreaIdTotalValue($book_id,$year,$area_id){
    if($year){
        $area = \App\Models\AsaneedBookPlan::
        where('year',$year)
            ->where('book_id',$book_id)
            ->whereHas('area',function($query) use ($area_id){
                $query->where('area_id',$area_id);
            })
            ->get();
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function getBookPlanAreaTotalValue($book_id,$year){
    if($year){
        $area = \App\Models\CourseBookPlan::
                            where('year',$year)
                            ->where('book_id',$book_id)
                            ->whereHas('area',function($query){
                                $query->whereNull('area_id');
                            })
                            ->get();
//        dd($area->pluck('value')->toArray());
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function getBookPlanAreaIdTotalValue($book_id,$year,$area_id){
    if($year){
        $area = \App\Models\CourseBookPlan::
                            where('year',$year)
                            ->where('book_id',$book_id)
                            ->whereHas('area',function($query) use ($area_id){
                                $query->where('area_id',$area_id);
                            })
                            ->get();
//        dd($area->each(function($ar){$ar->value ? var_dump($ar->value,$ar->id) : 0;}));
        if($area->count()){
            return $area->sum('value');
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function GetFormatedDate($time)
{
    $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
    $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
    $am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];



       $__lang = 'ar';


    if( $__lang == 'ar'){
    $day = $days[date('D', strtotime($time))];
    $month = $months[date('M', strtotime($time))];
    }else{
       $day = date('D', strtotime($time));
       $month = date('M', strtotime($time));
    }
    $date = $day . ' ' . date('d', strtotime($time)) . ' ' . $month . ' ' . date('Y', strtotime($time));
    $numbers_ar = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
    $numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    if( $__lang   == 'ar')
    return str_replace($numbers_en, $numbers_ar, $date);
  else
  return str_replace($numbers_ar, $numbers_en, $date);
}

function getSubAreas($area_id){
    $areas_data = \App\Models\Area::where('area_id',$area_id)->get();
    $areas = '<option value="0">-- اختر المنطقة المحلية --</option>';
    foreach ($areas_data as $key => $areas_datum){
        $areas .= '<option value="'.$areas_datum->id.'">'.$areas_datum->name.'</option>';
    }
    return $areas;
}
function getSubAreaPlaces($area_id){
    $places_data = \App\Models\Place::where('area_id',$area_id)->withoutGlobalScope('relatedPlaces')->get();
//    dd($places_data);
    $places = '<option value="0">-- اختر --</option>';
    foreach ($places_data as $key => $places_datum){
        $places .= '<option value="'.$places_datum->id.'">'.$places_datum->name.'</option>';
    }
    return $places;
}
function getPlaceTeachersForCircles($area_id,$teacher_id){
//    dd($area_id);
    if($area_id) {
        $teachers_data = \App\Models\User::fatherarea($area_id)->whereHas('user_roles', function ($query) {
            $query->where('name', 'محفظ');
        })->get();
        $teachers = '';
        foreach ($teachers_data as $key => $teachers_datum) {
            $selected = $teacher_id == $teachers_datum->id ? 'selected' : '';
            $teachers .= '<option value="' . $teachers_datum->id . '" '.$selected.'>' . $teachers_datum->name . '</option>';
        }
        return $teachers;
    }
}
function getPlaceAreaSupervisorForCircles($area_id,$teacher_id){
//    dd($area_id);
    if($area_id) {
        $teachers_data = \App\Models\User::areafatherarea($area_id)->department(6)->get();
        $teachers = '<option value="0">-- اختر --</option>';
//        dd($teachers_data);
        foreach ($teachers_data as $key => $teachers_datum) {
            $selected = $teacher_id == $teachers_datum->id ? 'selected' : '';
            $teachers .= '<option value="' . $teachers_datum->id . '" '.$selected.'>' . $teachers_datum->name . '</option>';
        }
        return $teachers;
    }
}
function getPlaceTeachersForCourses($area_id,$teacher_id){
    if($area_id) {
        $teachers_data = \App\Models\User::areascope($area_id)->whereHas('user_roles', function ($query) {
            $query->where('name', 'معلم');
        })->get();
//        dd($teachers_data);
        $teachers = '<option value="0">-- اختر --</option>';
        foreach ($teachers_data as $key => $teachers_datum) {
            $selected = $teacher_id == $teachers_datum->id ? 'selected' : '';
            $teachers .= '<option value="' . $teachers_datum->id . '" '.$selected.'>' . $teachers_datum->name . '</option>';
        }
        return $teachers;
    }
}



function getPlaceTeachersForAsaneed($area_id,$teacher_id){
    if($area_id) {
        $teachers_data = \App\Models\User::areascope($area_id)->whereHas('user_roles', function ($query) {
            $query->where('name', 'شيخ اسناد');
        })->get();
//        dd($teachers_data);
        $teachers = '<option value="0">-- اختر --</option>';
        foreach ($teachers_data as $key => $teachers_datum) {
            $selected = $teacher_id == $teachers_datum->id ? 'selected' : '';
            $teachers .= '<option value="' . $teachers_datum->id . '" '.$selected.'>' . $teachers_datum->name . '</option>';
        }
        return $teachers;
    }
}
function getAreaPlacesForCourseExam($area_id,$place_id){
//    dd($area_id);
    if($area_id) {
        $places_data = \App\Models\Place::where('area_id',$area_id)->get();
        $places = '';
        foreach ($places_data as $key => $places_datum) {
            $selected = $place_id == $places_datum->id ? 'selected' : '';
            $places .= '<option value="' . $places_datum->id . '" '.$selected.'>' . $places_datum->name . '</option>';
        }
        return $places;
    }
}

function markEstimation($mark)
{
    if (60 <= $mark && $mark < 70) {
        return '<span style="color:#b3b300">ضعيف</span>';
    } elseif (70 <= $mark && $mark < 75) {
        return '<span style="color:lawngreen">جيد</span>';
    } elseif (75 <= $mark && $mark < 80) {
        return '<span style="color:lightgreen">جيد مرتفع</span>';
    } elseif (80 <= $mark && $mark < 85) {
        return '<span style="color:forestgreen">جيد جدا</span>';
    } elseif (85 <= $mark && $mark < 90) {
        return '<span style="color:green">جيد جدا مرتفع</span>';
    } elseif (90 <= $mark && $mark <= 100) {
        return '<span style="color:darkgreen">ممتاز</span>';
    } else {
        return '<span style="color:red">لا يجاز</span>';
    }
}

function markEstimationText($mark)
{
    if (60 <= $mark && $mark < 70) {
        return 'ضعيف';
    } elseif (70 <= $mark && $mark < 75) {
        return 'جيد';
    } elseif (75 <= $mark && $mark < 80) {
        return 'جيد مرتفع';
    } elseif (80 <= $mark && $mark < 85) {
        return 'جيد جدا';
    } elseif (85 <= $mark && $mark < 90) {
        return 'جيد جدا مرتفع';
    } elseif (90 <= $mark && $mark <= 100) {
        return 'ممتاز';
    } else {
        return 'لا يجاز';
    }
}

function checkAreaTotalPercentage($area_id,$percent){
    $areas = \App\Models\Area::whereNull('area_id')->where('id','!=',$area_id)->get();
    $total = $percent;
    foreach ($areas as $area){
        $total += $area->percentage;
    }
    return ($total > 100) ? true : false;
}
function areaSupervisor($area_id){
    //withoutGlobalScope('relatedAreas')->
//    dd($area_id,\App\Models\Area::withoutGlobalScope('relatedAreas')->find($area_id));
    $area = \App\Models\Area::withoutGlobalScope('relatedAreas')->find($area_id);
    $area = $area ? $area->load('areaSupervisor','subAreaSupervisor') : $area;
//    dd($area,$area->sub_area_supervisor_name,$area->area_supervisor_name);
    return $area ? $area->area_supervisor_name : "";
}
function subAreaSupervisor($area_id){
//    dd($area_id,\App\Models\Area::withoutGlobalScope('relatedAreas')->find($area_id));
    $area = \App\Models\Area::withoutGlobalScope('relatedAreas')->find($area_id);
    $area = $area ? $area->load('areaSupervisor','subAreaSupervisor') : $area;
//    dd($area,$area->sub_area_supervisor_name,$area->area_supervisor_name);
    return $area ? $area->sub_area_supervisor_name : "";
}

function getGetDataFromIdentityNum($id_num){


    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://eservices.gedco.ps/solor/index.php/solar/solar/public_get_detaild_NAME',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_SSL_VERIFYHOST => 0,
    //     CURLOPT_SSL_VERIFYPEER => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS => 'id=' . $id_num,
    //     CURLOPT_HTTPHEADER => array(
    //         'Content-Type: application/x-www-form-urlencoded',
    //     ),
    // ));
    // $response = curl_exec($curl);
    // curl_close($curl);
    // $response = json_decode(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $response));



    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://eservices.mtit.gov.ps/ws/gov-services/ws/getData',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "WB_USER_NAME_IN": "DAR_QURAAN",
        "WB_USER_PASS_IN": "9ACA19A79194s6d5fe8r54fDB80FD18E9",
        "DATA_IN": {
            "package": "MOI_GENERAL_NEW_PKG",
            "procedure": "CITZN_MAIN_INFO_PR",
            "ID": '.$id_num.'
        },
        "WB_AUDIT_IN": {
            "ip": "10.12.0.32",
            "pc": "feras-iMac"
        }
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // $response = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $response);
    $response =  json_decode($response);
//    dd($response);
    return $response
        ? (isset($response->DATA)
            ? (isset($response->DATA[0])
                ? $response->DATA[0]
                : false )
            : false)
        : false;
}
function getUserBasicData($data){
//    dd($data);

// $user_data = [
//     "name"=>$data->FNAME_ARB . ' ' .
//         $data->SNAME_ARB . ' ' .
//         $data->TNAME_ARB . ' ' .
//         $data->LNAME_ARB,
//     "dob"=>$data->BIRTH_DT,
//     "role"=>$data->SEX_CD,
//     "pob"=>$data->BIRTH_PMAIN . ' ' . $data->BIRTH_PSUB ,
//     "material_status"=>$data->SOCIAL_STATUS,
//     "student_category"=>getStudentCategory($data->BIRTH_DT)
// ];

    $user_data = [
        "name"=>$data->CI_FIRST_ARB . ' ' .
            $data->CI_FATHER_ARB . ' ' .
            $data->CI_GRAND_FATHER_ARB . ' ' .
            $data->CI_FAMILY_ARB,
        "dob"=>$data->CI_BIRTH_DT,
        "role"=>$data->SEX,
        "pob"=>$data->CI_BIRTH_COUNTRY_AR,
        "material_status"=>$data->SOCIAL_STATUS,
        "student_category"=>getStudentCategory($data->CI_BIRTH_DT)
    ];
    return $user_data;
}
function getStudentAge($dob){
//    var_dump($dob);dd(\Carbon\Carbon::createFromFormat('d/m/Y', $dob));
    return \Carbon\Carbon::now()->diffInYears(\Carbon\Carbon::createFromFormat('d/m/Y', $dob));
}
function getStudentCategory($dob){
    $student_age = getStudentAge($dob) ;
//    dd($student_age);
    if($student_age>=7 && $student_age <= 12){
        return 'ابتدائية ( 7 - 12 )';
    }
    if($student_age >= 13 && $student_age <= 15){
        return 'اعدادية ( 13 - 15 )';
    }
    if($student_age >= 16){
        return 'ثانوية فما فوق ( 16 فما فوق )';
    }
}
