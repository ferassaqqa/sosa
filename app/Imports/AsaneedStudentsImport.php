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

class AsaneedStudentsImport implements ToModel,WithUpserts,WithValidation,WithHeadingRow,SkipsOnFailure,SkipsEmptyRows
{
    use Importable,SkipsFailures;

    private static $course;
    public function __construct($course)
    {
        Self::$course = $course;
    }

    public function model(array $row)
    {
//        dd((int)$row['rkm_alhoy']);
        $course = Self::$course;
        $id_num = (int)$row['rkm_alhoy'];
        if($course->teacher_id_num == $id_num){
            return response()->json(['view' => '', 'errors' => 1, 'msg' => ' لا يمكن اضافة المعلم '.$course->teacher_name.' كطالب في دورته '], 404);
        }else {
            $old_user = User::withoutGlobalScope('relatedUsers')->where('id_num', $id_num)->first();

            $user_validity_check = getGetDataFromIdentityNum($id_num);
            if($user_validity_check) {
                $user_interior_ministry_data = array_merge(getUserBasicData($user_validity_check), ['place_id' => $course->place_id]);
                if (!$old_user) {
//                    if (in_array($user_interior_ministry_data['student_category'], $course->student_categories)) {
                        $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num)], $user_interior_ministry_data);
                        $user = User::create($user_data);
//                        dd([
//                            'row'=>'
//                                        <tr>
//                                            <td></td>
//                                            <td style="text-align:right;">'. $user->name .'</td>
//                                            <td>'. $user->id_num .'</td>
//                                            <td>'. $user->dob .'</td>
//                                            <td>'. $user->pob .'</td>
//                                            <td>'. $user->student_category .'</td>
//                                            <td>'. (in_array($user->student_category,$course->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>').'</td>
//                                            '.(hasPermissionHelper('حذف طالب من دورة علمية') ?
//                                    '<td>'. $user->deleteCourseStudent($course->id) .'</td>' : '').'
//                                        </tr>'
//                        ]);
                        Auth::user()->sendFCM(
                            [
                                'title'=>'
                                        <tr>
                                            <td></td>
                                            <td style="text-align:right;">'. $user->name .'</td>
                                            <td>'. $user->id_num .'</td>
                                            <td>'. $user->dob .'</td>
                                            <td>'. $user->pob .'</td>
                                            <td>'. $user->student_category .'</td>
                                            <td>'. (in_array($user->student_category,$course->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>').'</td>
                                            '.(hasPermissionHelper('حذف طالب من دورة علمية') ?
                                                '<td>'. $user->deleteCourseStudent($course->id) .'</td>' : '').'
                                        </tr>'
                            ]
                        );
                        $user->assignRole('طالب دورات علمية');
                        // TODO: Implement model() method.
                        return new CourseStudent([
                            'user_id' => $user->id,
                            'course_id' => $course->id
                        ]);
//                    } else {
//                        //exclude
//                        $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num)], $user_interior_ministry_data);
//                        $user = User::create($user_data);
//                        $user->assignRole('طالب دورات علمية');
//                        $user->save();
//                        // TODO: Implement model() method.
//                        return new CourseStudent([
//                            'user_id' => $user->id,
//                            'course_id' => $course->id
//                        ]);
//                    }
                } else {
//                    if (!in_array($old_user->student_category, $course->student_categories)) {
                        //exclude
                        $user_data = array_merge(['password' => Hash::make($id_num)], $user_interior_ministry_data);
                        $old_user->update($user_data);

                        if (!$old_user->hasRole('طالب دورات علمية')) {
                            $old_user->assignRole('طالب دورات علمية');
                        }
                        Auth::user()->sendFCM(
                            [
                                'title'=>'
                                        <tr>
                                            <td></td>
                                            <td style="text-align:right;">'. $old_user->name .'</td>
                                            <td>'. $old_user->id_num .'</td>
                                            <td>'. $old_user->dob .'</td>
                                            <td>'. $old_user->pob .'</td>
                                            <td>'. $old_user->student_category .'</td>
                                            <td>'. (in_array($old_user->student_category,$course->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>').'</td>
                                            '.(hasPermissionHelper('حذف طالب من دورة علمية') ?
                                        '<td>'. $old_user->deleteCourseStudent($course->id) .'</td>' : '').'
                                        </tr>'
                            ]
                        );
                        $studentCourses = CourseStudent::where([
                            'user_id' => $old_user->id,
                            'course_id' => $course->id
                        ])->count();
//                dd($studentCourses);
                        if (!$studentCourses) {
                            // TODO: Implement model() method.
                            return new CourseStudent([
                                'user_id' => $old_user->id,
                                'course_id' => $course->id
                            ]);
                        }
//                    } else {
//                        if (!$old_user->hasRole('طالب دورات علمية')) {
//                            $old_user->assignRole('طالب دورات علمية');
//                        }
//                        $studentCourses = CourseStudent::where([
//                            'user_id' => $old_user->id,
//                            'course_id' => $course->id
//                        ])->count();
////                dd($studentCourses);
//                        if (!$studentCourses) {
//                            // TODO: Implement model() method.
//                            return new CourseStudent([
//                                'user_id' => $old_user->id,
//                                'course_id' => $course->id
//                            ]);
//                        }
//                    }
                }
            }else{

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
            'rkm_alhoy' => 'required|numeric|is_id_valid'//|can_exclude_student:'.SELF::$course->id,
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
