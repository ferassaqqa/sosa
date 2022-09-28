<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleMonthlyReportStudent extends Model
{
    use HasFactory;
    protected $fillable = ['circle_monthly_report_id','book_id','student_id','previous_from','previous_to','current_from','current_to'];

    public function book(){
        return $this->belongsTo(CircleBooks::class);
    }

    public function getBookNameAttribute(){
        return $this->book ? $this->book->name : '';
    }

    public function circleMonthlyReport(){
        return $this->belongsTo(CircleMonthlyReport::class,'circle_monthly_report_id');
    }

    public function getReportMonthAttribute(){
        return $this->circleMonthlyReport ? $this->circleMonthlyReport->date : 0;
    }

    public function getCircleIdAttribute(){
        return $this->circleMonthlyReport ? $this->circleMonthlyReport->circle_id : 0;
    }

    public function student(){
        return $this->belongsTo(User::class,'student_id');
    }

    public function getStudentNameAttribute(){
        return $this->student ? $this->student->name : '';
    }
    // public function getCurrentStorageAttribute(){
    //     return ($this->current_to ? $this->current_to : 0) - ($this->current_from ? $this->current_from : 0 );
    // }
    public function getCurrentStorageAttribute(){
        return $this->previous_to + $this->current_to;
    }
    public function getCurrentCategoryAttribute(){
//        dd($this);
        $bookCategory = BookCategory::where('from','<',($this->current_from+1))->where('to','>=',$this->current_to)->first();
        return $bookCategory ? $bookCategory->to : 'لا يوجد';
    }
    public function getPreviousCategoryAttribute(){
        $bookCategory = BookCategory::where('from','<',($this->previous_from+1))->where('to','>=',$this->previous_to)->first();
        return $bookCategory ? $bookCategory->to : 'لا يوجد';
    }
    public function getStudentProgressPercentageAttribute(){
        $circle_id = $this->circle_id;

        $date = Carbon::parse($this->report_month)->format('Y-m-d');
        $yearFirstDay = Carbon::parse($date)->startOfYear()->format('Y-m-d');
        $monthLastDay = Carbon::parse($date)->endOfMonth()->format('Y-m-d');
//        dd($yearFirstDay,$monthLastDay);
        $circleMonthlyReports = CircleMonthlyReport::where('circle_id', $circle_id)->whereDate('date', '<=', $monthLastDay)->whereDate('date', '>=', $yearFirstDay)->get();
        $studentTotalCurrentProgress = 0;
//        dd($circleMonthlyReports);
        foreach ($circleMonthlyReports as $key => $circleMonthlyReport) {
            $studentMonthlyReport = $circleMonthlyReport->circleMonthlyReportStudents->where('student_id', $this->student_id)->first();
            $studentTotalCurrentProgress += $studentMonthlyReport ? $studentMonthlyReport->current_storage : 0;
        }
        $circleBookPlan = CirclePlan::where('book_id',$this->book_id)->where('year',Carbon::parse($this->date)->year)->first();
//        dd($circleMonthlyReports,$monthLastDay,$yearFirstDay);
        return $circleBookPlan->guaranteed_yearly ?  round(((int)$studentTotalCurrentProgress*100)/(int)$circleBookPlan->guaranteed_yearly, 2) : 0;
    }
    public function getRowAttribute(){
        return
            '<tr>
                <td>#</td>
                <td>'.$this->student_name.'</td>
                <td>'.$this->book_name.'</td>
                <td>'.$this->previous_from.'</td>
                <td>'.$this->previous_to.'</td>
                <td>'.$this->current_from.'</td>
                <td><input type="number" class="form-control" step="1" min="'.$this->current_from.'" value="'.$this->current_to.'" onchange="changeCurrentToValue(this,'.$this->id.')"/></td>
                <td>'.$this->previous_category.'</td>
                <td class="current_category">'.$this->current_category.'</td>
                <td class="current_storage">'.($this->current_storage > 0 ? $this->current_storage : 0).'</td>
                <td class="current_percentage">'.($this->student_progress_percentage > 0 ? $this->student_progress_percentage : 0).'</td>
            </tr>';
    }
    public function getPrintedRowAttribute(){
        return
            '<tr>
                <td>#</td>
                <td>'.$this->student_name.'</td>
                <td>'.$this->book_name.'</td>
                <td>'.$this->previous_from.'</td>
                <td>'.$this->previous_to.'</td>
                <td>'.$this->current_from.'</td>
                <td>'.$this->current_to.'</td>
                <td>'.$this->previous_category.'</td>
                <td>'.$this->current_category.'</td>
                <td>'.$this->current_storage.'</td>
                <td>'.$this->student_progress_percentage.'</td>
            </tr>';
    }
}
