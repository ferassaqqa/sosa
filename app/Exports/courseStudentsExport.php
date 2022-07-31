<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\CourseStudent;
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

class courseStudentsExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    use RegistersEventListeners;

    public $course_students_count;
    public $course;
    public function __construct($course)
    {
        $this->course = $course;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $collection = new Collection();
        $course_students = CourseStudent::where('course_id',$this->course->id)->get();
        foreach($course_students as $key => $course_student){
            $collection[$key] = [
                $course_student->user_name,
                $course_student->user_id_num,
            ];
        }
        return $collection;
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'رقم الهوية',
            'الدرجة'
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
}
