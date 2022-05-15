<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedCourseStudent extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','asaneed_course_id','mark'];
    public function getEstimationAttribute(){
        $mark = $this->mark;
        if(60<=$mark && $mark<70){ return '<span style="color:#b3b300">ضعيف</span>'; }
        elseif(70<=$mark && $mark<75){ return '<span style="color:lawngreen">جيد</span>'; }
        elseif(75<=$mark && $mark<80){ return '<span style="color:lightgreen">جيد مرتفع</span>'; }
        elseif(80<=$mark && $mark<85){ return '<span style="color:forestgreen">جيد جدا</span>'; }
        elseif(85<=$mark && $mark<90){ return '<span style="color:green">جيد جدا مرتفع</span>'; }
        elseif(90<=$mark && $mark<=100){ return '<span style="color:darkgreen">ممتاز</span>'; }
        else{
            return '<span style="color:red">لا يجاز</span>';
        }
//        switch ($mark){
//            case (60<=$mark && $mark<70) : { return '<span style="color:#b3b300">ضعيف</span>'; }break;
//            case (70<=$mark && $mark<75) : { return '<span style="color:lawngreen">جيد</span>'; }break;
//            case (75<=$mark && $mark<80) : { return '<span style="color:lightgreen">جيد مرتفع</span>'; }break;
//            case (80<=$mark && $mark<85) : { return '<span style="color:forestgreen">جيد جدا</span>'; }break;
//            case (85<=$mark && $mark<90) : { return '<span style="color:green">جيد جدا مرتفع</span>'; }break;
//            case (90<=$mark && $mark<=100) : { return '<span style="color:darkgreen">ممتاز</span>'; }break;
//            default : return '<span style="color:red">لا يجاز</span>';
//        }
    }
}
