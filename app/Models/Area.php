<?php

namespace App\Models;

use App\Events\Areas\AreaSavedEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Area extends Model
{
    use HasFactory;
    public static $counter=0;

    protected $fillable = ['name','area_id','area_supervisor_id','sub_area_supervisor_id','percentage','student_marks_export_count','student_marks_export_year'];
    public function subArea(){
        return $this->hasMany(Area::class,'area_id','id')->withoutGlobalScope('relatedAreas');
    }
    public function places(){
        return $this->hasMany(Place::class,'area_id','id')->withoutGlobalScope('relatedPlaces');
    }
    public function getFirstPlaceIdAttribute(){
        if($this->subArea->count()){
            if($this->subArea[0]->places->count()){
                return $this->subArea[0]->places[0]->id;
            }
        }else{
            if($this->places->count()){
//                dd($this->places[0]->id);
                return $this->places[0]->id;

            }else{
                return 0;
            }
        }
    }
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id')->withoutGlobalScope('relatedAreas');
    }
    public function getSubAreaPercentageAttribute(){
        return $this->area ? ((double)$this->area->percentage * (double)$this->percentage) /10000 : ((double)$this->percentage /100);
    }
    public function getAreaFatherNameAttribute(){
        return $this->area ? $this->area->name : '';
    }
    public function getAreaFatherIdAttribute(){
        return $this->area ? $this->area->id : $this->id;
    }
    public function getAreaNameWithPercentage($year,$book_id){
//        dd($this->CourseBookPlan->where('year',$year)->where('book_id',$book_id)->first(),$book_id,$this->id);
        $areaTotalPercentage = $this->CourseBookPlan->count() ?
            ($this->CourseBookPlan->where('year',$year)->where('book_id',$book_id)->first() ? $this->CourseBookPlan->where('year',$year)->where('book_id',$book_id)->first()->percentage : 0)
            : 0 ;
        return (string)($this->name .' '.$areaTotalPercentage.'%');
    }
    public function getAreaNameWithPercentageForAsaneed($year,$book_id){
//        dd($this->AsaneedBookPlan->where('year',$year)->where('book_id',$book_id)->first(),$book_id,$this->id);
        $areaTotalPercentage = $this->AsaneedBookPlan->count() ?
            ($this->AsaneedBookPlan->where('year',$year)->where('book_id',$book_id)->first() ? $this->AsaneedBookPlan->where('year',$year)->where('book_id',$book_id)->first()->percentage : 0)
            : 0 ;
        return (string)($this->name .' '.$areaTotalPercentage.'%');
    }
    public function CourseBookPlan(){
        return $this->hasMany(CourseBookPlan::class,'area_id');
    }
    public function AsaneedBookPlan(){
        return $this->hasMany(AsaneedBookPlan::class,'area_id');
    }
    public function getAreaDisplayDataAttribute(){
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'tools'=>'
                <button type="button" class="btn btn-warning btn-sm" data-url="'.route('areas.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-url="'.route('areas.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>'
        ];
    }
    public function getSearchedAreaDisplayDataAttribute(){
        return [
            'id'=>$this->id,
            'name'=>'<span style="background-color: #2ca02c;color: #fff;">'.$this->name.'</span>',
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.route('areas.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.route('areas.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select'=>'
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="'.$this->id.'">
                        </div>'
        ];
    }
    public function searchedAreaDisplayData($searchOutputValue){
        return [
            'id'=>$this->id,
            'name'=>'<span style="background-color: red;color: #fff;">'.$this->name.' || '.$searchOutputValue.'</span>',
            'tools'=>'
                        <button type="button" class="btn btn-warning btn-sm" data-url="'.route('areas.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-url="'.route('areas.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select'=>'
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="'.$this->id.'">
                        </div>'
        ];
    }
    public function getAreaSearchedResultForPlaceAttribute(){
        return '<li class="list-group-item"><a class="selected-area"
                    data-id="'.$this->id.'" data-name="'.$this->name.'">'.$this->name.' -> '.$this->area_father_name.'</a></li>';
    }

    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
//        dd($searchWord);
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('name', 'like', "%" . $searchWord . "%")
            ->orWhereHas('area',function($query) use ($searchWord){
                $query->where('name', 'like', "%" . $searchWord . "%");
            });
    }
    /**
     * permissions scopes
     */
    public function scopePermissionsSubArea($query,$sub_area_id,$area_id)
    {
        if($area_id && !$sub_area_id){
            return $query->where('id', $area_id)
                        ->orWhere('area_id',$area_id);
        }else if ($area_id && $sub_area_id) {
            return $query->where('id', $sub_area_id)
                        ->orWhere('id',$area_id);
        }else{
            return $query;
        }
    }
    /**
     * Scopes
     */
    public function courses(){
        return $this->hasManyThrough(Course::class,Place::class);
    }
    public function areaSupervisor(){
        return $this->belongsTo(User::class , 'area_supervisor_id','id')->withoutGlobalScope('relatedUsers');
    }
    public function getAreaSupervisorNameAttribute(){
        return $this->areaSupervisor ? $this->areaSupervisor->name : '';
    }
    public function subAreaSupervisor(){
        return $this->belongsTo(User::class , 'sub_area_supervisor_id','id')->withoutGlobalScope('relatedUsers');
    }
    public function getSubAreaSupervisorNameAttribute(){
        return $this->subAreaSupervisor ? $this->subAreaSupervisor->name : '';
    }

    public function scopeBook($query,$book_id){
        if($book_id){
            return $query->whereHas('courses',function($query) use ($book_id){
                $query->where('book_id',$book_id);
            });
        }else{
            return $query;
        }
    }
    public function scopeplace($query, $place_id){
        if($place_id){
            return $query->whereHas('courses',function($query) use ($place_id){
                $query->where('place_id',$place_id);
            });
        }else{
            return $query;
        }
    }
    public function scopeteacher($query, $teacher_id){
        if($teacher_id){
            return $query->whereHas('courses',function($query) use ($teacher_id){
                $query->where('teacher_id',$teacher_id);
            });
        }else{
            return $query;
        }
    }

    public function getAllReviewsRowDataAttribute(){
        self::$counter++;
        $total = 0;

        $total_students_course_passed = CourseStudent::whereHas('course')
                    ->whereBetween('mark', [60, 101])
                    ->count();

        $total_pass_by_area = CourseStudent::whereHas('course')
                                ->subarea(0, $this->id)
                                ->whereBetween('mark', [60, 101])
                                ->count();

        $area_percentage = $this->percentage;

        $passed_students_count = $total_pass_by_area;
        $completed_num_percentage = $total_students_course_passed? round((($passed_students_count/$total_students_course_passed) * 100), 2): 0;
        $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;


        return '
        <tr>
        <tr>
        <td>'.self::$counter.'</td>
            <td>'.$this->name.'</td>
            <td>'.$total.'</td>
            <td>'.$completed_num_percentage.'%</td>
            <td>5.00%</td>
            <th>94.00%</th>
            <td>الاول</td>
            <th></th>
        </tr>
        </tr>
        ';
    }

    public function getCourseReviewsRowDataAttribute(){
        self::$counter++;
        $total = 0;




        $requierd_number = json_decode($this->required_students_number_array);

        $year = date("Y");
        $books = Book::where('year', $year)->get();

        $array = [];

        foreach ($books as $key => $book) {
            $total_pass = 0;
            $total_pass = CourseStudent::book($book->id)
                                        ->subarea(0,$this->id)
                                        ->whereHas('course')
                                        ->whereBetween('mark', [60, 101])->count();


            // $completed_num_percentage = $book->required_students_number? round((($total_pass/$book->required_students_number) * 100), 2): 0;
            // $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;

            array_push($array,array($total_pass,( ($book->required_students_number*$this->percentage) / 100)));
            // $excess_num_percentage = $completed_num_percentage > 100 ? $completed_num_percentage - 100 : 0;
        }

        // dd($array);





        // $total_students_course_passed = CourseStudent::whereHas('course')
        //             ->whereBetween('mark', [60, 101])
        //             ->count();

        // $total_pass_by_area = CourseStudent::whereHas('course')
        //                         ->subarea(0, $this->id)
        //                         ->whereBetween('mark', [60, 101])
        //                         ->count();

        // $area_percentage = $this->percentage;

        // $passed_students_count = $total_pass_by_area;
        // $completed_num_percentage = $total_students_course_passed? round((($passed_students_count/$total_students_course_passed) * 100), 2): 0;
        // $completed_num_percentage = $completed_num_percentage > 100 ? 100 : $completed_num_percentage;


        return '

    <tr >
        <td>'.self::$counter.'</td>
        <td>'.$this->name.'</td>

        <td>38%</td>
        <td>5%</td>
        <td>2%</td>
        <td>3%</td>
        <td><b>50%</b></td>

        <td><b>99%</b></td>
        <td>الاول</td>
        <td></td>
    </tr>
        ';
    }

    public function getMostAccomplishedCourseRowDataAttribute(){

        self::$counter++;
        $total = 0;


        $sub_area_courses = $this->courses;


        foreach ($sub_area_courses as $key => $course) {
                $total += $course->students->count();
        }

            $most_accomplished =  DB::table('course_students')
                        ->leftJoin('courses','courses.id','=','course_students.course_id')
                        ->leftJoin('books','books.id','=','courses.book_id')
                        ->leftJoin('places','places.id','=','courses.place_id')
                        ->leftJoin('areas','areas.id','=','places.area_id')
                        ->where('places.area_id','=',$this->id)
                        ->selectRaw('course_students.course_id,books.name, count(course_students.course_id) as times_teached')
                        ->groupBy('courses.id','course_students.course_id')
                        ->orderByDesc('times_teached')
                        ->limit(1)
                        ->get();

         $top_course = ($total > 0) ? $most_accomplished[0]->name.' ('.$most_accomplished[0]->times_teached.')' : 0;

        // $top_course = 00;
        return [
            'id' => self::$counter,
            'subarea_name' =>$this->name,
            'total_accomplished_course' => $this->courses->count(),
            'total_accomplished_students' => $total,
            'most_accomplished_course' => $top_course,
        ];

    }


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($area) {
            if ($area->subArea->count()) {
                $area->subArea()->delete();
            } else {
                if ($area->places->count()) {
                    $area->places()->delete();
                }
            }

        });
    }
    protected static function booted()
    {
        parent::booted();
        if(!Auth::guest()) {
            $user = Auth::user();
            if($_SERVER['REMOTE_ADDR'] == "185.132.250.252"){
//                dd($user->area_supervisor_area_id);
            }
//            dd($user->supervisor_sub_area);
//            $sub_area_supervisor_area_id = $user->sub_area_supervisor_area_id;
            $sub_area = Area::where('sub_area_supervisor_id',$user->id)->first();
            $sub_area_supervisor_area_id = $sub_area ? $sub_area->id : 0;
            $sub_area_supervisor_area_father_id = $sub_area ? $sub_area->area_id : 0;
            $father_area = Area::where('area_supervisor_id',$user->id)->first();
            $area_supervisor_area_id = $father_area ? $father_area->id : 0;
            static::addGlobalScope('relatedAreas',function (Builder $builder) use ($user,$sub_area_supervisor_area_father_id,$area_supervisor_area_id,$sub_area_supervisor_area_id) {
                if ($user) {
                    if($_SERVER['REMOTE_ADDR'] == "185.132.250.252"){
//                        dd($user->sub_area_supervisor_area_id);
                    }
//                    $userAreas = getUserAreaId($user);
                    if ($user->hasRole('رئيس الدائرة')) {
                        return $builder;
                    } else if($user->hasRole('مدير الدائرة') || $user->hasRole('مساعد اداري')){
                        return $builder;
                    }else if($user->hasRole('مشرف عام')){
                        return $builder->permissionssubarea(0,$area_supervisor_area_id);
                    }else if($user->hasRole('مشرف ميداني')){
                        return $builder->permissionssubarea($sub_area_supervisor_area_id,$sub_area_supervisor_area_father_id);
                    }else if($user->hasRole('محفظ') || $user->hasRole('معلم') || $user->hasRole('شيخ اسناد')){
                        return $builder;
                    }else if($user->hasRole('مدير دائرة التخطيط والجودة')){
                        return $builder;
                    }else if($user->hasRole('رئيس قسم الاختبارات')){
                        return $builder;
                    }else{
                        return $builder;
                    }
                }else {
                    return false;
                }
            });
        }
    }
}
