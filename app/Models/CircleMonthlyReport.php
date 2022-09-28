<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CircleMonthlyReport extends Model
{
    use HasFactory;
    protected $fillable = ['circle_id','date','status','delivered_by','approved_by'];
    /**
     * status = 1, report is entered in its date, no late.
     * status = 2, report is entered successfully but entry process was late.
    */
    public function getCircleMonthlyReportDisplayDataAttribute(){
        $tools = $this->has_next_report ? '' :
//            '<button type="button" class="btn btn-warning btn-sm" data-url="'.route('circleMonthlyReports.edit',$this->id).'" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" onclick="callApi(this,\'modal_content\')"><i class="mdi mdi-comment-edit"></i></button>
             '<button type="button" class="btn btn-danger" data-url="'.route('circleMonthlyReports.deleteCircleMonthlyReport',$this->id).'" onclick="deleteCircleMonthlyReport(this)"><i class="mdi mdi-trash-can"></i></button>';
        return [
            'id'        =>$this->id,
            'date'      =>'<a href="#!" data-url="'.route('circleMonthlyReports.updateCircleMonthlyReports',$this->id).'" onclick="showReport(this)">'.$this->date.'</a>',
            'tools'     =>$tools
        ];
    }
    public function circle(){
        return $this->belongsTo(Circle::class,'circle_id');
    }
    public function approved(){
        return $this->belongsTo(User::class,'approved_by','id')->select(['name']);
    }

    public function delivered(){
        return $this->belongsTo(User::class,'delivered_by','id')->select(['name']);
    }
    public function getStudentsAttribute(){
        return $this->circle ? $this->circle->students: [];
    }
    public function circleMonthlyReportStudents(){
        return $this->hasMany(CircleMonthlyReportStudent::class,'circle_monthly_report_id');
    }
    public function startReport($circlePrevMonthlyReport){
        foreach($this->students as $key => $student) {
            if ($circlePrevMonthlyReport) {
                $studentPrevReport = $circlePrevMonthlyReport->circleMonthlyReportStudents->where('student_id', $student->id)->first();
                if ($studentPrevReport) {
                    $current_book = CircleBooks::find($studentPrevReport->book_id);
                    $book_id = $studentPrevReport->book_id;
                    if ($studentPrevReport->current_to == $current_book->hadith_count) {
                        $newBook = CircleBooks::where('location', ($current_book->location + 1))->first();
                        if ($newBook) {
                            $book_id = $newBook->id;
                        }
                        $this->circleMonthlyReportStudents()->create([
                            'book_id' => $book_id,
                            'student_id' => $student->id,
                            'previous_from' => 0,
                            'previous_to' => 0,
                            'current_from' => 1,
                            'current_to' => 0
                        ]);
                    }else{
                        $this->circleMonthlyReportStudents()->create([
                            'book_id' => $book_id,
                            'student_id' => $student->id,
                            'previous_from' => $studentPrevReport->current_from,
                            'previous_to' => $studentPrevReport->current_to,
                            'current_from' => $studentPrevReport->current_to + 1,
                            'current_to' => 0
                        ]);
                    }
                } else {
                    // if student added to circle after its start
                    $book = CircleBooks::orderBy('location', 'ASC')->first();
                    $this->circleMonthlyReportStudents()->create([
                        'book_id' => $book->id,
                        'student_id' => $student->id,
                        'previous_from' => 0,
                        'previous_to' => 0,
                        'current_from' => 1,
                        'current_to' => 0
                    ]);
                }
            }else{
                // if circle is completely new
                $book = CircleBooks::orderBy('location', 'ASC')->first();
                $this->circleMonthlyReportStudents()->create([
                    'book_id' => $book->id,
                    'student_id' => $student->id,
                    'previous_from' => 0,
                    'previous_to' => 0,
                    'current_from' => 1,
                    'current_to' => 0
                ]);
            }
        }
    }
    /**
     * Scopes
     */
    public function scopeSearch($query,$searchWord)
    {
        return $query->where('id', 'like', "%" . $searchWord . "%")
            ->orWhere('date', 'like', "%" . $searchWord . "%")
            ->orWhereHas('circle',function($query) use ($searchWord){
                $query->search($searchWord);
            });
    }
    public function scopeTeacherCircles($query,$searchWord)
    {
        return $query->whereHas('circle',function($query) use ($searchWord){
//                dd($searchWord);
                $query->where('teacher_id',$searchWord);
            });
    }
    /**
     * Scopes
     */
    public function getHasNextReportAttribute(){
        $lastDay = Carbon::parse($this->date)->endOfMonth()->format('Y-m-d');
        $circleNextReportsCount = CircleMonthlyReport::where('circle_id', $this->circle_id)
            ->whereDate('date', '>=', $lastDay)->count();
        return $circleNextReportsCount ? true : false ;
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();
        static::deleting(function($circleMonthlyReport) {
            $circleMonthlyReport->circleMonthlyReportStudents()->delete();
        });
    }
}
