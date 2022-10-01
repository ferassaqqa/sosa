<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class AsaneedBook extends Model
{
    use HasFactory;
    public static $counter = 0;

    protected $fillable = ['name', 'author', 'pass_mark', 'hadith_count', 'hours_count', 'book_code', 'required_students_number_array', 'required_students_number', 'student_category', 'year', 'included_in_plan', 'category_id'];
    public function courses()
    {
        return $this->hasMany(AsaneedCourse::class, 'book_id');
    }
    public function category()
    {
        return $this->belongsTo(AsaneedBookCategory::class, 'category_id');
    }
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : '';
    }
    public function getBookDisplayDataAttribute()
    {
        $copyBookButton = $this->is_exists_in_all_years ? ''
            : '<button type="button" class="btn btn-primary" title="نسخ الكتاب الى خطة سنوية" onclick="copyToYear(this,' . $this->id . ')"><i class="mdi mdi-check"></i></button>';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'hours_count' => $this->hours_count,
            'category_name' => $this->category_name,
            'tools' => $copyBookButton . '
                <button type="button" class="btn btn-warning" title="تعديل بيانات الكتاب" data-url="' . route('asaneedBooks.edit', $this->id) . '" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger" title="حذف بيانات الكتاب" data-url="' . route('asaneedBooks.destroy', $this->id) . '" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select' => '
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="' . $this->id . '">
                        </div>'
        ];
    }


    public function getAsaneedStudentsReportsByAreaRowDataAttribute()
    {

        // $asaneed_plan_books = AsaneedBook::whereNotNull('author')->get();

        $sub_area_id = $_REQUEST ? $_REQUEST['sub_area_id'] : 0;
        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;

        self::$counter++;

        $areas = Area::whereNull('area_id')->get();

        $number_of_areas = count($areas);
        $number_of_sub_areas = 0;

        $data = '';
        $total_pass = 0;
        $total_rest = 0;
        $total_plus = 0;
        $required_students_number = 0;
        $subarea_required = 0;

        if ($area_id) {
            $areas = Area::where('id', $area_id)->get();
            $number_of_areas = Area::whereNull('area_id')->withoutGlobalScope('relatedAreas')->count();
            // $this->required_students_number  = floor(floor($this->required_students_number * $areas[0]->percentage) / 100);
            // $required_students_number  = floor($this->required_students_number / $number_of_areas);
            $this->required_students_number  = floor($this->required_students_number / $number_of_areas);

            $number_of_sub_areas = Area::where('area_id', $area_id)->count();
        } else {
            $areas = Area::whereNull('area_id')->get();
        }

        // if ($sub_area_id) {
        //     $number_of_sub_areas = Area::where('area_id',$area_id)->count();
        //     // $areas = Area::where('id', $sub_area_id)->get();
        //     // $this->required_students_number  = floor(floor($this->required_students_number * $areas[0]->percentage) / 100);

        //     $this->required_students_number  = floor($this->required_students_number / $number_of_sub_areas);
        // }
        $sub_areas = [];

        if ($sub_area_id) {
            if ($sub_area_id == 'all') {
                $sub_areas = Area::where('area_id', $area_id)->get();
            } else {
                $sub_areas = Area::where('id', $sub_area_id)->get();
            }
            // $this->required_students_number  = floor($this->required_students_number / $number_of_sub_areas);
            // $required_students_number  = floor($this->required_students_number / $number_of_sub_areas);

            // dd($this->required_students_number);
        }

        // if ($sub_area_id == 'all' && !$area_id) {
        //     $sub_area_id = 0;
        //     $sub_areas = [];
        // }

        $rest = 0;

        foreach ($areas as $key => $area) {

            if ($sub_areas) {

                $subarea_required = ceil($this->required_students_number / $number_of_sub_areas);
                $required = ceil($this->required_students_number / $number_of_sub_areas);

                // dd($required);

                foreach ($sub_areas as $key => $sub_area) {
                    $pass = AsaneedCourseStudent::whereHas('asaneedCourse')->book($this->id)->subarea($sub_area->id, $area->id)->count();
                    $rest = $pass - $required;
                    $rest = ceil($rest);
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
                    $data .= '<td>' . $pass . '</td>
                    <td  style="color:' . $color . '"><b>' . $icon . ' ' . abs($rest) . '</b></td>';
                }
            } else {
                $pass = AsaneedCourseStudent::whereHas('asaneedCourse')->book($this->id)->subarea(0, $area->id)->count();
                // $required = floor($this->required_students_number / $number_of_areas);
                if($area_id){
                    $required = $this->required_students_number;
                }else{
                    $required = ceil($this->required_students_number / $number_of_areas);
                }

                $rest = $pass - $required;
                $rest = $this->required_students_number ? ceil($rest) : 0;

                $color =  '#009933';
                if ($rest < 0) {
                    $color = '#cc0000';
                }

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
                $data .= '<td>' . $pass . '</td>
                            <td  style="color:' . $color . '"><b>' . $icon . ' ' . abs($rest) . '</b></td>';
            }
        }

        // $required_students_number = $required;
        // $pass_percentage = $this->required_students_number ? round((($total_pass / $this->required_students_number) * 100), 2) : 0;

        $pass_percentage = round((($total_pass / $required) * 100), 2);

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



        $review_result = array(
            'name' => $this->name,
            'required_students_number' => $required,
            'data' =>  $data,
            'total_pass' => $total_pass,
            'total_rest' => $total_rest,
            'pass_percentage' => $pass_percentage,
            'plus_percentage' => $plus_percentage,
            'total_plus' => $total_plus,
            'id' => $this->id,
        );

        return $review_result;
    }


    public function getAsaneedStudentsReportsByStudentsCategoriesRowDataAttribute()
    {



        $sub_area_id = $_REQUEST ? $_REQUEST['sub_area_id'] : 0;
        $area_id = $_REQUEST ? $_REQUEST['area_id'] : 0;
        $teacher_id = $_REQUEST ? $_REQUEST['teacher_id'] : 0;
        $book_id = $_REQUEST ? $_REQUEST['book_id'] : '';
        $place_id = $_REQUEST ? $_REQUEST['place_id'] : '';
        $start_date = $_REQUEST ? $_REQUEST['start_date'] : '';
        $end_date = $_REQUEST ? $_REQUEST['end_date'] : '';

        $areas = Area::whereNull('area_id')->get();
        $number_of_areas = count($areas);
        $number_of_sub_areas = 0;


        if ($area_id > 0) {
            $area = Area::find($area_id);
            // $required_student_total = floor($this->required_students_number * ($area->percentage / 100));

            $required_student_total = floor($this->required_students_number / $number_of_areas);
        } else {
            $required_student_total = $this->required_students_number;
        }

        if ($sub_area_id > 0) {
            $area = Area::find($sub_area_id);
            // $required_student_total = floor($required_student_total * ($area->percentage / 100));

            $number_of_sub_areas = Area::where('area_id', $area_id)->count();
            $required_student_total  = floor($this->required_students_number / $number_of_sub_areas);
        }




        self::$counter++;
        $total_pass = AsaneedCourseStudent::book($this->id)
            ->teacher($teacher_id)
            ->subarea($sub_area_id, $area_id)
            ->count();



        $passed_students_count = $total_pass;
        $completed_num_percentage = $required_student_total ? round((($passed_students_count / $required_student_total) * 100), 2) : 0;
        $excess_num_percentage = $completed_num_percentage > 100 ? $completed_num_percentage - 100 : 0;
        $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;


        return [
            'name' => $this->name,
            'required_student_total' => $required_student_total,
            'total_pass' => $total_pass,
            'completed_num_percentage' => $completed_num_percentage,
            'excess_num_percentage' => $excess_num_percentage,
            'id' => $this->id,
        ];
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
                $studentCatStr .= '-' . $value;
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
                $string_value = 'ابتدائية';
            } elseif ($key == 1) {
                $string_value = 'اعدادية';
            } elseif ($key == 2) {
                $string_value = 'ثانوية';
            } elseif ($key == 3) {
                $string_value = 'ثانوية فما فوق';
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
    public function scopeAsaneedBookCategory($query, $asaneedBookCategory)
    {
        //        var_dump($courseBookCategory);
        if ($asaneedBookCategory) {
            return $query->where('category_id', $asaneedBookCategory);
        } else {
            return $query;
        }
    }

    /**
     * End Scopes
     */
    public function coursePlans()
    {
        return $this->hasMany(AsaneedBookPlan::class, 'book_id');
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
        $finishedCourses = $this->courses->count() ? $this->courses->where('status', 'منتهية')->where('included_in_plan', 'داخل الخطة') : [];

        foreach ($finishedCourses as $course) {
            $total += $course->passedStudents->count();
        }
        return $total;
    }
    public function getAreaCoursesPassedStudentsCount($area_id)
    {
        $total = 0;
        $courses = AsaneedCourse::where('status', 'منتهية')
            ->where('book_id', $this->id)
            ->where('included_in_plan', 'داخل الخطة')
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
        $book_years = AsaneedBook::where('name', $book_name)->get()->pluck('year')->toArray();
        //        dd($book_years);
        $years = AsaneedBookPlan::distinct()->get(['year'])->pluck('year');
        $options = '<select class="form-control" id="years_select"><option value="0">اختر السنة المطلوب نسخ بيانات الكتاب اليها</option>';
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
        $book_years = AsaneedBook::where('name', $book_name)->get()->pluck('year')->toArray();
        $years = AsaneedBookPlan::distinct()->get(['year'])->pluck('year');
        foreach ($years as $key => $year) {
            if (!in_array($year, $book_years)) {
                return false;
            }
        }
        return true;
    }
}
