<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;
    protected $fillable = ['from','to'];

    public static $counter = 0;
    public function getCatDisplayDataAttribute(){
        self::$counter++;
        return [
            'id' => self::$counter,
            'from' => $this->from,
            'to' => $this->to,
            'tools' => '
                    <button type="button" class="btn btn-warning" data-url="' . route('bookCategory.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" data-url="' . route('bookCategory.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }

    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('from', 'like', "%" . $searchWord . "%")
            ->orWhere('to', 'like', "%" . $searchWord . "%");
    }
}
