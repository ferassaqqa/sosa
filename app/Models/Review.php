<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;


    protected $fillable = ['plan_score_38','test_quality_5','safwa_score_2','students_category_3','super_plus_2','created_at'];


}
