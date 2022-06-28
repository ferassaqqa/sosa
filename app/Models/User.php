<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,LogsActivity;
    public $department=0;
    public static $counter=0;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'username',
        'name',
        'password',
        'first_name',
        'role',
        'dob',
        'pob',
        'id_num',
        'avatar',
        'teacher_id',
        'device_token',
        'place_id','supervisor_area_id','prefix','material_status','sons_count','address','monthly_report_limit'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ['area_id_for_permissions'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function sendFCM($body){
        $SERVER_API_KEY = 'AAAAIPqz91M:APA91bGZ73RcVxwtrTlOxr5cxHh84DfX_FsCdWcaMcdaa2faUK7KgBF1IqQCJVeavdhyK-1gl12bjkCjRSCyntp8JPh-DU9_m09MlZCEuKlDcHuujVAwGyNIJL1sN3NoTidkFGpDILRl';

//        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
//        dd($firebaseToken,[$this->device_token]);
        $data = [
            "registration_ids" => [$this->device_token],
            "notification" => $body
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    //    dd($response);
    }
    public function updateFields(){
        return [
            'name',
            'password',
            'first_name',
            'role',
            'dob',
            'pob',
            'id_num',
            'avatar',
            'teacher_id',
            'place_id','prefix','material_status','sons_count','address'
        ];
    }
    public function setDobAttribute( $value ) {
        try {
            $this->attributes['dob'] = (Carbon::createFromFormat('d/m/Y', $value))->format('d-m-Y');
        }catch (InvalidFormatException $e){
            $this->attributes['dob'] = $value;
        }
    }
    public function setDepartmentValue($department){
        $this->department = $department;
    }
    public function getUserRolesStringAttribute(){
        $roles = $this->user_roles;
        $roles_string = '';
        foreach ($roles as $key => $role) {
            if(!$key) {
                $roles_string .= '<span style="color: #0bb197;">' . $role->name . '</span>';
            }else{
                $roles_string .= '<span style="color: #0bb197;"> - '.$role->name .'</span>';
            }
        }
        return $roles_string;
    }
    public function getTools($department){
        switch ($department){
//            case 0 : {}break;
            case 1 : {
                $edit_route = route('mohafez.edit',$this->id);
                $delete_route = route('mohafez.destroy',$this->id);
            }break;
            case 2 : {
                $edit_route = route('moallem.edit',$this->id);
                $delete_route = route('moallem.destroy',$this->id);
            }break;
            case 3 : {
                $edit_route = route('circleStudents.edit',$this->id);
                $delete_route = route('circleStudents.destroy',$this->id);
            }break;
            case 4 : {
                $edit_route = route('courseStudents.edit',$this->id);
                $delete_route = route('courseStudents.destroy',$this->id);
            }break;
            default : {
                $edit_route = route('users.edit',$this->id);
                $delete_route = route('users.destroy',$this->id);
            }
        }
        return [$edit_route,$delete_route];
    }
    public function getUserDisplayDataAttribute(){
        self::$counter++;
        if($this->department == 4){





    $allCoursesBtn = $this->studentCourses->count() && hasPermissionHelper('جميع الدورات')?'<button type="button" class="btn btn-info"  title="جميع الدورات" data-url="' . route('users.getCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"><i class="mdi mdi-account-details"></i></button>':'<button disabled type="button" class="btn btn-default"><i class="mdi mdi-account-details"></i></button>';
    // $passedStudentsCourseBtn = $this->passedStudentCourses->count() && hasPermissionHelper('الدورات المجاز فيها')?'<button type="button" class="btn btn-success" title="الدورات المجاز فيها" data-url="' . route('users.getPassedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"><i class="mdi mdi-account-details"></i></button>':'<button disabled type="button" class="btn btn-default"><i class="mdi mdi-account-details"></i></button>';
    // $failedStudentCourses = $this->failedStudentCourses->count() && hasPermissionHelper('الدورات الغير مجاز فيها')?'<button type="button" class="btn btn-danger" title="الدورات الغير مجاز فيها" data-url="' . route('users.getFailedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"><i class="mdi mdi-account-details"></i></button>':'<button disabled type="button" class="btn btn-default"><i class="mdi mdi-account-details"></i></button>';
    $tools = '<div class="container m-3">'.$allCoursesBtn  .'</div>';
            return [
                'id' => self::$counter,
                'name' => $this->name,
                'id_num' => $this->id_num,
                'teacher_name' => $this->name,
                'passedCourses' => $this->passedStudentCourses->count() && hasPermissionHelper('الدورات المجاز فيها')?
                    '<a href="#!" data-url="' . route('users.getPassedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->passedStudentCourses->count().'</a>'
                    : 0,
                'failedCourses' => $this->failedStudentCourses->count() && hasPermissionHelper('الدورات الغير مجاز فيها')?
                    '<a href="#!" data-url="' . route('users.getFailedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->failedStudentCourses->count().'</a>'
                    : 0,
                // 'courses' => $this->studentCourses->count() && hasPermissionHelper('جميع الدورات')?
                //     '<a href="#!" data-url="' . route('users.getCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->studentCourses->count().'</a>'
                //     : 0,

                'courses' => $this->studentCourses->count() && hasPermissionHelper('جميع الدورات')?$this->studentCourses->count(): 0,

                'area_father_name' => $this->area_father_name,
                'area_name' => $this->area_name,
                'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),
                'sub_area_supervisor'=>subAreaSupervisor($this->area_id_for_permissions),

                // 'supervisor' =>'الميداني: '.subAreaSupervisor($this->area_id_for_permissions).'<br>'
                // .'العام: '.areaSupervisor($this->area_father_id_for_permissions),

                'tools' => $tools

            ];
        }else {
            $tools = $this->getTools($this->department ? $this->department : 0);
            $edit_route = $tools[0];
            $delete_route = $tools[1];
//            self::$counter++;
            return [
                'id' => self::$counter,
                'name' => $this->name,
                'studentCount' => $this->circleStudents->count(),
                'area_father_name' => $this->area_father_name,
                'circleReports' => $this->circles->count() ? '<a href="#!" title="التقارير الشهرية" data-url="'.route('circleMonthlyReports.getTeacherMonthlyReports',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">'.$this->circleReports->count().'</a>': 'لا يوجد حلقات',
                'roles' => $this->user_roles_string,
                'supervisorArea'=>$this->supervisor_area_accessor,
                'tools' => '
                        <button type="button" class="btn btn-warning btn-sm" data-url="' . $edit_route . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="' . $delete_route . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
            ];
        }
    }
    public function getAsaneedStudentsDisplayDataAttribute(){
        self::$counter++;
        $allCoursesBtn = '';
        $total = '';
        if(hasPermissionHelper('عرض جميع الأسانيد للطالب')){
             $allCoursesBtn = $this->studentAsaneedCourses->count()?'<button type="button" class="btn btn-info"  title="جميع الدورات" data-url="' . route('asaneedCourseStudents.getCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"><i class="mdi mdi-account-details"></i></button>':'<button disabled type="button" class="btn btn-default"><i class="mdi mdi-account-details"></i></button>';
             $total = $this->studentAsaneedCourses->count();
        }

        $passedCourses = '';
        if(hasPermissionHelper('الأسانيد المجاز فيها')){
            $passedCourses = $this->passedAsaneedStudentCourses->count() ?
            '<a href="#!" data-url="' . route('asaneedCourseStudents.getPassedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->passedStudentCourses->count().'</a>'
            : 0;
        }
        $failedCourses = '';
        if(hasPermissionHelper('الأسانيد الغير مجاز فيها')){
            $failedCourses = $this->failedAsaneedStudentCourses->count() ?
            '<a href="#!" data-url="' . route('asaneedCourseStudents.getFailedCourses',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->failedStudentCourses->count().'</a>'
            : 0;
        }


        return [
            'id' => self::$counter,
            'name' => $this->name,
            'teacher_name' => $this->name,

            'id_num' => $this->id_num,
            'area_father_name' => $this->area_father_name,
            'area_name' => $this->area_name,
            'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),
            'sub_area_supervisor'=>subAreaSupervisor($this->area_id_for_permissions),

            'passedCourses' => $passedCourses,
            'failedCourses' => $failedCourses,
            'courses' => $total,
            'tools' => $allCoursesBtn
            // '
            //         <button type="button" class="btn btn-warning btn-sm" data-url="' . route('asaneedCourseStudents.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
            //         <button type="button" class="btn btn-danger btn-sm" data-url="' . route('asaneedCourseStudents.edit',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
            //     '
        ];
    }
    public function getStudentStoredBooksAttribute(){
        $books = CircleMonthlyReportStudent::with('book')->where('student_id',$this->id)->get()->groupBy(['student_id','book_id']);
//        dd($books);
        $select = '<select class="form-control" onchange="changeHadithCount(this)"><option>اختر الكتاب</option>';
        if($books->count()) {
            foreach ($books[$this->id] as $key => $book_reports) {
                $storedCount = 0;
//            dd($book_reports);
                foreach ($book_reports as $key1 => $book_report) {
                    $storedCount += abs($book_report->current_storage);
                }
                $select .= '<option value="' . $storedCount . '">' . CircleBooks::find($key)->name . '</option>';

            }
        }
        return $select.'</select>';
//        dd($select.'</select>');
    }
    public function getCircleStudentDisplayDataAttribute(){
        self::$counter++;
//        dd($this,$this->circleStudentTeacher);
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'id_num' => $this->id_num,
            'contract_type' => $this->circleStudentTeacher ? $this->circleStudentTeacher->userExtraData->contract_type : '',
            'teacher_name' => $this->teacher_name,
            // 'books'=>$this->student_stored_books,
            'area_father_name' => $this->area_father_name,
            'area_name' => $this->area_name,

            'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),


            // 'hadith_count'=>0,
            'tools' => '
                    <button type="button" class="btn btn-warning" title="تعديل بيانات الطالب" data-url="' . route('circleStudents.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" title="حذف بيانات الطالب" data-url="' . route('circleStudents.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }

    public function scopeAgedBetween($query, $start, $end = null)
    {
        // if (is_null($end)) {
        //     $end = $start;
        // }

        // $now = $this->freshTimestamp();
        // $start = $now->subYears($start);
        // $end = $now->subYears($end)->addYear()->subDay(); // plus 1 year minus a day

         $from = Carbon::now()->subYears(16)->format('Y-m-d');
        $to = Carbon::now()->subYears(25)->format('Y-m-d');

        return $query->whereBetween('dob', $start, $end);
    }

    public function scopeContractType($query,$contract_type){
        if ($contract_type) {
            return $query->whereHas('UserExtraData', function ($query) use ($contract_type) {
                    $query->where('contract_type', $contract_type);
            });

        }else{
            return $query;
        }
    }

    public function scopeIdNum($query,$id_num){
        if ($id_num) {
            return $query->whereHas('UserExtraData', function ($query) use ($id_num) {
                    $query->where('id_num', $id_num);
            });

        }else{
            return $query;
        }
    }

    public function getMohafezDisplayDataAttribute(){
        self::$counter++;
        $letEnterLateReports = $this->has_late_reports ? '<button type="button" class="btn btn-primary" onclick="letEnterLateReports('.$this->id.')"><i class="mdi mdi-plus-circle-multiple-outline"></i></button>' : '';
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'id_num' => $this->id_num,
            'mobile' => $this->userExtraData->mobile,
            'contract_type' => $this->userExtraData->contract_type,
            'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),
            'sub_area_supervisor'=>subAreaSupervisor($this->area_id_for_permissions),
            'area_name'=>$this->area_name,


            'studentCount' => $this->circleStudents->count(),
            'area_father_name' => $this->area_father_name,
            'circleReports' => $this->circles->count() ? '<a href="#!" title="التقارير الشهرية" data-url="'.route('circleMonthlyReports.getTeacherMonthlyReports',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')">'.$this->circleReports->count().'</a>': 'لا يوجد حلقات',
            'tools' => $letEnterLateReports.'
                    <button type="button" class="btn btn-warning" data-url="' . route('mohafez.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" data-url="' . route('mohafez.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }
    public function getCircleStudentCurrentCircleAttribute(){
        return $this->circleStudentTeacher ? $this->circleStudentTeacher->current_circle : 0;
    }
    public function getCurrentCircleAttribute(){
        return $this->circles->count() ? $this->circles->where('status','قائمة')->first() : 0;
    }
    public function getCurrentCircleReportsMustBeEnteredUpToNowCountAttribute(){
        return $this->current_circle ? $this->current_circle->reports_must_be_entered_up_to_now : 0;
    }
    public function getCurrentCircleStartDateAttribute(){
        return $this->current_circle ? $this->current_circle->start_date : 0;
    }
    public function getCurrentCircleIdAttribute(){
        return $this->current_circle ? $this->current_circle->id : 0;
    }
    public function circles(){
        return $this->hasMany(Circle::class,'teacher_id');
    }
    public function circlesForPermissions(){
        return $this->hasMany(Circle::class,'teacher_id')->withoutGlobalScope('relatedCircles');
    }
    public function circleStudents(){
        return $this->hasMany(User::class,'teacher_id');
    }
    public function circleReports(){
        return $this->hasManyThrough(CircleMonthlyReport::class,Circle::class,'teacher_id','circle_id');
    }
    public function getAsaneedMoallemDisplayDataAttribute(){
        self::$counter++;
//        dd($this->area_father_id);
        $moallemPermission = hasPermissionHelper('إضافة صلاحيات لشيوخ الإسناد') ?
            '<button type="button" class="btn btn-primary" title="صلاحيات المستخدم" onclick="updateRoles(this,'.$this->id.')"><i class="mdi mdi-rotate-left"></i></button>' : '';
        $moallemUpdate = hasPermissionHelper('تعديل بيانات شيوخ الاسانيد') ?
            '<button type="button" class="btn btn-warning" title="تعديل بيانات المعلم" data-url="' . route('asaneedMoallem.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button><br>' : '';
        $moallemDelete = hasPermissionHelper('حذف بيانات شيوخ الاسانيد') ?
            '<button type="button" class="btn btn-danger" title="حذف بيانات شيوخ الاسانيد" data-url="' . route('asaneedMoallem.destroy',$this->id) . '" onclick="deleteItem(this)" style="margin-top: 4px;"><i class="mdi mdi-trash-can"></i></button>' : '';
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'id_num' => $this->id_num,
            'mobile' => $this->userExtraData->mobile,

            'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),
            'sub_area_supervisor'=>subAreaSupervisor($this->area_id_for_permissions),
            'area_father_name'=>$this->area_father_name,
            'area_name'=>$this->area_name,
            'CoursesCount'=>$this->teacherAsaneedCourses->count(),
            'runningCoursesCount'=>$this->teacherAsaneedCourses->count() ? $this->teacherAsaneedCourses->where('status','قائمة')->count() : 0,
            'tools' => $moallemPermission.$moallemUpdate.$moallemDelete
        ];
    }
    public function getMoallemDisplayDataAttribute(){
        self::$counter++;
        $moallemPermission = hasPermissionHelper('نسخ بيانات المعلم لمحفظ او شيخ أسانيد') ?
            '<button type="button" class="btn btn-primary" title="صلاحيات المستخدم" onclick="updateRoles(this,'.$this->id.')"><i class="mdi mdi-rotate-left"></i></button>' : '';
        $moallemUpdate = hasPermissionHelper('تعديل بيانات معلمو الدورات') ?
            '<button type="button" class="btn btn-warning" title="تعديل بيانات المعلم" data-url="' . route('moallem.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button><br>' : '';
        $moallemDelete = hasPermissionHelper('حذف بيانات معلمو الدورات') ?
            '<button type="button" class="btn btn-danger" title="حذف بيانات المعلم" data-url="' . route('moallem.destroy',$this->id) . '" onclick="deleteItem(this)" style="margin-top: 4px;"><i class="mdi mdi-trash-can"></i></button>' : '';
//       dd($this->area_father_name,$this->area_name);
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'id_num' => $this->id_num,
            'area_supervisor'=>areaSupervisor($this->area_father_id_for_permissions),
            'sub_area_supervisor'=>subAreaSupervisor($this->area_id_for_permissions),
            'area_father_name'=>$this->area_father_name,
            'area_name'=>$this->area_name,
            'CoursesCount'=>$this->teacherCourses->count(),
            'runningCoursesCount'=>$this->teacherCourses->count() ? $this->teacherCourses->where('status','قائمة')->count() : 0,
            'tools' => $moallemPermission.'&nbsp'.$moallemUpdate.$moallemDelete
        ];
    }
    public function getTeachersRolesSelectAttribute(){
        $currentRoles = $this->getRoleNames()->toArray();
        $roles = ['محفظ','معلم','شيخ اسناد'];
        $rolesSelect = '<div style="padding-right:52px;">'.
            ' المستخدم ' . $this->name . ' موجود مسبقا بصلاحيات ' . $this->user_roles_string;
        foreach ($roles as $key => $role){
            $selected = in_array($role,$currentRoles) ? 'checked' : '';
            $rolesSelect .= '<div class="form-check mb-2"><input class="form-check-input" type="checkbox" '.$selected.' name="userSelectRoles[]" value="'.$role.'" id="userSelectRoles_'.$key.'"><label for="userSelectRoles_'.$key.'">'.$role.'</label></div>';//'<option value="'.$role.'" '.$selected.'>'.$role.'</option>';
        }
        return $rolesSelect.'</div>';
    }
    public function getSearchedUserDisplayDataAttribute(){
        $tools = $this->getTools($this->department);
        $edit_route = $tools[0];
        $delete_route = $tools[1];
        return [
            'id'=>$this->id,
            'name'=>'<span style="background-color: #2ca02c;color: #fff;">'.$this->name.'</span>',
            'supervisorArea'=>$this->supervisor_area_accessor,
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.$edit_route.'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.$delete_route.'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
        ];
    }
    public function getGetDataFromIdentityNumAttribute(){
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
        //     CURLOPT_POSTFIELDS => 'id=' . $this->id_num,
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/x-www-form-urlencoded',
        //     ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        // $response = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $response);
        // return json_decode($response);



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
                "ID": '.$this->id_num.'
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
        return json_decode($response);


    }
    public function getUserBasicDataAttribute(){


        // dd($this->get_data_from_identity_num->DATA[0]);
//        return $this->get_data_from_identity_num;
        if(!empty($this->get_data_from_identity_num->DATA)) {
            $data = $this->get_data_from_identity_num->DATA[0];
            $this->name = $data->CI_FIRST_ARB . ' ' .
                $data->CI_FATHER_ARB . ' ' .
                $data->CI_GRAND_FATHER_ARB . ' ' .
                $data->CI_FAMILY_ARB;

            $this->dob = $data->CI_BIRTH_DT;

            $this->role = $data->SEX;

            $this->pob = $data->CI_BIRTH_COUNTRY_AR;

            $this->material_status = $data->SOCIAL_STATUS;
            return true;
        }else{
            return false;
        }
    }
    public function userExtraData(){
        return $this->hasOne(UserExtraData::class);
    }
    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function placeForPermissions(){
        return $this->belongsTo(Place::class,'place_id','id')->withoutGlobalScope('relatedPlaces');
    }
    public function getAreaIdForPermissionsAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->area_id : 0;
    }
    public function getAreaFatherIdForPermissionsAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->area_father_id_for_permissions : 0;
    }
    public function area(){
        return $this->belongsTo(Area::class,'supervisor_area_id');
    }
    public function getAreaIdAttribute(){
        return $this->place ? $this->place->area_id : 0;
    }
    public function getAreaFatherIdAttribute(){
        return $this->place ? $this->place->area_father_id : ($this->area ? $this->area->area_father_id : 0);
    }
    public function getAreaNameAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->area_name : 0;
    }
    public function getAreaFatherNameAttribute(){
//        return $this->place_name;
        return $this->placeForPermissions ? $this->placeForPermissions->area_father_name : ($this->placeForPermissions ? $this->area->area_father_name : '');
    }
    public function getPlaceNameAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->name : '';
    }
    public function getPlaceFullNameAttribute(){
        return $this->placeForPermissions ? $this->placeForPermissions->place_full_name : '';
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%")
            ->orWhere('id_num', 'like', "%" . $searchWord . "%")
            ->orWhereHas('supervisor_sub_area',function($query) use ($searchWord){
//                dd($searchWord);
                $query->search($searchWord);
            })
            ->orWhereHas('supervisor_area',function($query) use ($searchWord){
                $query->search($searchWord);
            });
    }
    public function scopeUsers($query)
    {
        return $query->whereHas('user_roles',function($query){
                $query->whereIn('name',['مدير الدائرة','مساعد اداري','مشرف عام','مشرف ميداني','مشرف جودة','مدير دائرة التخطيط والجودة','رئيس قسم الاختبارات']);
            });
    }
    public function scopeCourse($query,$status)
    {
        if($status) {
            return $query->whereHas('teacherCourses',function($query) use ($status){
                $query->where('status',$status);
            });
        }else{
            return $query;
        }
    }
    public function scopeUserRole($query,$role)
    {
        return $query->whereHas('user_roles',function($query) use ($role){
                $query->where('name',$role);
            });
    }
    public function scopeSearchUser($query,$searchWord)
    {
//        dd($searchWord);
        return $query->where('id', 'like', "%" . $searchWord . "%")
                ->orWhere('name', 'like', "%" . $searchWord . "%");
    }
    public function scopeFatherArea($query,$area_id)
    {
//        dd($searchWord);
        return $query->WhereHas('place',function($query) use ($area_id){
                $query->whereHas('area',function($query) use($area_id){
                    $query->whereHas('area',function($query) use($area_id){
                        $query->where('id',$area_id);
                    });
                });
            });
    }
    public function scopeAreaScope($query,$area_id)
    {
    //    dd($area_id);
        return $query->WhereHas('placeForPermissions',function($query) use ($area_id){
                $query->where('area_id',$area_id);
            });
    }
    public function scopeAreaFatherArea($query,$area_id)
    {
//        dd($searchWord);
        return $query->whereHas('area',function($query) use($area_id){
                    $query->whereHas('area',function($query) use($area_id){
                        $query->where('id',$area_id);
                    });
                });
    }
    public function scopeDepartment($query,$department)
    {
        switch ($department){
            case 0 : {return $query;}break;
            case 1 : {return $query->role('محفظ');}break;
            case 2 : {return $query->role('معلم');}break;
            case 3 : {return $query->role('طالب تحفيظ');}break;
            case 4 : {return $query->role('طالب دورات علمية');}break;
            case 5 : {return $query->role('مشرف جودة');}break;
            case 6 : {return $query->role('مشرف ميداني');}break;
            case 7 : {return $query->role('شيخ اسناد');}break;
            case 8 : {return $query->role('طالب دورات أسانيد وإجازات');}break;
        }
    }

    public function scopeArea($query,$area_id){
        if($area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        }else{
            return $query;
        }
    }

    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
//        dd($sub_area_id,$area_id);
        if ($sub_area_id) {
            return $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                $query->where('area_id', $sub_area_id);
            });
        } else
        if($area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        }else{
            return $query;
        }
    }
    public function scopeCourseBookOrTeacher($query,$teacher_id,$book_id,$place_id)
    {
//        return $query;

//        dd($teacher_id,$book_id,$place_id);
        if(!$teacher_id && !$book_id && !$place_id){
            return $query;
        }else {
            if ($teacher_id && !$book_id && !$place_id) {
//                dd(Course::where('teacher_id',$teacher_id)->count());
                return $query->whereIn('users.id',function ($query)use($teacher_id){
                    $query->from('course_students')
                        ->select('course_students.user_id')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id){
                            $query->from('courses')
                            ->select('courses.id')
                            ->where('courses.teacher_id', $teacher_id);
                        });
                });
//                return $query->whereHas('student_courses', function ($query) use ($teacher_id) {
////                    $query->whereHas('course', function ($query) use ($teacher_id) {
////                        $query->where('teacher_id', $teacher_id);
////                    });
//                });
            } else if ($teacher_id && $book_id && !$place_id){
                return $query->whereIn('users.id',function ($query)use($teacher_id,$book_id){
                    $query->from('course_students')
                        ->select('course_students.user_id')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id,$book_id){
                            $query->from('courses')
                                ->select('courses.id')
                                ->where('courses.teacher_id', $teacher_id)
                                ->where('courses.book_id', $book_id);
                        });
                });
//                return $query->whereHas('studentCoursesPivotTable', function ($query) use ($teacher_id,$book_id) {
//                    $query->whereHas('course', function ($query) use ($teacher_id,$book_id) {
//                        $query->where('teacher_id', $teacher_id)->where('book_id', $book_id);
//                    });
//                });
            } else if ($teacher_id && $book_id && $place_id){

                return $query->whereIn('users.id',function ($query)use($teacher_id,$book_id,$place_id){
                    $query->from('course_students')
                        ->select('course_students.user_id')
                        ->whereIn('course_students.course_id', function ($query)use($teacher_id,$book_id,$place_id){
                            $query->from('courses')
                                ->select('courses.id')
                                ->where('courses.teacher_id', $teacher_id)
                                ->where('courses.place_id', $place_id)
                                ->where('courses.book_id', $book_id);
                        });
                });
//                return $query->whereHas('studentCoursesPivotTable', function ($query) use ($teacher_id,$book_id,$place_id) {
//                    $query->whereHas('course', function ($query) use ($teacher_id,$book_id,$place_id) {
//                        $query->where('teacher_id', $teacher_id)->where('book_id', $book_id)->where('place_id', $place_id);
//                    });
//                });
            }
        }
    }
    public function scopeAsaneedCourseBookOrTeacher($query,$teacher_id,$book_id)
    {
//        dd($teacher_id ,$book_id);
        if(!$teacher_id && !$book_id){
            return $query;
        }else {
            if ($teacher_id && !$book_id) {
                return $query->whereHas('asaneedCourses', function ($query) use ($teacher_id) {
                    $query->where('teacher_id', $teacher_id);
                });
            }else {
                return $query->whereHas('asaneedCourses', function ($query) use ($teacher_id,$book_id) {
                    $query->where('teacher_id', $teacher_id)->where('book_id', $book_id);
                });
            }
        }
    }

    /**
     * permissions scopes
     */
    public function scopeGenderDepartment($query,$role){
        return $query->where('role', $role);
    }
    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
        if($area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                        $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                            $query->where('area_id', $area_id);
                        });
                    })
