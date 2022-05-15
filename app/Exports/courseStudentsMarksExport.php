<?php

namespace App\Exports;


use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Matrix\Exception;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class courseStudentsMarksExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    use RegistersEventListeners;

    public $course_students;
    public $course_students_count;
    public $course;
    public function __construct($course)
    {
        $this->course_students = $course->manyStudentsForPermissions;
        $this->course = $course;
        $this->course_students_count = $this->course_students->count();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $collection = new Collection();
        $i = 1;
        $year = Carbon::now()->format('Y');
        $area = Area::find($this->course->area_father_id_for_permissions);
//        dd($area);
        $j = $area->student_marks_export_count ? $area->student_marks_export_count : 1;
        foreach($this->course_students as $key => $course_student){
            if($course_student->mark >= 60) {
                $collection[$key] = [
                    $j . '/' . $year . '/' . $this->course->father_area_abbreviation,
                    $i,
                    $course_student->user_name,
                    $course_student->user_dob,
                    $course_student->user_pob,
                    $this->course->start_date,
                    Carbon::parse($this->course->updated_at)->format('Y-m-d'),
                    $this->course->book_name,
                    strip_tags($this->course->book_students_hours_count),
                    $this->course->teacher_name,
                    $course_student ? $course_student->mark : 'لا يرجد',
                    $course_student ? strip_tags(markEstimation($course_student->mark)) : 'لا يوجد',
                ];
                $i++;
                $j++;
                if ($i == $this->course_students->count()) {
//                dd($i == $this->course_students->count());
                    if ($area) {
                        if ($year == $area->student_marks_export_year) {
                            $student_marks_export_count = ((int)$area->student_marks_export_count + $i);
                        } else {
                            $student_marks_export_count = $i;
                        }
                        $area->update(['student_marks_export_count' => $student_marks_export_count, 'student_marks_export_year' => $year]);
//                    dd(Area::find($this->course->area_father_id_for_permissions)->toArray(),$student_marks_export_count);
                    }
                }
            }
        }
//        dd($collection);
        return $collection;
    }

    public function headings(): array
    {
        return [
            'المسلسل',
            'م',
            'اسم الطالب',
            'تاريخ الميلاد',
            'مكان الميلاد',
            'تاريخ بداية الدورة',
            'تاريخ نهاية الدورة',
            'اسم الدورة',
            'عدد الساعات',
            'اسم المدرس',
            'الدرجة من 100',
            'التقدير',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        return [
            // Style the first row as bold text.
            'A1:Z1'    => [
                'font' => ['bold' => true],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000'],
                    ],
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000'],
                    ],
                ],
                'alignment' => [
                    'horizontal'=>'center',
                    'vertical'=>'center'
                ]
            ],
            'A2:Z'.($this->course_students_count+1)    => [
                'font' => ['name' => ''],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000'],
                    ],
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000'],
                    ],
                ],
                'alignment' => [
                    'horizontal'=>'center',
                    'vertical'=>'center'
                ]
            ]
        ];
    }
//    public static function afterSheet(AfterSheet $event)
//    {
//        foreach(Self::$faults as $key => $failure){
////            $user = User::where('id_num',$failure->values()['rkm_alhoy'])->first();
//            try {
////                if($user) {
//////                    $event->sheet
//////                        ->getDelegate()
//////                        ->setHyperlink('B' . $failure->row(),new Hyperlink(route('roles.restoreItemFromExcel',$role->id)));
////                    $event->sheet
////                        ->getDelegate()
////                        ->getComment('B' . $failure->row())
////                        ->getText()
////                        ->createTextRun($failure->errors()[0].'، البيانات المتشابهة محذوفة.');
////                }else{
//                $event->sheet
//                    ->getDelegate()
//                    ->getComment('B' . $failure->row())
//                    ->getText()
//                    ->createTextRun($failure->errors()[0]);
////                }
//            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
//
//            }
//        }
//    }
}
