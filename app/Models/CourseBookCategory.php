<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CourseBookCategory extends Model
{
    use HasFactory,LogsActivity;
    public static $counter = 0;
    protected $fillable = ['name'];
    public function getCatDisplayDataAttribute(){
        self::$counter++;
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'book_count' => $this->books->count() ?
                '<a href="#!" data-url="' . route('CourseBookCategory.getCatBooks',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->books->count().'</a>'
                : 0,
            'tools' => '
                    <button type="button" class="btn btn-warning" title="تعديل التصنيف" data-url="' . route('CourseBookCategory.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" title="حذف التصنيف" data-url="' . route('CourseBookCategory.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }
    public function books(){
        return $this->hasMany(Book::class,'category_id');
    }

    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%");
    }



    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable){
        switch($fillable){
            case 'id': {return 'المعرف';}break;
            case 'name': {return 'اسم التصنيف';}break;
            default : {return 0;}
        }
    }
    public function getFillableRelationData($fillable){
        switch($fillable){
            case 'id': {return $this->id;}break;
            case 'name': {return $this->name;}break;
        }
    }
    protected static $logAttributes = ['id','name'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName){
            case 'created':{
                $description = ' قام '.$activity->causer->name.' باضافة التصنيف ' .$this->name. ' لكتب الدورات العلمية ';
            }break;
            case 'updated':{
                $description = ' قام '.$activity->causer->name.' بتعديل التصنيف '.$this->name;
            }break;
            case 'deleted':{
                $description = ' قام '.$activity->causer->name.' بحذف التصنيف '.$this->name;
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
}
