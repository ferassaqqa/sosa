<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBookCategories extends Model
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
                '<a href="#!" data-url="' . route('CourseBookCategories.getCatBooks',$this->id) . '" onclick="callApi(this,\'user_modal_content\')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">'.$this->books->count().'</a>'
                : 0,
            'tools' => '
                    <button type="button" class="btn btn-warning" data-url="' . route('CourseBookCategories.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" data-url="' . route('CourseBookCategories.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }
    public function books(){
        return $this->hasMany(Book::class,'category_id');
    }
}
