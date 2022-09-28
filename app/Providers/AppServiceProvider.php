<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Book;
use App\Models\Circle;
use App\Models\BookPlanHour;
use App\Models\BookPlanYear;
use App\Models\BookPlanYearSemester;
use App\Models\BookPlanYearSemesterMonth;
use App\Models\Course;
use App\Models\Place;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('SemesterMonthAndSemesterId', function ($attribute, $value, $parameters, $validator) {
            $month = BookPlanYearSemesterMonth::
                where('semester_month', $value)
                ->where('book_plan_year_semester_id', $parameters[0])
                ->first();
            if($month && $parameters[1]) {
                return $month->id === (int)$parameters[1];
            }elseif ($month){
                return false;
            }
            return true;
        });
        Validator::extend('FromHadithAndSemesterId', function ($attribute, $value, $parameters, $validator) {
            $month = BookPlanYearSemesterMonth::
                where('from_hadith', $value)
                ->where('book_plan_year_semester_id', $parameters[0])
                ->first();
            if($month && $parameters[1]) {
                return $month->id === (int)$parameters[1];
            }elseif ($month){
                return false;
            }
            return true;
        });
        Validator::extend('ToHadithAndSemesterId', function ($attribute, $value, $parameters, $validator) {
            $month = BookPlanYearSemesterMonth::
                where('to_hadith', $value)
                ->where('book_plan_year_semester_id', $parameters[0])
                ->first();
            if($month && $parameters[1]) {
                return $month->id === (int)$parameters[1];
            }elseif ($month){
                return false;
            }
            return true;
        });
        Validator::extend('SemesterNameAndYearId', function ($attribute, $value, $parameters, $validator) {
            $semester = BookPlanYearSemester::
                where('year_semester', $value)
                ->where('book_plan_year_id', $parameters[0])
                ->first();
            if($semester && $parameters[1]) {
                return $semester->id === (int)$parameters[1];
            }elseif ($semester){
                return false;
            }
            return true;
        });
        Validator::extend('YearNameAndPlanId', function ($attribute, $value, $parameters, $validator) {
            $year = BookPlanYear::
                where('plan_year', $value)
                ->where('book_plan_id', $parameters[0])
                ->first();
            if($year && $parameters[1]) {
                return $year->id === (int)$parameters[1];
            }elseif ($year){
                return false;
            }
            return true;
        });
        Validator::extend('HoursPlanFromAndPlanId', function ($attribute, $value, $parameters, $validator) {
            $hour = BookPlanHour::
                where('from', $value)
                ->where('book_plan_id', $parameters[0])
                ->first();
            if($hour && $parameters[1]) {
                return $hour->id === (int)$parameters[1];
            }elseif ($hour){
                return false;
            }
            return true;
        });
        Validator::extend('HoursPlanToAndPlanId', function ($attribute, $value, $parameters, $validator) {
            $hour = BookPlanHour::
                where('to', $value)
                ->where('book_plan_id', $parameters[0])
                ->first();
            if($hour && $parameters[1]) {
                return $hour->id === (int)$parameters[1];
            }elseif ($hour){
                return false;
            }
            return true;
        });
        Validator::extend('validateBookId', function ($attribute, $value, $parameters, $validator) {
            foreach ($value as $book_id => $yearlyCountArray){
                $book = Book::where('department',1)->where('id',$book_id)->first();
                if(!$book){
                    return false;
                }
            }
            return true;
        });
        Validator::extend('validateKey', function ($attribute, $value, $parameters, $validator) {
//            dd($value);
            foreach ($value as $area_id => $subAreaArray) {
//                dd($subAreaArray);
                foreach ($subAreaArray as $sub_area_id => $bookArray) {
                    $area = Area::find($sub_area_id);
                    if (!$area) {
                        return false;
                    }
//                    dd($sub_area_id);
                    foreach ($bookArray as $book_id => $bookVal) {
                        $book = Book::find($book_id);
                        if (!$book) {
                            return false;
                        }
                        if(!$bookVal){
                            return false;
                        }
                    }
                }
            }
            return true;
        },'يرجى ادخال كافة البيانات');
        Validator::extend('unique_name_area_id', function ($attribute, $value, $parameters, $validator) {
            $place = Place::
                where('name', $value)
                ->where('area_id', $parameters[0])
                ->first();
            if($place && $parameters[1]) {
                return $place->id === (int)$parameters[1];
            }elseif ($place){
                return false;
            }
            return true;
        });
        Validator::extend('is_id_valid', function ($attribute, $value, $parameters, $validator) {
            $id_num_validation = getGetDataFromIdentityNum($value);
            if($id_num_validation) {
                return true;
            }else{
                return false;
            }
        },'رقم الهوية خطأ');
        Validator::extend('not_teacher', function ($attribute, $value, $parameters, $validator) {
            $course = Course::find($parameters[0]);
            if ($course->teacher_id_num == $value) {
                return false;
            }else{
                return true;
            }
        }, ' لا يمكن اضافة المعلم  كطالب في دورته ');

        Validator::extend('not_teacher_circle', function ($attribute, $value, $parameters, $validator) {
            $circle = Circle::find($parameters[0]);
            if ($circle->teacher_id_num == $value) {
                return false;
            }else{
                return true;
            }
        }, ' لا يمكن اضافة المعلم  كطالب في دورته ');


        Validator::extend('can_exclude_student', function ($attribute, $value, $parameters, $validator) {
            $course = Course::find($parameters[0]);
            $user_validity_check = getGetDataFromIdentityNum($value);
            if($user_validity_check) {
                $user_interior_ministry_data = getUserBasicData($user_validity_check);
//            dd(!in_array($user_interior_ministry_data['student_category'],$course->student_categories));
                if (!in_array($user_interior_ministry_data['student_category'], $course->student_categories)) {
//                dd($user_interior_ministry_data);
                    if (hasPermissionHelper('استثناء طالب خارج الخطة')) {
//                    dd(1);
                        return true;
                    } else {
//                    dd(2);
                        return false;
                    }
                } else {
                    return true;
                }
            }else{
                return false;
            }
        },'الطالب خارج الفئة المستهدفة ، لا توجد صلاحيات استثناؤه');
    }
}
