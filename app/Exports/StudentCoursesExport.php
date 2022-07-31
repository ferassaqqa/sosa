<?php

namespace App\Exports;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentCoursesExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    use RegistersEventListeners;


    public $user;

    public $users_count;
    public function __construct($user)
    {


        $this->user = $user;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $collection = new Collection();


    $courses = isset($passed) ? $this->user->passedStudentCourses : ( isset($failed) ? $this->user->failedStudentCourses : $this->user->studentCourses);




        foreach($courses as $key => $course){
            $collection[$key] = [
                ($key+1),
                $course->book_name,
                $course->name,
                $course->place_full_name,
                $course->sub_area_supervisor_name,
                $course->area_supervisor_name,
                $course->book->hours_count,

                $course->exam ? ($course->exam->status == 5 ? ($course->pivot->mark ? $course->pivot->mark : 'لم يتم رصد الدرجات') : 'انتظار اعتماد الدرجات' ): 'لم يختبر بعد',
                $course->exam->status == 5 ? ($course->pivot->mark ? markEstimation($course->pivot->mark) : '-'):'-',

                $course->exam->status == 5 ? 'تم الاستلام' : 'لم يتم الاستلام',
                $course->exam->status == 5 ? 'طباعة شهادة' : '-',


            ];
        }
        return $collection;
    }

    public function headings(): array
    {
        return [
            'م',
            'اسم الدورة',
            'اسم المعلم',
            'العنوان',
            'المشرف الميداني',
            'المشرف العام',

            'عدد الساعات',
            'الدرجة',
            'التقدير',
            'استلام الشهادة',
            'أدوات',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        return [
            // Style the first row as bold text.
            'A1:F1'    => [
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
            'A2:F'.($this->users_count+1)    => [
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
