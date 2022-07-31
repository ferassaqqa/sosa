<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedBookPlan extends Model
{
    use HasFactory;
    protected $fillable = ['year','area_id','book_id','value','percentage'];

    public function book(){
        return $this->belongsTo(AsaneedBook::class,'book_id');
    }
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function getAreaFatherIdAttribute(){
        return $this->area ? $this->area->area_father_id : '';
    }
    public function getBookNameAttribute(){
        return $this->book ? $this->book->name : '';
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('year', 'like', "%" . $searchWord . "%");
    }
    /**
     * Scopes
     */
}
