<?php

namespace App\Imports;

use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseNewStudentsMarkImport implements ToCollection,WithHeadingRow,SkipsEmptyRows
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
        $course = $exam->course;
        if($collection->count()) {
            foreach ($collection as $value) {
                $id_num = isset($value['rkm_alhoy']) ? $value['rkm_alhoy'] : '';
                $alaalam = isset($value['aldrg']) ? $value['aldrg'] : '';
                $old_user = User::withoutGlobalScope('relatedUsers')->where('id_num', $id_num)->first();
                if($old_user){
                    continue;
                }
                $user_validity_check = getGetDataFromIdentityNum($id_num);
                if($user_validity_check) {
                    $user_interior_ministry_data = array_merge(getUserBasicData($user_validity_check), ['place_id' => $course->place_id]);
                    if (in_array($user_interior_ministry_data['student_category'], $course->student_categories)) {
                        if($alaalam) {
                            $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num)], $user_interior_ministry_data);
                            $user = User::create($user_data);
                            $user->assignRole('طالب دورات علمية');
                            CourseStudent::create([
                                'user_id' => $user->id,
                                'course_id' => $course->id,
                                'mark' => $alaalam
                            ]);
                        }else{
                            continue;
                        }
                    }else{
                        continue;
                    }
                }else{
                    continue;
                }
            }
        }
    }
}
