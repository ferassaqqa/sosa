<?php

namespace App\Models;

use App\Http\Controllers\controlPanel\CircleMonthlyReportsController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Circle extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['start_date','place_id','teacher_id','supervisor_id','notes','status'];
    public function getCircleDisplayDataAttribute(){
        return [
            'id'                    =>$this->id,
            'start_date'            =>$this->start_date,
            'place_name'            =>$this->place_name,
            'teacher_name'          =>$this->teacher_name,
            'area_father_name'      => $this->place ? $this->place->area_father_name : 0,
            'area_name'             => $this->place ? $this->place->area_name : 0,
            'id_num'                => $this->teacher ? $this->teacher->id_num : '',
            'contract_type'           => $this->teacher ? $this->teacher->userExtraData->contract_type : '',
            'supervisor_name'       =>subAreaSupervisor($this->area_id_for_permissions),
            'area_supervisor_name'  =>areaSupervisor($this->area_father_id_for_permissions),
            'StatusSelect'          =>$this->status_select,
            'tools'                 =>'
                        <div class="mb-1"><button type="button" class="btn btn-info" title="التقارير الشهرية" data-url="'.route('circleMonthlyReports.getCircleMonthlyReports',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-file-plus"></i></button>
                        <button type="button" class="btn btn-primary" title="طلاب الحلقة" data-url="'.route('circles.students',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-account-multiple"></i></button></div>
                        <div><button type="button" class="btn btn-warning" title="تعديل" data-url="'.route('circles.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2" onclick="callApi(this,\'user_modal_content_new\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger" title="حذف" data-url="'.route('circles.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button></div>
                    '
        ];
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
    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function getPlaceNameAttribute(){
        return $this->place ? $this->place->name : '';
    }
    public function getSubAreaNameAttribute(){
        return $this->place ? $this->place->area_name : '';
    }
    public function getAreaNameAttribute(){
        return $this->place ? $this->place->area_father_name : '';
    }
    public function getPlaceFullNameAttribute(){
        return $this->place ? $this->place->place_full_name : '';
    }
    public function getAreaFatherIdAttribute(){
        return $this->place ? $this->place->area_father_id : 0;
    }
    public function getAreaIdAttribute(){
        return $this->place ? $this->place->area_id : 0;
    }
    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id')->withoutGlobalScope('relatedUsers');
    }
    public function getStudentsAttribute(){
        return $this->teacher ? $this->teacher->students: [];
    }
    public function getTeacherNameAttribute(){
        return $this->teacher ? $this->teacher->name : '';
    }
    public function getTeacherMobileAttribute(){
        return $this->teacher ? $this->teacher->mobile : '';
    }
    public function supervisor(){
        return $this->belongsTo(User::class,'supervisor_id');
    }
    public function getSupervisorNameAttribute(){
        return $this->supervisor ? $this->supervisor->name : '';
    }
    public function reports(){
        return $this->hasMany(CircleMonthlyReport::class,'circle_id');
    }

    public function getReportsMustBeEnteredUpToNowAttribute(){

        $circleStartYear = Carbon::parse($this->start_date)->year;
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $agendas = CircleAgenda::where('year','>=',$circleStartYear)->where('year','<=',$currentYear)->get();
        $totalReports = 0;
        $totalExamReports = 0;

        foreach ($agendas as $key => $agenda){
            if($agenda->year == $currentYear && in_array($currentMonth,$agenda->months_array)){
                $totalReports += array_search($currentMonth,$agenda->months_array);
            }else {
                $totalReports += count($agenda->months_array);
                $totalExamReports++;
            }
        }
        return [$totalReports,$totalExamReports];

    }

    public function getStatusSelectAttribute(){
        $selected1 = '';
        $selected2 = '';
        $selected3 = '';
        $selected4 = '';
        switch ($this->status){
            case 'انتظار الموافقة' : {$selected1 = 'selected';}break;
            case 'قائمة' : {$selected2 = 'selected';}break;
//            case 'منتهية' : {$selected3 = 'selected';}break;
            case 'معلقة' : {$selected4 = 'selected';}break;
        }
        $select = '<select onchange="changeCircleStatus('.$this->id.',this.value)" class="form-control">
                        <option '.$selected1.' value="انتظار الموافقة">انتظار الموافقة</option>
                        <option '.$selected2.' value="قائمة">قائمة</option>
                        <!--<option '.$selected3.' value="منتهية">منتهية</option>-->
                        <option '.$selected4.' value="معلقة">معلقة</option>
                   </select>';
        return $select;
    }

    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'start_date': {return 'تارخ البداية';}break;
            case 'place_id': {return 'المكان';}break;
            case 'teacher_id': {return 'المعلم';}break;
            case 'supervisor_id': {return 'المشرف الميداني';}break;
            case 'notes': {return 'ملاحظات';}break;
            case 'status': {return 'الحالة';}break;
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id': {return $this->id;}break;
            case 'start_date': {return $this->start_date;}break;
            case 'place_id': {return $this->place_name;}break;
            case 'teacher_id': {return $this->teacher_name;}break;
            case 'supervisor_id': {return $this->supervisor_name;}break;
            case 'notes': {return $this->notes;}break;
            case 'status': {return $this->status;}break;
        }
    }
    protected static $logAttributes = ['id','start_date','place_id','teacher_id','supervisor_id','notes','status'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة حلقة للمحفظ '.$activity->subject->teacher_name;
            }break;
            case 'updated':{
                $description = ' قام '.$activity->causer->name.' بتعديل حلقة المحفظ '.$activity->subject->teacher_name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف حلقة المحفظ '.$activity->subject->teacher_name;
            }break;
        }
        $activity->description = $description;
        $activity->log_name = $log_name;
        $activity->save();
    }
    public function CreatedBy(){
        return $this->morphMany(Activity::class,'subject');
    }
    public function getCreatedAtUserAttribute(){
        return $this->CreatedBy ? $this->CreatedBy->causer : 0 ;
    }
    /**
     * End activities functions
     */

    /**
     * Scopes
     */

    public function scopeCircleStatus($query,$circle_status){
        if ($circle_status) {
            $query->where('status', $circle_status);
        }else{
            return $query;
        }
    }

    public function scopeTeacher($query,$teacher_id){
        if ($teacher_id) {
            return $query->whereHas('teacher', function ($query) use ($teacher_id) {
                    $query->where('id', $teacher_id);
            });

        }else{
            return $query;
        }
    }

    public function scopeContractType($query,$contract_type){
        if ($contract_type) {
            return $query->whereHas('teacher', function ($query) use ($contract_type) {
                $query->whereHas('UserExtraData', function ($query) use ($contract_type) {
                    $query->where('contract_type', $contract_type);
                });
            });

        }else{
            return $query;
        }
    }
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('start_date', 'like', "%" . $searchWord . "%")
            ->orWhere('notes', 'like', "%" . $searchWord . "%")
            ->orWhereHas('supervisor',function($query) use ($searchWord){
                $query->search($searchWord);
            })
            ->orWhereHas('teacher',function($query) use ($searchWord){
                $query->searchuser($searchWord);
            })
            ->orWhereHas('place',function($query) use ($searchWord){
                $query->search($searchWord);
            });
    }
    public function scopeSubArea($query,$sub_area_id,$area_id)
    {
        if(!$area_id){
            return $query;
        }else {
            if ($sub_area_id) {
                return $query->whereHas('place', function ($query) use ($sub_area_id) {
                    $query->where('area_id', $sub_area_id);
                });
            } else {
                return $query->whereHas('place', function ($query) use ($area_id) {
                    $query->whereHas('area', function ($query) use ($area_id) {
                        $query->where('area_id', $area_id);
                    });
                });
            }
        }
    }
    public function scopeGenderDepartment($query,$role)
    {
        return $query->whereHas('CreatedBy', function ($query) use ($role) {
            $query->whereHas('causer', function ($query1) use ($role){
                return $query1->where('role',$role);
            });
        })->orWhereHas('teacher', function ($query) use ($role) {
            return $query->where('role', $role);
        });
    }
    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
        if($area_id){
            return $query->whereHas('placeForPermissions', function ($query) use ($area_id) {
                $query->whereHas('areaForPermissions', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
                });
            });
        }else if ($sub_area_id) {
            return $query->whereHas('placeForPermissions', function ($query) use ($sub_area_id) {
                $query->where('area_id', $sub_area_id);
            });
        } else{
            return $query;
        }
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

    /**
     * Scopes
     */
    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();
