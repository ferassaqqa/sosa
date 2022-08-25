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

        $data = '';
        $total_pass = 0;
        $total_rest = 0;
        $total_plus = 0;

        if($area_id){
            $areas = Area::where('id',$area_id)->get();
            $this->required_students_number  = floor(floor($this->required_students_number * $areas[0]->percentage)/100);

        }else{
            $areas = Area::whereNull('area_id')->get();
        }

        if($sub_area_id){
            $areas = Area::where('id',$sub_area_id)->get();
            $this->required_students_number  = floor(floor($this->required_students_number * $areas[0]->percentage)/100);
        }



        $rest = 0;

        foreach ($areas as $key => $area) {

            $pass = AsaneedCourseStudent::whereHas('asaneedCourse')->subarea($sub_area_id, $area->id)->count();

            $rest = $this->required_students_number ? $pass - floor(($area->percentage * $this->required_students_number)  / 100) : 0;

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

            // $total_pass +=$pass;
            // $total_rest +=$rest;

            $total_pass += $pass;
            $data .= '<td>' . $pass . '</td>
                        <td  style="color:' . $color . '"><b>' . $icon . ' ' . abs($rest) . '</b></td>';
        }


        // $pass_percentage = $this->required_students_number? round((($total_pass/$this->required_students_number) * 100), 2):0;


        $pass_percentage = $this->required_students_number ? round((($total_pass / $this->required_students_number) * 100), 2) : 0;
        $plus_percentage = $this->required_students_number ? round((($total_plus / $this->required_students_number) * 100), 2) : 0;


        if ($pass_percentage > 100) {
            $pass_percentage = 100;
        }


        $review_result = array(
            'name' => $this->name,
            'required_students_number' =>  $this->required_students_number,
            'data' =>  $data,
            'total_pass' => $total_pass,
            'total_rest' => abs($total_rest),
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


        self::$counter++;

        $requierd_number = json_decode($this->required_students_number_array);
        $total_pass = AsaneedCourseStudent::book($this->id)
            ->teacher($teacher_id)
            ->subarea($sub_area_id, $area_id)
            // ->placearea($place_id)
            ->whereHas('asaneedCourse', function ($query) use ($start_date, $end_date) {
                if ($start_date || $end_date) {
                    $query->whereHas('exam', function ($query) use ($start_date, $end_date) {
                        $query->fromDate($start_date)->toDate($end_date);
                    });
                }
            })
            ->whereBetween('mark', [60, 101])->count();

        $passed_students_count = $total_pass;
        $completed_num_percentage = $this->required_students_number ? round((($passed_students_count / $this->required_students_number) * 100), 2) : 0;
        $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;
        $excess_num_percentage = $completed_num_percentage > 100 ? $completed_num_percentage - 100 : 0;




        $primary = AsaneedCourseStudent::whereHas('user', function ($query) {
            $to = Carbon::now()->subYears(7)->startOfYear()->format('d-m-Y');
            $from = Carbon::now()->subYears(12)->startOfYear()->format('d-m-Y');
            $query->whereBetween('dob', [$from, $to]);
        })->book($this->id)
            ->teacher($teacher_id)
            ->subarea($sub_area_id, $area_id)
            ->whereHas('asaneedCourse', function ($query) use ($start_date, $end_date) {
                if ($start_date || $end_date) {
                    $query->whereHas('exam', function ($query) use ($start_date, $end_date) {
                        $query->fromDate($start_date)->toDate($end_date);
                    });
                }
            })
            ->whereBetween('mark', [60, 101])->count();

        $passed_students_count_primary = $primary;
        $completed_num_percentage_primary = $requierd_number[0] ? round((($passed_students_count_primary / $requierd_number[0]) * 100), 2) : 0;
        $completed_num_percentage_primary = $completed_num_percentage_primary > 100 ? 100 : $completed_num_percentage_primary;
        $excess_num_percentage_primary = $completed_num_percentage_primary > 100 ? $completed_num_percentage_primary - 100 : 0;



        $middle = AsaneedCourseStudent::whereHas('user', function ($query) {
            $to = Carbon::now()->subYears(13)->startOfYear()->format('d-m-Y');
            $from = Carbon::now()->subYears(15)->startOfYear()->format('d-m-Y');
            $query->whereBetween('dob', [$from, $to]);
        })->book($this->id)
            ->teacher($teacher_id)
            ->subarea($sub_area_id, $area_id)
            ->whereHas('asaneedCourse', function ($query) use ($start_date, $end_date) {
                if ($start_date || $end_date) {
                    $query->whereHas('exam', function ($query) use ($start_date, $end_date) {
                        $query->fromDate($start_date)->toDate($end_date);
                    });
                }
            })
            ->whereBetween('mark', [60, 101])->count();

        $passed_students_count_middle = $middle;
        $completed_num_percentage_middle = $requierd_number[1] ? round((($passed_students_count_middle / $requierd_number[1]) * 100), 2) : 0;
        $completed_num_percentage_middle = $completed_num_percentage_middle > 100 ? 100 : $completed_num_percentage_middle;
        $excess_num_percentage_middle = $completed_num_percentage_middle > 100 ? $completed_num_percentage_middle - 100 : 0;


        $high = AsaneedCourseStudent::whereHas('user', function ($query) {
            $from = Carbon::now()->subYears(16)->startOfYear()->format('d-m-Y');
            $query->where('dob', '>=', $from);
        })->book($this->id)
            ->teacher($teacher_id)
            ->subarea($sub_area_id, $area_id)
            ->whereHas('asaneedCourse', function ($query) use ($start_date, $end_date) {
                if ($start_date || $end_date) {
                    $query->whereHas('exam', function ($query) use ($start_date, $end_date) {
                        $query->fromDate($start_date)->toDate($end_date);
                    });
                }
            })
            ->whereBetween('mark', [60, 101])->count();
        $passed_students_count_high = $high;

        $completed_num_percentage_high = $requierd_number[2] ? round((($passed_students_count_high / $requierd_number[2]) * 100), 2) : 0;
        $completed_num_percentage_high = $completed_num_percentage_high > 100 ? 100 : $completed_num_percentage_high;
        $excess_num_percentage_high = $completed_num_percentage_high > 100 ? $completed_num_percentage_high - 100 : 0;


        // // Book::where('id', $this->id)->update(['total_students_passed' => $total_pass]);


        return '            <tr>
        <tr>
            <th rowspan="4">' . self::$counter . '</th>
            <th rowspan="4" style="background: #f0f0f0">' . $this->name . '</th>
            <th>ابتدائية ( 7 - 12 )</th>
            <td>' . $requierd_number[0] . '</td>
            <td>' . $passed_students_count_primary . '</td>
            <td>' . $completed_num_percentage_primary . ' %</td>
            <td>' . $excess_num_percentage_primary . ' %</td>

        </tr>
        <tr>
            <th>اعدادية ( 13 - 15 )</th>
            <td>' . $requierd_number[1] . '</td>
            <td>' . $passed_students_count_middle . '</td>
            <td>' . $completed_num_percentage_middle . ' %</td>
            <td>' . $excess_num_percentage_middle . ' %</td>
        </tr>
        <tr>
            <th>ثانوية فما فوق ( 16 فما فوق )</th>
            <td>' . $requierd_number[2] . '</td>
            <td>' . $passed_students_count_high . '</td>
            <td>' . $completed_num_percentage_high . ' %</td>
            <td>' . $excess_num_percentage_high . ' %</td>
        </tr>
        <tr style="background: #f0f0f0">
            <th>المجموع</th>
            <td>' . $this->required_students_number . '</td>
            <td>' . $total_pass . '</td>
            <td>' . $completed_num_percentage . ' %</td>
            <td>' . $excess_num_percentage . ' %</td>
        </tr>
        </tr>';
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
