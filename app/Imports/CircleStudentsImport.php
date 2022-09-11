<?php

namespace App\Imports;


use App\Models\CourseStudent;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class CircleStudentsImport implements ToModel, WithUpserts, WithValidation, WithHeadingRow, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    private static $circle;
    public function __construct($circle)
    {
        Self::$circle = $circle;
    }

    public function model(array $row)
    {


        $circle = Self::$circle;
        $id_num = (int)$row['rkm_alhoy'];
        if ($circle->teacher_id_num == $id_num) {
            return response()->json(['view' => '', 'errors' => 1, 'msg' => ' لا يمكن اضافة المعلم ' . $circle->teacher_name . ' كطالب في دورته '], 404);
        } else {
            $old_user = User::withoutGlobalScope('relatedUsers')->where('id_num', $id_num)->first();


            if ($old_user) {

                // $user_data = array_merge(['password' => Hash::make($id_num),'teacher_id' =>$circle->teacher_id], $user_interior_ministry_data);
                // $old_user->update($user_data);

                if ($old_user->userExtraData) {
                    $old_user->userExtraData->update(['mobile' => $id_num]);
                } else {
                    $old_user->userExtraData()->create(['mobile' => $id_num]);
                }

                if (!$old_user->hasRole('طالب تحفيظ')) {
                    $old_user->assignRole('طالب تحفيظ');
                }
                // Auth::user()->sendFCM(
                //     [
                //         'title' => '
                //                 <tr>
                //                     <td></td>
                //                     <td style="text-align:right;">' . $old_user->name . '</td>
                //                     <td>' . $old_user->id_num . '</td>
                //                     <td>' . $old_user->dob . '</td>
                //                     <td>' . $old_user->pob . '</td>

                //                 </tr>'
                //     ]
                // );
            } else { // empty old_user



                $user_validity_check = getGetDataFromIdentityNum($id_num);
                if ($user_validity_check) {

                    $user_interior_ministry_data = array_merge(getUserBasicData($user_validity_check), ['place_id' => $circle->place_id]);
                    $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num), 'teacher_id' => $circle->teacher_id], $user_interior_ministry_data);
                    $user = User::create($user_data);


                    if ($user->userExtraData) {
                        $user->userExtraData->update(['mobile' => $id_num]);
                    } else {
                        $user->userExtraData()->create(['mobile' => $id_num]);
                    }

                    // Auth::user()->sendFCM(
                    //     [
                    //         'title' => '
                    //                     <tr>
                    //                         <td></td>
                    //                         <td style="text-align:right;">' . $user->name . '</td>
                    //                         <td>' . $user->id_num . '</td>
                    //                         <td>' . $user->dob . '</td>
                    //                         <td>' . $user->pob . '</td>
                    //                     </tr>'
                    //     ]
                    // );
                    $user->assignRole('طالب تحفيظ');
                }
            }
        }
    }
    public function uniqueBy()
    {
        return 'rkm_alhoy';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'rkm_alhoy' => 'required|numeric|is_id_valid' //|can_exclude_student:'.SELF::$course->id,
            //            'almsgd' => 'required|string|exists:places,name',
            //            'rkm_algoal' => 'required|numeric',
        ];
    }
    public function customValidationMessages()
    {
        return [
            'rkm_alhoy.required' => 'رقم الهوية مطلوب',
            'rkm_alhoy.numeric' => 'رقم الهوية من نوع عدد',
            'almsgd.required' => 'المسجد مطلوب',
            'almsgd.string' => 'المسجد من نوع نص',
            'almsgd.exists' => 'المسجد غير موجود في المساجد',
            'rkm_algoal.required' => 'رقم الجوال مطلوب',
            'rkm_algoal.numeric' => 'رقم الجوال من نوع عدد',
        ];
    }
}