//            dd($user);
            static::addGlobalScope('relatedCircles', function (Builder $builder) use ($user) {
                if ($user) {
                    if ($user->hasRole('رئيس الدائرة')){
                        return $builder;
                    } else if($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')){
                        $builder->genderdepartment($user->role);
                    }else if($user->hasRole('مشرف عام')){
//                        $builder->genderdepartment($user->role)->permissionssubarea(0,$user->area_father_id_for_permissions);
                        $builder->genderdepartment($user->role)->permissionssubarea(0,$user->area_supervisor_area_id);
                    }else if($user->hasRole('مشرف ميداني')){
                        $builder->genderdepartment($user->role)->permissionssubarea($user->sub_area_supervisor_area_id,0);
//                        $builder->genderdepartment($user->role)->permissionssubarea($user->area_id_for_permissions,0);
                    }else if($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')){
                        $builder->where('teacher_id',$user->id);
                    }else if($user->hasRole('مدير دائرة التخطيط والجودة')){
                        return $builder;
                    }else if($user->hasRole('رئيس قسم الاختبارات')){
                        return $builder;
                    }else{
                        $builder->whereHas('teacher',function ($query) use($user){
                            $query->whereHas('students',function($query1) use($user){
                                $query1->where('id',$user->id);
                            });
                        });
                    }
                }else {
                    return false;
                }
            });
        }
    }
}
