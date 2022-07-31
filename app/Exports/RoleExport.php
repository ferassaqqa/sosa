<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Spatie\Permission\Models\Role;

class RoleExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{

    private $search;
    private $isDeleted;
    private $page;
    private $ids;

    public function __construct($search,$isDeleted,$page,$ids)
    {
        $this->search = $search;
        $this->isDeleted = $isDeleted;
        $this->page = $page;
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $roles = [];
//        dd($this->search,$this->isDeleted,$this->page,$this->ids);
        if($this->page == 'all'){
            if(!empty($this->search)) {
                if($this->isDeleted){
                    $roles = Role::select('id','name')
                        ->onlyTrashed()
                        ->where(function ($query){
                            $query->where('id', 'like', "%" . $this->search . "%")
                                ->orWhere('name', 'like', "%" . $this->search . "%");
                        })
                        ->get();
                }else {
                    $roles = Role::select('id', 'name')
                        ->where('id', 'like', "%" . $this->search . "%")
                        ->orWhere('name', 'like', "%" . $this->search . "%")
                        ->get();
                }
            }else{
                if($this->isDeleted){
                    $roles = Role::select('id', 'name')
                        ->onlyTrashed()
                        ->get();
                }else {
                    $roles = Role::select('id', 'name')
                        ->get();
                }
            }
        }else{
            if(!empty($this->ids)) {
                $ids = explode(',',$this->ids);
//                dd($ids);
                $roles = Role::select('id', 'name')
                    ->onlyTrashed()
                    ->whereIn('id', $ids)
                    ->get();
            }
        }
        return $roles;
    }
    public function headings(): array
    {
        return [
            '#',
            'الدور'
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
}
