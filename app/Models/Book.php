<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Book extends Model
{
    use HasFactory, LogsActivity;

    public static $counter = 0;


    protected $fillable = ['name', 'author', 'author_prefix', 'pass_mark', 'hadith_count', 'hours_count', 'book_code', 'required_students_number_array', 'required_students_number', 'department', 'student_category', 'year', 'included_in_plan', 'category_id'];
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function category()
    {
        return $this->belongsTo(CourseBookCategory::class, 'category_id');
    }
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : '';
    }
    public function getBookCoursesPlanProgressDisplayDataAttribute()
    {
        $completed_num_percentage = $this->required_students_number
            ? round((($this->courses_passed_students_count / $this->required_students_number) * 100), 2)
            : 0;
        $excess_num_percentage = $completed_num_percentage > 100 ? $completed_num_percentage - 100 : 0;
        $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;
        return [
            'id' => $this->id,
            'book_name' => $this->name,
            'graduated_categories' => $this->courses_passed_students_categories,
            'required_num' => $this->required_students_number,
            'completed_num' => $this->courses_passed_students_count,
            'completed_num_percentage' => $completed_num_percentage,
            'excess_num_percentage' => $excess_num_percentage,
        ];
    }




    public function getCourseStudentsReportsByAreaRowDataAttribute()
    {

        $year = date("Y");

        self::$counter++;
        $sub_areas = [];


        $data = '';
        $total_pass = 0;
        $total_rest = 0;
        $total_plus = 0;
        $plus_percentage = 0;



        $sub_area_id = $_REQUEST ? $_REQUEST['sub_area_id'] : 0;
        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;
        $teacher_id = $_REQUEST ? $_REQUEST['teacher_id'] : 0;
        $book_id = $_REQUEST ? $_REQUEST['book_id'] : '';
        $place_id = $_REQUEST ? $_REQUEST['place_id'] : '';




        if ($area_id) {
            $areas = Area::where('id', $area_id)->get();
            $this->required_students_number  = ceil($this->required_students_number * $areas[0]->percentage / 100) ;
        } else {
            $areas = Area::whereNull('area_id')->get();
        }

        if ($sub_area_id) {
            if ($sub_area_id == 'all') {
                $sub_areas = Area::where('area_id', $area_id)->get();
            } else {
                $sub_areas = Area::where('id', $sub_area_id)->get();
                // $this->required_students_number  = floor(floor($this->required_students_number * $sub_areas[0]->percentage) / 100);
            }
        }


        $required = 0;
        $rest = 0;
        $subarea_required = 0;
        // $all_areas_total_array = array();
        foreach ($areas as $key => $area) {

            if ($sub_areas) {
                $required = ceil(($area->percentage * $this->required_students_number)/ 100) ;
                foreach ($sub_areas as $key => $sub_area) {
                    $pass = CourseStudent::book($this->id)
                        ->subarea($sub_area->id, $area->id)
                        ->course('????????????')
                        ->whereBetween('mark', [60, 101])->count();


                    $subarea_required = ceil(($sub_area->percentage *   $this->required_students_number) / 100);

                    $rest = $pass - $subarea_required;
                    $rest = $this->required_students_number ? ceil($rest) : 0;

                    $icon = '';
                    if ($rest < 0) {
                        $color = '#cc0000';
                        $total_rest += $rest;
                        $icon = '-';
                    } else {
                        $color =  '#009933';
                        $total_plus += $rest;
                        $icon = '+';
                    }

                    $total_pass += $pass;
                    $data .= '<td>' . $pass .'</td>
                    <td  style="color:' . $color . '"><b>' . $icon . ' ' . abs($rest) . '</b></td>';
                }
            } else {
                $pass = CourseStudent::book($this->id)
                    ->subarea(0, $area->id)
                    ->course('????????????')
                    ->whereBetween('mark', [60, 101])->count();



                if($area_id){
                    $required = $this->required_students_number;
                }else{
                    $required = ceil(($area->percentage * $this->required_students_number) / 100);
                }


                $rest = $pass - $required;
                $rest = $this->required_students_number ? ceil($rest) : 0;

                $icon = '';
                if ($rest < 0) {
                    $color = '#cc0000';
                    $total_rest += $rest;
                    $icon = '-';
                } else {
                    $color =  '#009933';
                    $total_plus += $rest;
                    $icon = '+';
                }

                $total_pass += $pass;
                $data .= '<td>' . $pass. '</td>
                    <td  style="color:' . $color . '"><b>' . $icon . ' ' . abs($rest) . '</b></td>';
            }
        }



        $pass_percentage = $this->required_students_number ? round((($total_pass / $required) * 100), 2) :0;

        if ($pass_percentage > 100) {
            $pass_percentage = 100;
        }

        if($subarea_required && $sub_area_id !='all'){
            $required = ceil($subarea_required);
        }else{
            $required = $this->required_students_number;
        }


        $plus_percentage = $required ? round((($total_plus / $required) * 100), 2) : 0;


        $total_rest = abs($total_rest);
        if($total_rest > 0 && $pass_percentage == 100){
            $total_rest_per = round((($total_rest / $required) * 100), 1);
            $pass_percentage -= $total_rest_per;
        }elseif($total_rest > 0 && $pass_percentage < 100){
            $total_rest_per = round((($total_rest / $required) * 100), 1);
            $pass_percentage = abs($total_rest_per -100);
            $plus_percentage = 0;
        }else{
            $pass_percentage = 100;
        }




        if($total_pass > $required && $required>0){
            $super_plus = $total_pass - $required;
            $plus_percentage = round(($super_plus/$required)*100,2);
        }

        if($total_pass == 0){
            $pass_percentage = 0;
        }



        $review_result = array(
            'name' => $this->name,
            'required_students_number' =>  $required,
            'data' =>  $data,
            'total_pass' => $total_pass,
            'total_rest' => $total_rest,
            'total_plus' => $total_plus,


            'pass_percentage' => $pass_percentage,
            'plus_percentage' => $plus_percentage,

            'id' => $this->id,
        );

        return $review_result;
    }

    public function getStudentsReportsByStudentsCategoriesRowDataAttribute()
    {



        $sub_area_id = $_REQUEST ? $_REQUEST['sub_area_id'] : 0;
        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;
        $teacher_id = $_REQUEST ? $_REQUEST['teacher_id'] : 0;
        $book_id = $_REQUEST ? $_REQUEST['book_id'] : '';
        $place_id = $_REQUEST ? $_REQUEST['place_id'] : '';
        $start_date = $_REQUEST ? $_REQUEST['start_date'] : '';
        $end_date = $_REQUEST ? $_REQUEST['end_date'] : '';


        self::$counter++;

        $requierd_number = json_decode($this->required_students_number_array);
        $primary_point = 1;
        $middle_point = 2;
        $high_point = 3;

        if ($area_id > 0) {
            $area = Area::find($area_id);
            $required_student_primary = ceil($requierd_number[0] * ($area->percentage / 100));
            $required_student_middle = ceil($requierd_number[1] * ($area->percentage / 100));
            $required_student_high = ceil($requierd_number[2] * ($area->percentage / 100));
            $required_student_total = ceil($this->required_students_number * ($area->percentage / 100));
        } else {
            $required_student_primary = $requierd_number[0];
            $required_student_middle = $requierd_number[1];
            $required_student_high = $requierd_number[2];
            $required_student_total = $this->required_students_number;
        }

        if ($sub_area_id > 0) {
            $area = Area::find($sub_area_id);
            $required_student_primary = ceil($required_student_primary * ($area->percentage / 100));
            $required_student_middle = ceil($required_student_middle * ($area->percentage / 100));
            $required_student_high = ceil($required_student_high * ($area->percentage / 100));
            $required_student_total = ceil($required_student_total * ($area->percentage / 100));
        }
        //
        // $pass = CourseStudent::book($this->id)
        // ->subarea(0,$area->id)
        // ->whereBetween('mark', [60, 101])->count();

        $total_pass = CourseStudent::book($this->id)
            ->coursebookorteacher($teacher_id, $book_id, $place_id)
            ->subarea($sub_area_id, $area_id)
            ->course('????????????')
            ->whereBetween('mark', [60, 101])->count();


        // ->whereHas('course',function($query) use ($start_date,$end_date){
        //     if($start_date || $end_date){
        //         $query->whereHas('exam',function($query) use ($start_date,$end_date){
        //             $query->fromDate($start_date)->toDate($end_date);
        //         });
        //     }
        // })
        // ->course('????????????')->whereBetween('mark', [60, 101])->count();

        $passed_students_count = $total_pass;
        $completed_num_percentage = $required_student_total ? round((($passed_students_count / $required_student_total) * 100), 2) : 0;
        $excess_num_percentage = $completed_num_percentage > 100 ? $completed_num_percentage - 100 : 0;
        $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;




        $primary = CourseStudent::whereHas('user', function ($query) {
            $to = Carbon::now()->subYears(4)->startOfYear()->format('d-m-Y');
            $from = Carbon::now()->subYears(12)->startOfYear()->format('d-m-Y');
            $query->whereBetween('dob', [$from, $to]);
        })->book($this->id)
            ->coursebookorteacher($teacher_id, $book_id, $place_id)
            ->subarea($sub_area_id, $area_id)
            // ->whereHas('course',function($query) use ($start_date,$end_date){
            //     if($start_date || $end_date){
            //         $query->whereHas('exam',function($query) use ($start_date,$end_date){
            //             $query->fromDate($start_date)->toDate($end_date);
            //         });
            //     }
            // })
            ->course('????????????')->whereBetween('mark', [60, 101])->count();

        $passed_students_count_primary = $primary;
        $completed_num_percentage_primary = $required_student_primary ? round((($passed_students_count_primary / $required_student_primary) * 100), 2) : 0;
        $excess_num_percentage_primary = $completed_num_percentage_primary > 100 ? $completed_num_percentage_primary - 100 : 0;
        $completed_num_percentage_primary = $completed_num_percentage_primary > 100 ? 100 : $completed_num_percentage_primary;



        $middle = CourseStudent::whereHas('user', function ($query) {
            $to = Carbon::now()->subYears(13)->startOfYear()->format('d-m-Y');
            $from = Carbon::now()->subYears(15)->startOfYear()->format('d-m-Y');
            $query->whereBetween('dob', [$from, $to]);
        })->book($this->id)
            ->coursebookorteacher($teacher_id, $book_id, $place_id)
            ->subarea($sub_area_id, $area_id)
            // ->whereHas('course',function($query) use ($start_date,$end_date){
            //     if($start_date || $end_date){
            //         $query->whereHas('exam',function($query) use ($start_date,$end_date){
            //             $query->fromDate($start_date)->toDate($end_date);
            //         });
            //     }
            // })
            ->course('????????????')->whereBetween('mark', [60, 101])->count();
        $passed_students_count_middle = $middle;

        $completed_num_percentage_middle = $required_student_middle ? round((($passed_students_count_middle / $required_student_middle) * 100), 2) : 0;
        $excess_num_percentage_middle = $completed_num_percentage_middle > 100 ? $completed_num_percentage_middle - 100 : 0;
        $completed_num_percentage_middle = $completed_num_percentage_middle > 100 ? 100 : $completed_num_percentage_middle;


        $high = CourseStudent::whereHas('user', function ($query) {
            $from = Carbon::now()->subYears(16)->startOfYear()->format('d-m-Y');
            $query->where('dob', '>=', $from);
        })->book($this->id)
            ->coursebookorteacher($teacher_id, $book_id, $place_id)
            ->subarea($sub_area_id, $area_id)
            // ->whereHas('course',function($query) use ($start_date,$end_date){
            //     if($start_date || $end_date){
            //         $query->whereHas('exam',function($query) use ($start_date,$end_date){
            //             $query->fromDate($start_date)->toDate($end_date);
            //         });
            //     }
            // })
            ->course('????????????')->whereBetween('mark', [60, 101])->count();
        $passed_students_count_high = $high;

        $completed_num_percentage_high = $required_student_high ? round((($passed_students_count_high / $required_student_high) * 100), 2) : 0;
        $excess_num_percentage_high = $completed_num_percentage_high > 100 ? $completed_num_percentage_high - 100 : 0;
        $completed_num_percentage_high = $completed_num_percentage_high > 100 ? 100 : $completed_num_percentage_high;





        $review_result = array(
            'name' => $this->name,
            'required_number' =>  $required_student_total,

            'required_student_primary' =>  $required_student_primary,
            'passed_students_count_primary' =>  $passed_students_count_primary,
            'completed_num_percentage_primary' =>  $completed_num_percentage_primary,
            'excess_num_percentage_primary' =>  $excess_num_percentage_primary,

            'required_student_middle' =>  $required_student_middle,
            'passed_students_count_middle' =>  $passed_students_count_middle,
            'completed_num_percentage_middle' =>  $completed_num_percentage_middle,
            'excess_num_percentage_middle' =>  $excess_num_percentage_middle,

            'required_student_high' =>  $required_student_high,
            'passed_students_count_high' =>  $passed_students_count_high,
            'completed_num_percentage_high' =>  $completed_num_percentage_high,
            'excess_num_percentage_high' =>  $excess_num_percentage_high,

            'total_pass' => $total_pass,
            'completed_num_percentage' => $completed_num_percentage,
            'excess_num_percentage' => $excess_num_percentage,

            'id' => $this->id,
        );

        return $review_result;
    }

    public function getBookDisplayDataAttribute()
    {
        $copyBookButton = $this->is_exists_in_all_years ? ''
            : '<button type="button" class="btn btn-primary" title="?????? ???????????? ?????? ?????? ??????????" onclick="copyToYear(this,' . $this->id . ')"><i class="mdi mdi-check"></i></button>';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'hours_count' => $this->hours_count,
            'category_name' => $this->category_name,
            'tools' => $copyBookButton . '
                <button type="button" class="btn btn-warning" title="?????????? ???????????? ????????????" data-url="' . route('books.edit', $this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger" title="?????? ???????????? ????????????" data-url="' . route('books.destroy', $this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select' => '
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="' . $this->id . '">
                        </div>'
        ];
    }
    public function getSearchedBookDisplayDataAttribute()
    {
        return [
            'id' => $this->id,
            'name' => '<span style="background-color: #2ca02c;color: #fff;">' . $this->name . '</span>',
            'tools' => '
                        <button type="button" class="btn btn-warning btn-sm" data-url="' . route('books.edit', $this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="' . route('books.destroy', $this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select' => '
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="' . $this->id . '">
                        </div>'
        ];
    }
    public function searchedBookDisplayData($searchOutputValue)
    {
        return [
            'id' => $this->id,
            'name' => '<span style="background-color: red;color: #fff;">' . $this->name . ' || ' . $searchOutputValue . '</span>',
            'tools' => '
                        <button type="button" class="btn btn-warning btn-sm" data-url="' . route('books.edit', $this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="' . route('books.destroy', $this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select' => '
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="' . $this->id . '">
                        </div>'
        ];
    }
    public function getBookSearchedResultForCircleAttribute()
    {
        return '<li class="list-group-item"><a class="selected-book"
                    data-id="' . $this->id . '" data-name="' . $this->name . '">' . $this->name . '</a></li>';
    }
    public function getBookOptionAttribute()
    {
        return '<option value="' . $this->id . '">' . $this->name . '</option>';
    }
    public function getStudentCategoryArrayAttribute()
    {
        return $this->student_category ? json_decode($this->student_category) : [];
    }
    public function getStudentCategoryStringAttribute()
    {
        //        dd(55);
        $studentCatStr = '';
        foreach ($this->student_category_array as $key => $value) {
            if (!$key) {
                $studentCatStr .= $value;
            } else {
                $studentCatStr .= '_' . $value;
            }
        }
        return $studentCatStr;
    }
    public function setStudentCategoryAttribute($value)
    {
        if (
            (isset($value[0]) && isset($value[1]) && isset($value[2]) && isset($value[3])) &&
            ($value[0] == 0 && $value[1] == 0 && $value[2] == 0 && $value[3] == 0)
        ) {
            $this->attributes['student_category'] = null;
        } else {
            $this->attributes['student_category'] = json_encode($value);
        }
    }
    public function getRequiredStudentsNumberArrayAsArrayAttribute()
    {
        return $this->required_students_number_array ? json_decode($this->required_students_number_array) : [];
    }
    public function getRequiredStudentsNumberArrayStringAttribute()
    {
        $studentCatStr = '';
        foreach ($this->required_students_number_array_as_array as $key => $value) {
            if ($key == 0) {
                $string_value = '???????????????? ( 7 - 12 )';
            } elseif ($key == 1) {
                $string_value = '?????????????? ( 13 - 15 )';
            } elseif ($key == 2) {
                $string_value = '???????????? ?????? ?????? ( 16 ?????? ?????? )';
            } elseif ($key == 3) {
                $string_value = '???????????? ?????? ?????? ( 16 ?????? ?????? )';
            }
            if (!$key) {
                $studentCatStr .= $string_value;
            } else {
                $studentCatStr .= ' - ' . $string_value;
            }
        }
        return $studentCatStr;
    }
    public function setRequiredStudentsNumberArrayAttribute($value)
    {
        $this->attributes['required_students_number_array'] = json_encode($value);
    }
    /**
     * Scopes
     */
    public function scopeSearch($query, $searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%")
            ->orWhere('author', 'like', "%" . $searchWord . "%")
            ->orWhere('pass_mark', 'like', "%" . $searchWord . "%")
            ->orWhere('hadith_count', 'like', "%" . $searchWord . "%")
            ->orWhere('hours_count', 'like', "%" . $searchWord . "%")
            ->orWhere('book_code', 'like', "%" . $searchWord . "%")
            ->orWhere('year', 'like', "%" . $searchWord . "%")
            ->orWhere('included_in_plan', 'like', "%" . $searchWord . "%")
            ->orWhere('required_students_number', 'like', "%" . $searchWord . "%")
            ->orWhereHas('category', function ($query) use ($searchWord) {
                $query->search($searchWord);
            });
    }
    public function scopeDepartment($query, $department)
    {
        switch ($department) {
            case 0: {
                    return $query;
                }
                break;
            case 1: {
                    return $query->where('department', (int)$department);
                }
                break;
            case 2: {
                    return $query->where('department', (int)$department);
                }
                break;
        }
    }
    public function scopeAuthor($query, $author)
    {
        //        dd($author);
        //        dd($author);
        if ($author) {
            return $query->where('author', $author);
        } else {
            return $query;
        }
    }
    public function scopeBook($query, $book_id)
    {
        if ($book_id) {
            return $query->where('id', $book_id);
        } else {
            return $query;
        }
    }
    public function scopeCourseBookCategory($query, $courseBookCategory)
    {
        //        var_dump($courseBookCategory);
        if ($courseBookCategory) {
            return $query->where('category_id', $courseBookCategory);
        } else {
            return $query;
        }
    }

    /**
     * End Scopes
     */
    public function plans()
    {
        return $this->hasMany(BookPlan::class);
    }
    public function coursePlans()
    {
        return $this->hasMany(CourseBookPlan::class);
    }
    public function CoursePlansFatherAreaValues($year, $area_id)
    {
        $area = Area::with('subArea')->find($area_id);
        $sub_area_ids = $area->subArea->pluck('id');
        return $this->coursePlans->count() ? $this->coursePlans->where('year', $year)->whereIn('area_id', $sub_area_ids)->sum('value') : 0;
    }
    public function CoursePlansSubAreaValues($year, $area_id)
    {
        $area = Area::find($area_id);
        return $this->coursePlans->count() ? $this->coursePlans->where('year', $year)->whereIn('area_id', $area_id)->sum('value') : 0;
    }
    public function getCoursesPassedStudentsCountAttribute()
    {
        $total = 0;
        $finishedCourses = $this->courses->count() ? $this->courses->where('status', '????????????')->where('included_in_plan', '???????? ??????????') : [];
        foreach ($finishedCourses as $course) {
            $total += $course->passedStudents->count();
        }
        return $total;
    }
    public function getCoursesPassedStudentsCategoriesAttribute()
    {
        $categories = array();
        $finishedCourses = $this->courses->count() ? $this->courses->where('status', '????????????')->where('included_in_plan', '???????? ??????????') : [];

        foreach ($finishedCourses as $course) {
            foreach ($course->passedStudents as $student) {
                $category = $student->student_category;
                if (!in_array($category, $categories, true)) {
                    array_push($categories, $category);
                }
            }
        }
        if ($categories) {
            return implode(' - ', $categories);
        } else {
            return '???? ???????? ????????????';
        }
    }
    public function getAreaCoursesPassedStudentsCount($area_id)
    {
        $total = 0;
        $courses = Course::where('status', '????????????')
            ->where('book_id', $this->id)
            ->where('included_in_plan', '???????? ??????????')
            ->whereHas('place', function ($query) use ($area_id) {
                $query->fatherarea($area_id);
            });
        $finishedCourses = $this->courses->count() ? $courses->get() : [];
        //        dd($this->id,$this,$area_id,$courses->get());
        foreach ($finishedCourses as $course) {
            $total += $course->passedStudents->count();
        }
        return $total;
    }
    public function getYearsDoesNotHaveThisBookAttribute()
    {
        $book_name = $this->name;
        $book_years = Book::where('name', $book_name)->get()->pluck('year')->toArray();
        //        dd($book_years);
        $years = CourseBookPlan::distinct()->get(['year'])->pluck('year');
        $options = '<select class="form-control" id="years_select"><option value="0">???????? ?????????? ?????????????? ?????? ???????????? ???????????? ??????????</option>';
        foreach ($years as $key => $year) {
            if (!in_array($year, $book_years)) {
                $options .= '<option value="' . $year . '">' . $year . '</option>';
            }
        }
        return $options . '</select>';
    }
    public function getIsExistsInAllYearsAttribute()
    {
        $book_name = $this->name;
        $book_years = Book::where('name', $book_name)->get()->pluck('year')->toArray();
        $years = CourseBookPlan::distinct()->get(['year'])->pluck('year');
        foreach ($years as $key => $year) {
            if (!in_array($year, $book_years)) {
                return false;
            }
        }
        return true;
    }


    /**
     * Start activities functions
     */

    public function getFillableArabic($fillable)
    {
        switch ($fillable) {
            case 'id': {
                    return '????????????';
                }
                break;
            case 'name': {
                    return '?????? ????????????';
                }
                break;
            case 'author': {
                    return '?????? ????????????';
                }
                break;
            case 'pass_mark': {
                    return '???????? ????????????';
                }
                break;
            case 'hadith_count': {
                    return '?????? ????????????????';
                }
                break;
            case 'hours_count': {
                    return '?????? ??????????????';
                }
                break;
            case 'book_code': {
                    return '?????? ????????????';
                }
                break;
            case 'required_students_number_array': {
                    return '?????? ???????????? ??????????????';
                }
                break;
            case 'student_category': {
                    return '???????? ???????????? ????????????????';
                }
                break;
            case 'year': {
                    return '??????????';
                }
                break;
            case 'included_in_plan': {
                    return '?????? ??????????';
                }
                break;
            case 'category_id': {
                    return '?????????? ????????????';
                }
                break;
            default: {
                    return 0;
                }
        }
    }
    public function getFillableRelationData($fillable)
    {
        switch ($fillable) {
            case 'id': {
                    return $this->id;
                }
                break;
            case 'name': {
                    return $this->name;
                }
                break;
            case 'author': {
                    return $this->author;
                }
                break;
            case 'pass_mark': {
                    return $this->pass_mark;
                }
                break;
            case 'hadith_count': {
                    return $this->hadith_count;
                }
                break;
            case 'hours_count': {
                    return $this->hours_count;
                }
                break;
            case 'book_code': {
                    return $this->book_code;
                }
                break;
            case 'required_students_number_array': {
                    return $this->required_students_number;
                }
                break;
            case 'student_category': {
                    return $this->student_category_string;
                }
                break;
            case 'year': {
                    return $this->year;
                }
                break;
            case 'included_in_plan': {
                    return $this->included_in_plan;
                }
                break;
            case 'category_id': {
                    return $this->category_name;
                }
                break;
        }
    }
    protected static $logAttributes = ['id', 'name', 'author', 'pass_mark', 'hadith_count', 'hours_count', 'book_code', 'required_students_number_array', 'required_students_number', 'department', 'student_category', 'year', 'included_in_plan', 'category_id'];
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $eventName;
        $log_name = $description;
        switch ($eventName) {
            case 'created': {
                    $description = ' ?????? ' . $activity->causer->name . ' ???????????? ???????????? ' . $this->name . ' ?????????? ' . $this->year;
                }
                break;
            case 'updated': {
                    $description = ' ?????? ' . $activity->causer->name . ' ???????????? ???????????? ' . $this->name . ' ?????????? ' . $this->year;
                }
                break;
            case 'deleted': {
                    $description = ' ?????? ' . $activity->causer->name . ' ???????? ???????????? ' . $this->name . ' ?????????? ' . $this->year;
                }
                break;
        }
        $activity->description = $description;
        $activity->log_name = $log_name;
        $activity->save();
    }
    public function CreatedBy()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    public function getCreatedAtUserAttribute()
    {
        return $this->CreatedBy ? $this->CreatedBy->causer : 0;
    }

    /**
     * End activities functions
     */
}
