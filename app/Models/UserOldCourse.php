<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOldCourse extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','course','course_teacher','year'];

}
