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

class exportMoallemsAsExcelSheet implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    use RegistersEventListeners;
    public $sub_area_id;
    public $area_id;
    public $search;
    public $users_count;
    public function __construct($sub_area_id,$area_id,$search)
    {
        $this->search = !empty($search) ? $search : '';
        $this->sub_area_id = !empty($sub_area_id) ? $sub_area_id : '';
        $this->area_id = !empty($area_id) ? $area_id : '';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $collection = new Collection();

        $users = User::subarea($this->sub_area_id,$this->area_id)
            ->search($this->search)
            ->department(2)
            ->get();
        $this->users_count = $users->count();
        foreach($users as $key => $user){
            $collection[$key] = [
                ($key+1),
                $user->name,
                $user->id_num,
                $user->mobile,
                $user->area_name,
                $user->place_name,
            ];
        }
        return $collection;
    }

    public function headings(): array
    {
        return [
            'م',
            'الإسم',
            'رقم الهوية',
            'رقم الجوال',
            'المحلية',
            'المسجد',
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