//                    ->orWhereHas('coursesForPermissions',function($query) use ($area_id){
//                        $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
//                            $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
//                                $query->where('area_id', $area_id);
//                            });
//                        });
//                    })
                    ->orWhereHas('circlesForPermissions',function($query) use ($area_id){
                        $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                            $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                                $query->where('area_id', $area_id);
                            });
                        });
                    });
        }else if ($sub_area_id) {
            return $query
                    ->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                        $query->where('area_id', $sub_area_id);
                    })
                    ->orWhereHas('circlesForPermissions',function($query) use ($sub_area_id){
                        $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                            $query->where('area_id', $sub_area_id);
                        });
                    });
//                    ->orWhereHas('coursesForPermissions',function($query) use ($sub_area_id){
//                        $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
//                            $query->where('area_id', $sub_area_id);
//                        });
//                    });
        } else{
            return $query;
        }
    }
    /**
     * End Scopes
     */
    public function user_roles()
    {
        $model_has_roles = config('permission.table_names.model_has_roles');

        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            $model_has_roles,
            config('permission.column_names.model_morph_key'),
            config('permission.column_names.role_pivot_key') ?: 'role_id'
        );
    }
    public function supervisor_area(){
        return $this->hasOne(Area::class,'area_supervisor_id')->withoutGlobalScope('relatedAreas');
    }
    public function supervisor_sub_area(){
        return $this->hasOne(Area::class,'sub_area_supervisor_id')->withoutGlobalScope('relatedAreas');
    }
    public function getSupervisorAreaAccessorAttribute(){
        return $this->supervisor_area ? $this->supervisor_area->name :
            ($this->supervisor_sub_area ? $this->supervisor_sub_area->name : '');
    }
    public function getSubAreaSupervisorAreaIdAttribute(){
        return $this->supervisor_sub_area ? $this->supervisor_sub_area->id : 0;
    }
    public function getAreaSupervisorAreaIdAttribute(){
        return $this->supervisor_area ? $this->supervisor_area->id : 0;
    }
    public function getUserSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-user" data-id="'.$this->id.'" data-name="'.$this->name.'">'.$this->name.'</a></li>';
    }
    public function getTeacherSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-teacher" data-id="'.$this->id.'" data-name="'.$this->name.'">'.$this->name.'</a></li>';
    }
    public function getSupervisorSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-supervisor" data-id="'.$this->id.'" data-name="'.$this->name.'">'.$this->name.'</a></li>';
    }

    public function getNoUserSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-place">لا يوجد بيانات</a></li>';
    }
    public function circleStudentTeacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }
    public function getTeacherNameAttribute(){
        return $this->circleStudentTeacher ? $this->circleStudentTeacher->name : '';
    }
    public function media(){
        return $this->morphMany(Media::class,'mediable');
    }
    public function notes(){
        return $this->hasMany(UserNote::class);
    }
    public function OldCourses(){
        return $this->hasMany(UserOldCourse::class);
    }
    public function getLogoAttribute(){
        return ($this->avatar && $this->avatar !='logo.png') ? asset('storage/'.$this->avatar) : asset('logo.png') ;
    }
    public function asaneedCourses(){
        return $this->belongsToMany(AsaneedCourse::class,AsaneedCourseStudent::class);
    }
    public function teacherAsaneedCourses(){
        return $this->hasMany(AsaneedCourse::class,'teacher_id');
    }
    public function studentAsaneedCourses(){
        return $this->belongsToMany(AsaneedCourse::class,AsaneedCourseStudent::class)->withPivot('id','user_id','asaneed_course_id','mark');
    }
    public function passedAsaneedStudentCourses(){
        return $this->belongsToMany(AsaneedCourse::class,AsaneedCourseStudent::class)->wherePivot('mark','>=','60')->withPivot('id','user_id','asaneed_course_id','mark');
    }
    public function failedAsaneedStudentCourses(){
        return $this->belongsToMany(AsaneedCourse::class,AsaneedCourseStudent::class)->wherePivot('mark','<','60')->withPivot('id','user_id','asaneed_course_id','mark');
    }
    public function courses(){
        return $this->belongsToMany(Course::class,CourseStudent::class);
    }
    public function coursesForPermissions(){
        return $this->belongsToMany(Course::class,CourseStudent::class)->withoutGlobalScope('relatedCourses');
    }
    public function teacherCourses(){
        return $this->hasMany(Course::class,'teacher_id');
    }
    public function studentCourses(){
        return $this->belongsToMany(Course::class,CourseStudent::class)->withPivot('id','user_id','course_id','mark');
    }
    public function studentCoursesPivotTable(){
        return $this->hasMany(CourseStudent::class)->withoutGlobalScope('relatedCourseStudents');
    }
    public function student_courses(){
        return $this->hasMany(CourseStudent::class,'user_id','id');
    }
    public function passedStudentCourses(){
        return $this->hasMany(CourseStudent::class)
            ->whereHas('course',function($query){
                $query->whereHas('exam',function($query){
                    $query->where('status',5);
                });
            })
            // ->where('mark','>=','60');//->withoutGlobalScope('relatedCourseStudents');
            ->whereBetween('mark', [60, 101]);
    }
    public function failedStudentCourses(){
        return $this->hasMany(CourseStudent::class)
            ->whereHas('course',function($query){
                $query->whereHas('exam',function($query){
                    $query->where('status',5);
                });
            })
            // ->where('mark','<','60');

            ->whereBetween('mark', [1, 59]);
    }
    public function getStudentAgeAttribute(){
        return (int)Carbon::now()->format('Y') - (int)Carbon::parse($this->dob)->format('Y');
    }
    public function getStudentCategoryAttribute(){
//        dd($this->student_age);
        if($this->student_age >=7 && $this->student_age <= 12){
            return 'ابتدائية ( 7 - 12 )';
        }
        if($this->student_age >= 13 && $this->student_age <= 15){
            return 'اعدادية ( 13 - 15 )';
        }
//        if($this->student_age >= 16 && $this->student_age < 19){
//            return 'ثانوية';
//        }
        if($this->student_age >= 16){
            return 'ثانوية فما فوق ( 16 فما فوق )';
        }
    }
    public function getMobileAttribute(){
        return $this->userExtraData ? $this->userExtraData->mobile : '';
    }
    public function deleteCourseStudent($course_id){
        return '<button type="button" class="btn btn-danger btn-sm" data-url="'.route('courseStudents.destroy',['user'=>$this->id,'course'=>$course_id]).'" onclick="deleteCourseStudent(this)"><i class="mdi mdi-trash-can"></i></button>';
    }
    public function deleteAsaneedCourseStudent($user_id,$course_id){
        return '<button type="button" class="btn btn-danger btn-sm" data-url="'.route('asaneedCourseStudents.destroy',['user'=>$user_id,'asaneedCourse'=>$course_id]).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>';
    }
    public function students(){
        return $this->hasMany(User::class,'teacher_id');
    }
    public function getHasLateReportsAttribute(){
        // get diff between latest report and now , if > 0 so there are late reports
        // if no latest reports , get diff between circle start date and now , if > 0 there are late reports
        if($this->current_circle) {
            $now = Carbon::now()->addMonth();
            $latestReport = CircleMonthlyReport::where('circle_id', $this->current_circle_id)->orderBy('date', 'DESC')->first();
//        $timeDifference = 0;
            if ($latestReport) {
                $timeDifference = $now->diffInMonths(Carbon::parse($latestReport->date));
            } else {
                $timeDifference = $now->diffInMonths(Carbon::parse($this->current_circle_start_date));
            }
//            if ($this->current_circle) {
//                dd($timeDifference);
//            }
            return $timeDifference ? true : false;
        }else{
            return false;
        }
    }

    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'name': {return 'الاسم رباعياّ';}break;
            case 'id_num': {return 'رقم الهوية';}break;
            case 'place_id': {return 'المنطقة';}break;
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id'       : {return $this->id;}break;
            case 'name'     : {return $this->name;}break;
            case 'id_num'   : {return $this->id_num;}break;
            case 'place_id' : {return $this->place_full_name;}break;
        }
    }
    protected static $logAttributes = ['id','username',
        'name',
        'password',
        'first_name',
        'role',
        'dob',
        'pob',
        'id_num',
        'avatar',
        'teacher_id',
        'place_id','supervisor_area_id','prefix','material_status','sons_count','address','monthly_report_limit'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة المستخدم '.$this->name .' بصلاحيات ' .$this->user_roles_string;
            }break;
            case 'updated':{
                $description = ' قام '.$activity->causer->name.' بتعديل بيانات المستخدم '.$this->name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف بيانات المستخدم '.$this->name;
            }break;
        }
        $activity->description = $description;
        $activity->log_name = $log_name;
        $activity->save();
    }

    /**
     * End activities functions
     */

    public function getShowUserDataAttribute(){
        $row = '';
        $dataKeys = ['name','id_num','place_id'];
        foreach ($dataKeys as $key){
//            dd($key,$this->getFillableRelationData($key));
            if($key!='id') {
                $row .= '<td class="col-md-2" style="background-color: #F3F3F4;">'.$this->getFillableArabic($key).'</td><td class="col-md-2">'.$this->getFillableRelationData($key).'</td>';
            }
        }
//        dd($row);
        return $row;
    }















    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();
