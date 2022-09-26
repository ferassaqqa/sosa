<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProject extends Model
{
    use HasFactory;
    protected $fillable = ['name','date','books'];
    
    public static $counter = 0;
    public function getProjectDisplayDataAttribute(){
//        dd($this->books_array);
        self::$counter++;
        return [
            'id' => self::$counter,
            'name' => $this->name,
            'year' => $this->year,
            'books' => $this->books_string,
            'tools' => '
                    <button type="button" class="btn btn-warning" data-url="' . route('courseProjects.edit',$this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                    <button type="button" class="btn btn-danger" data-url="' . route('courseProjects.destroy',$this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                '
        ];
    }

    public function getBooksIdsArrayAttribute(){
        return $this->books ? json_decode($this->books) : [];
    }
    public function getBooksArrayAttribute(){
        $books_ids = $this->books ? json_decode($this->books) : [];
        $books = Book::select('id','name')->whereIn('id',$books_ids)->get();
        return $books->count() ? $books->pluck('name')->all() : [];
    }
    public function getBooksStringAttribute(){
//        dd(55);
        $asString = '';
        foreach ($this->books_array as $key => $value){
            if(!$key){
                $asString .= '<span style="color: #2ca02c;">'.$value.'</span><br>';
            }else{
                $asString .= '<span style="color: #2ca02c;">'.$value.'</span><br>';
            }
        }
        return $asString;
    }
    public function setBooksAttribute($value){
        if(!$value){
            $this->attributes['books'] = null;
        }else{
            $this->attributes['books'] = json_encode($value);
        }
    }
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('from', 'like', "%" . $searchWord . "%")
            ->orWhere('to', 'like', "%" . $searchWord . "%");
    }
}
