<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExtraData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','email','address','home_tel','fb_link',
        'collage','speciality','occupation','occupation_place','monthly_income','join_date',
        'qualification','mobile','study_level','contract_type','contract_type_value','computer_skills','english_skills',
        'health_skills'
    ];
}