//            if($_SERVER['REMOTE_ADDR'] == "82.205.28.65"){
//                dd($user);
//            }
            static::addGlobalScope('relatedUsers', function (Builder $builder) use ($user) {
                if ($user) {
                    if ($user->hasRole('رئيس الدائرة')) {
                        return $builder;
                    } elseif ($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')) {
                        // $builder->genderdepartment($user->role);
                        return $builder;

                    } else {
//                        $builder->whereHas('user_roles',function($query){
//                            $query->where('name','!=','مشرف جودة');
//                        });
                         if ($user->hasRole('مشرف عام')) {
                            //  $builder->genderdepartment($user->role)->permissionssubarea(0, $user->area_supervisor_area_id);
                       return $builder->permissionssubarea(0, $user->area_supervisor_area_id);

                        // return $builder;


                        } else if ($user->hasRole('مشرف ميداني')) {
                            // $builder->genderdepartment($user->role)->permissionssubarea($user->sub_area_supervisor_area_id, 0);
                      return  $builder->permissionssubarea($user->sub_area_supervisor_area_id, 0);

                        // return $builder;

                        } else if ($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')) {
                            $builder->where('teacher_id', $user->id)->orWhere('id', $user->id);
                        } else if ($user->hasRole('مدير دائرة التخطيط والجودة')) {
                            return $builder;
                        } else if ($user->hasRole('رئيس قسم الاختبارات')) {
                            return $builder;
                        } else {
                            $builder->where('id', $user->id);
                        }
                    }
                } else {

                }
            });
        }
    }
}
