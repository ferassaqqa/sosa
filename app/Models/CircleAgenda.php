<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleAgenda extends Model
{
    use HasFactory;
    protected $fillable = ['year','semester','months','exam_month'];

    public function getMonthsArrayAttribute(){
        return $this->months ? json_decode($this->months) : [];
    }
    public function getMonthsStringAttribute(){
//        dd(55);
        $monthsStr = '';
        foreach ($this->months_array as $key => $value){
            if(!$key){
                $monthsStr .= $value;
            }else{
                $monthsStr .= ' - '.$value;
            }
        }
        return $monthsStr;
    }
    public function setMonthsAttribute($value){
        $this->attributes['months'] = json_encode($value);
    }
}
