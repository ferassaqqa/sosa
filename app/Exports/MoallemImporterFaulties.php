<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MoallemImporterFaulties implements FromCollection,WithHeadings,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private static $faults;
    public function __construct($faults)
    {
        Self::$faults = $faults;
    }

    public function collection()
    {
        $collection = new Collection();
        foreach(Self::$faults as $key => $failure){
            $collection[$key] = $failure;
        }
        return $collection;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
    }
    public function headings(): array
    {

        return [
            'الإسم رباعي',
            'تاريخ الميلاد',
            'مكان الميلاد',
            'رقم الهوية / الوثيقة',
            'الحالة الاجتماعية',
            'عدد الأبناء',
            'الفرع',
            'المنطقة الفرعية',
            'العنوان',
            'المسجد',
            'رقم الجوال',
            'هاتف المنزل',
            'البريد الإلكتروني',
            'فيسبوك',
            'الدرجة العلمية',
            'الكلية',
            'التخصص',
            'المهنة',
            'مكان العمل',
            'مستوى الدخل الشهري',
            'تاريخ بداية العمل',
            'نوع العقد',
            'قيمة الكفالة',
            'المشكلة',
        ];
    }
}
