<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedBookCategory extends Model
{
    use HasFactory;
    public static $counter = 0;
    protected $fillable = ['name'];
    public function getCatDisplayDataAttribute(){
        self::$counter++;
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'book_count' => $this->books->count() ?
                '<a href="#!" data-url="' . route('asaneedBookCategories.getCatBooks',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->books->count().'</a>'
                : 0,
            'tools' => '
                    <button type="button" class="btn btn-warning" title="تعديل التصنيف" data-url="' . route('asaneedBookCategories.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" title="حذف التصنيف" data-url="' . route('asaneedBookCategories.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }
    public function books(){
        return $this->hasMany(AsaneedBook::class,'category_id','id');
    }

    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%");
    }
}
