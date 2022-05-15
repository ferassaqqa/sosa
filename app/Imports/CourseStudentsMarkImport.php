<?php

namespace App\Imports;

use App\Models\CourseStudent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class CourseStudentsMarkImport implements ToCollection,WithHeadingRow,SkipsEmptyRows
{
    use Importable;

    private static $exam;
    public function __construct($exam)
    {
        Self::$exam = $exam;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $exam = Self::$exam;
        if($collection->count()) {
            foreach ($collection as $value) {
                $alaalam = isset($value['aldrg']) ? $value['aldrg'] : '';
                if($alaalam) {
                    $studentCourseRecord = CourseStudent::where('course_id', $exam->examable_id)
                        ->where('course_id', $exam->examable_id)
                        ->whereHas('user', function ($query) use ($value,$alaalam) {
                            $alasm = isset($value['alasm']) ? $value['alasm'] : '';
                            $id_num = isset($value['rkm_alhoy']) ? $value['rkm_alhoy'] : '';
                            $query->where(function ($q) use ( $alasm, $id_num) {
                                $q->where('id_num', $id_num)
                                    ->orWhere('name', 'like', '%' . $alasm . '%');
                            });
                        })
                        ->withoutGlobalScope('relatedCourseStudents')
                        ->first();
//                    var_dump($studentCourseRecord);
                    if($studentCourseRecord) {
//                        echo '<pre>';var_dump($studentCourseRecord->toArray());echo '</pre>';
                        $studentCourseRecord->update(['mark' => $alaalam]);
                    }
                }else{
                    continue;
                }
            }
        }
    }
    public function model(array $row)
    {
        $exam = Self::$exam;
        $tarykh_almylad = $row['tarykh_almylad'];
        $alasm = $row['alasm'];
        $id_num = $row['rkm_alhoy'];
        $alaalam = $row['alaalam'];

    }

}
