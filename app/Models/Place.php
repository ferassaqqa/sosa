<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Place extends Model
{
    use HasFactory;
    protected $fillable = ['name','area_id','address'];
    public function getPlaceDisplayDataAttribute(){
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'area_name'=>$this->area_name,
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.route('places.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.route('places.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    '
        ];
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function areaForPermissions(){
        return $this->belongsTo(Area::class,'area_id')->withoutGlobalScope('relatedAreas');
    }
    public function getAreaFatherIdForPermissionsAttribute(){
        return $this->areaForPermissions ? $this->areaForPermissions->area_id : '';
    }
    public function getAreaNameAttribute(){
        return $this->area ? $this->area->name : '';
    }
    public function getAreaFatherNameAttribute(){
        return $this->area ? $this->area->area_father_name : '';
    }
    public function setAreaIdAttribute($value){
        $area = Area::where('name',$value)->first();
        $this->attributes['area_id'] = $area ? $area->id : $value;
    }
    public function getAreaFatherIdAttribute(){
        return $this->area ? $this->area->area_father_id : '';
    }
    public function getPlaceFullNameAttribute(){
        return $this->name .' - '.$this->area_name .' - '.$this->area_father_name;
    }
    public function getPlaceSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-place" data-id="'.$this->id.'" data-name="'.$this->place_full_name.'">'.$this->place_full_name.'</a></li>';
    }
    public function getPlaceOptionAttribute(){
        return '<option value="'.$this->id.'">'.$this->place_full_name.'</a></option>';
    }
    public function getNoPlaceSearchedResultForCircleAttribute(){
        return '<li class="list-group-item"><a class="selected-place">لا يوجد بيانات</a></li>';
    }

    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
//        dd($searchWord);
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%")
            ->orWhereHas('area',function($query) use ($searchWord){
                $query->search($searchWord);
            });
    }
    public function scopeFatherArea($query,$area_id)
    {
        return $query->whereHas('area',function($query) use ($area_id){
                $query->whereHas('area',function($query) use ($area_id){
                    $query->where('id',$area_id);
                });
            });
    }
    public function scopeSubArea($query,$area_id)
    {
        return $query->whereHas('area',function($query) use ($area_id){
                    $query->where('id',$area_id);
                });
    }
    /**
     * permissions scopes
     */
    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
        if($area_id && !$sub_area_id){
//            dd($area_id);
            return $query->whereHas('area',function ($query) use($area_id){
                $query->where('area_id',$area_id);
            });
        }else if ($sub_area_id) {
            return $query->where('area_id', $sub_area_id);
        } else{
            return $query;
        }
    }
    /**
     * Scopes
     */
    public function courses(){
        return $this->hasMany(Course::class);
    }
    public function users(){

    }
    public static function boot(){
        parent::boot();

        if(!Auth::guest()) {
            $user = Auth::user();
//            dd(getUserAreaId($user));
            static::addGlobalScope('relatedPlaces',function (Builder $builder) use ($user) {
                if ($user) {
                    if ($user->hasRole('رئيس الدائرة')) {
                        return $builder;
                    } else if($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')){
                        return $builder;
                    }else if($user->hasRole('مشرف عام')){
                        return $builder->permissionssubarea(0,$user->area_father_id_for_permissions);
                    }else if($user->hasRole('مشرف ميداني')){
                        return $builder->permissionssubarea($user->area_id_for_permissions,$user->area_father_id_for_permissions);
                    }else if($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')){
                        return $builder;
                    }else{
                        return $builder;
                    }
                }else {
                    return false;
                }
            });
        }
    }
}
