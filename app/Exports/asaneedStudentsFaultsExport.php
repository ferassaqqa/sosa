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
use Maatwebsite\Excel\Events\AfterSheet;
use Matrix\Exception;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsaneedStudentsFaultsExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    use RegistersEventListeners;
    private static $faults;
    public function __construct($faults)
    {
        Self::$faults = $faults;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collection = new Collection();
        foreach(Self::$faults as $key => $failure){
//            dd($failure);
            $collection[$key] = array_merge([
                '#'=>$failure->row()
            ],$failure->values());
        }
//        dd($collection);
        return $collection;
    }
    public function headings(): array
    {
        return [
            '#',
            'رقم الهوية',
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
            'A2:Z100'    => [
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
    public static function afterSheet(AfterSheet $event)
    {
        foreach(Self::$faults as $key => $failure){
//            $user = User::where('id_num',$failure->values()['rkm_alhoy'])->first();
            try {
//                if($user) {
////                    $event->sheet
////                        ->getDelegate()
////                        ->setHyperlink('B' . $failure->row(),new Hyperlink(route('roles.restoreItemFromExcel',$role->id)));
//                    $event->sheet
//                        ->getDelegate()
//                        ->getComment('B' . $failure->row())
//                        ->getText()
//                        ->createTextRun($failure->errors()[0].'، البيانات المتشابهة محذوفة.');
//                }else{
                $event->sheet
                    ->getDelegate()
                    ->getComment('B' .($key+2))
                    ->getText()
                    ->createTextRun($failure->errors()[0]);
//                }
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {

            }
        }
    }
}
