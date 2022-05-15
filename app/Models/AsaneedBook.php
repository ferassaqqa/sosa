<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaneedBook extends Model
{
    use HasFactory;
    protected $fillable = ['name','author','pass_mark','hadith_count','hours_count','book_code','required_students_number_array','required_students_number','student_category','year','included_in_plan','category_id'];
    public function courses(){
        return $this->hasMany(AsaneedCourse::class,'book_id');
    }
    public function category(){
        return $this->belongsTo(AsaneedBookCategory::class,'category_id');
    }
    public function getCategoryNameAttribute(){
        return $this->category ? $this->category->name : '';
    }
    public function getBookDisplayDataAttribute(){
        $copyBookButton = $this->is_exists_in_all_years ? ''
            :'<button type="button" class="btn btn-primary" title="نسخ الكتاب الى خطة سنوية" onclick="copyToYear(this,'.$this->id.')"><i class="mdi mdi-check"></i></button>';
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'author'=>$this->author,
            'hours_count'=>$this->hours_count,
            'category_name'=>$this->category_name,
            'tools'=>$copyBookButton.'
                <button type="button" class="btn btn-warning" title="تعديل بيانات الكتاب" data-url="'.route('asaneedBooks.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" onclick="callApi(this,\'user_modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
                <button type="button" class="btn btn-danger" title="حذف بيانات الكتاب" data-url="'.route('asaneedBooks.destroy',$this->id).'" onclick="deleteItem(this)"><i class="mdi mdi-trash-can"></i></button>
                    ',
            'select'=>'
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="id[]" value="'.$this->id.'">
                        </div>'
        ];
    }
    public function getBookOptionAttribute(){
        return '<option value="'.$this->id.'">'.$this->name.'</option>';
    }
    public function getStudentCategoryArrayAttribute(){
        return $this->student_category ? json_decode($this->student_category) : [];
    }
    public function getStudentCategoryStringAttribute(){
//        dd(55);
        $studentCatStr = '';
        foreach ($this->student_category_array as $key => $value){
            if(!$key){
                $studentCatStr .= $value;
            }else{
                $studentCatStr .= '-'.$value;
            }
        }
        return $studentCatStr;
    }
    public function setStudentCategoryAttribute($value){
        if(
            (isset($value[0]) && isset($value[1]) && isset($value[2]) && isset($value[3])) &&
            ($value[0] == 0 && $value[1] == 0 && $value[2] == 0 && $value[3] == 0)
        ){
            $this->attributes['student_category'] = null;
        }else {
            $this->attributes['student_category'] = json_encode($value);
        }
    }
    public function getRequiredStudentsNumberArrayAsArrayAttribute(){
        return $this->required_students_number_array ? json_decode($this->required_students_number_array) : [];
    }
    public function getRequiredStudentsNumberArrayStringAttribute(){
        $studentCatStr = '';
        foreach ($this->required_students_number_array_as_array as $key => $value){
            if($key == 0){
                $string_value = 'ابتدائية';
            }elseif ($key == 1){
                $string_value = 'اعدادية';
            }elseif ($key == 2){
                $string_value = 'ثانوية';
            }elseif ($key == 3){
                $string_value = 'ثانوية فما فوق';
            }
            if(!$key){
                $studentCatStr .= $string_value;
            }else{
                $studentCatStr .= ' - '.$string_value;
            }
        }
        return $studentCatStr;
    }
    public function setRequiredStudentsNumberArrayAttribute($value){
        $this->attributes['required_students_number_array'] = json_encode($value);
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
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
            ->orWhereHas('category',function($query) use($searchWord){
                $query->search($searchWord);
            });
    }
    public function scopeAuthor($query,$author)
    {
//        dd($author);
//        dd($author);
        if($author){
            return $query->where('author', $author);
        }else{
            return $query;
        }
    }
    public function scopeAsaneedBookCategory($query,$asaneedBookCategory)
    {
//        var_dump($courseBookCategory);
        if($asaneedBookCategory){
            return $query->where('category_id', $asaneedBookCategory);
        }else{
            return $query;
        }
    }

    /**
     * End Scopes
     */
    public function coursePlans(){
        return $this->hasMany(AsaneedBookPlan::class,'book_id');
    }
    public function CoursePlansFatherAreaValues($year,$area_id){
        $area = Area::with('subArea')->find($area_id);
        $sub_area_ids = $area->subArea->pluck('id');
        return $this->coursePlans->count() ? $this->coursePlans->where('year',$year)->whereIn('area_id',$sub_area_ids)->sum('value') : 0;
    }
    public function CoursePlansSubAreaValues($year,$area_id){
        $area = Area::find($area_id);
        return $this->coursePlans->count() ? $this->coursePlans->where('year',$year)->whereIn('area_id',$area_id)->sum('value') : 0;
    }
    public function getCoursesPassedStudentsCountAttribute(){
        $total = 0;
        $finishedCourses = $this->courses->count() ? $this->courses->where('status','منتهية')->where('included_in_plan','داخل الخطة') : [];

        foreach($finishedCourses as $course){
            $total += $course->passedStudents->count();
        }
        return $total;
    }
    public function getAreaCoursesPassedStudentsCount($area_id){
        $total = 0;
        $courses = AsaneedCourse::where('status','منتهية')
            ->where('book_id',$this->id)
            ->where('included_in_plan','داخل الخطة')
            ->whereHas('place',function($query) use ($area_id){
                $query->fatherarea($area_id);
            });
        $finishedCourses = $this->courses->count() ? $courses->get() : [];
//        dd($this->id,$this,$area_id,$courses->get());
        foreach($finishedCourses as $course){
            $total += $course->passedStudents->count();
        }
        return $total;
    }
    public function getYearsDoesNotHaveThisBookAttribute(){
        $book_name = $this->name;
        $book_years = AsaneedBook::where('name',$book_name)->get()->pluck('year')->toArray();
//        dd($book_years);
        $years = AsaneedBookPlan::distinct()->get(['year'])->pluck('year');
        $options = '<select class="form-control" id="years_select"><option value="0">اختر السنة المطلوب نسخ بيانات الكتاب اليها</option>';
        foreach ($years as $key => $year){
            if(!in_array($year,$book_years)) {
                $options .= '<option value="' . $year . '">' . $year . '</option>';
            }
        }
        return $options.'</select>';
    }
    public function getIsExistsInAllYearsAttribute(){
        $book_name = $this->name;
        $book_years = AsaneedBook::where('name',$book_name)->get()->pluck('year')->toArray();
        $years = AsaneedBookPlan::distinct()->get(['year'])->pluck('year');
        foreach ($years as $key => $year){
            if(!in_array($year,$book_years)) {
                return false;
            }
        }
        return true;
    }
}
