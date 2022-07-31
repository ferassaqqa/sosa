<?php

namespace App\Imports;

use App\Exports\MoallemImporterFaulties;
use App\Exports\RoleFaultsExport;
use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Models\Role;

class MoallemImporter implements ToCollection,SkipsOnFailure,SkipsEmptyRows,WithHeadingRow//,WithUpserts,WithValidation
{
    use Importable,SkipsFailures;
    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public $excel;
    public function __construct($excel)
    {
        $this->excel = $excel;
    }

    public function collection(Collection $rows)
    {
        // TODO: Implement model() method.

//                    dd($rows);
//        $faults = [];
        foreach ($rows as $row) {
//            dd($row);
            $user = User::where('id_num', $row['rkm_alhoy'])->first();
            if ($user) {
                $user->assignRole('محفظ');
            }else{
                var_dump($row['rkm_alhoy']);
            }
////                array_push($faults,$user->toArray());
//
//                if($user->hasRole('معلم')) {
//                    $row['reason'] = implode("-",$user->getRoleNames()->toArray());
//                    array_push($faults, $row);
//                }
//            } else {
//                $user = new User();
//                $user->id_num = $row['rkm_alhoy'];
//                if ($user->user_basic_data) {
//                    $area = Area::whereNUll('area_id')->where('name', $row['almntk_alkbr'])->first();
//                    if ($area) {
//                        $sub_area = Area::where('area_id', $area->id)->first();
//                        if ($sub_area) {
//                            if($sub_area->places->count()) {
//                                $user->place_id = $sub_area->places[0]->id;
//                                $user->sons_count = isset($row['aadd_alabnaaa']) ? $row['aadd_alabnaaa'] : 0;
//                                $user->password = Hash::make($user->id_num);
//                                $user->save();
//                                $user->assignRole('محفظ');
//                                $user->userExtraData()->create(['mobile' => isset($row['rkm_algoal']) ? $row['rkm_algoal'] : 0]);
//                            }else{
//                                $row['reason'] = 'لا يوجد أماكن في المنطقة المحلية';
//                                array_push($faults,$row);
//                            }
//
//                        }else{
//                            $row['reason'] = 'مشكلة في المنطقة المحلية';
//                            array_push($faults,$row);
//                        }
//                    }else{
//                        $row['reason'] = 'مشكلة في المنطقة الكبرى';
//                        array_push($faults,$row);
//                    }
//                }else{
//                    $row['reason'] = 'خطأ رقم الهوية';
//                    array_push($faults,$row);
//                }
//            }
        }
//        dd($faults);

//        $this->excel->store(
//            new MoallemImporterFaulties($faults)
//            , 'public/MoallemImporterFaulties.xlsx');
    }
//    public function uniqueBy()
//    {
//        return 'name';
//    }

//    /**
//     * @return array
//     */
//    public function rules(): array
//    {
//        // TODO: Implement rules() method.
//        return [
//            'aldor' => 'required|string|unique:roles,name',
//        ];
//    }
//    public function customValidationMessages()
//    {
//        return [
//            'aldor.required' => 'اسم الدور مطلوب',
//            'aldor.string' => 'اسم الدور من نوع نص',
//            'aldor.unique' => 'اسم الدور يجب ان يكون فريد',
//        ];
//    }

}
