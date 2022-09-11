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
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;


use Throwable;

class CourseStudentsImport implements
    ToModel,
    WithValidation,
    WithHeadingRow,
    SkipsOnFailure,
    SkipsEmptyRows,
    SkipsOnError,
    WithBatchInserts,
    WithChunkReading

{
    use Importable, SkipsFailures, SkipsErrors, RegistersEventListeners;

    private static $course;
    public function __construct($course)
    {
        Self::$course = $course;
    }

    // public function onError(Throwable $e)
    // {
    //         dd($e);
    // }
    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }

    // public static function afterImport(AfterImport $event){
    //     // here we can send the notifications to the users

    // }

    // public function onFailure(Failure ...$failure)
    // {
    //     dd($failure);
    // }

    public function model(array $row)
    {

        $course = Self::$course;
        $id_num = (int)$row['rkm_alhoy'];

        $old_user = User::where('id_num', $id_num)->first();

        if ($old_user) {

            if (!$old_user->hasRole('طالب دورات علمية')) {
                $old_user->assignRole('طالب دورات علمية');
            }

            $studentCourses = CourseStudent::where(['user_id' => $old_user->id,'course_id' => $course->id])->first();
            if(!$studentCourses){
                CourseStudent::create([
                    'user_id' => $old_user->id,
                    'course_id' => $course->id
                ]);
            }

        } else {  //empty old user

            $user_validity_check = getGetDataFromIdentityNum($id_num);

            if ($user_validity_check) {
                $user_interior_ministry_data = array_merge(getUserBasicData($user_validity_check), ['place_id' => $course->place_id]);
                if (!$old_user) {
                    $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num)], $user_interior_ministry_data);
                    $user = User::create($user_data);

                    $user->assignRole('طالب دورات علمية');

                    return new CourseStudent([
                        'user_id' => $user->id,
                        'course_id' => $course->id
                    ]);
                }
            }
        }
    }
    // public function uniqueBy()
    // {
    //     return 'rkm_alhoy';
    // }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // '*.rkm_alhoy' => 'required|numeric|unique:users,id_num|not_teacher:'.SELF::$course->id,
            '*.rkm_alhoy' => 'required|numeric|not_teacher:' . SELF::$course->id,
        ];
    }
    public function customValidationMessages()
    {
        return [
            'rkm_alhoy.required' => 'رقم الهوية مطلوب',
            'rkm_alhoy.numeric' => 'رقم الهوية من نوع عدد',
            'rkm_alhoy.unique' => 'رقم هوية الطالب مسجله من قبل',
        ];
    }
}
