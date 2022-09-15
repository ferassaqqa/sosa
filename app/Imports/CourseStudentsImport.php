<?php

namespace App\Imports;


use App\Models\CourseStudent;
use App\Models\Exam;
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
use App\Http\Requests\controlPanel\Roles\ImportExcelRequest;
use Maatwebsite\Excel\Events\AfterSheet;


use Throwable;

class CourseStudentsImport implements
    ToModel,
    WithValidation,
    WithHeadingRow,
    SkipsOnFailure,
    SkipsEmptyRows,
    SkipsOnError,
    WithBatchInserts,
    WithEvents

{
    use Importable, SkipsFailures, SkipsErrors, RegistersEventListeners;

    private static $course;
    public function __construct($course)
    {
        Self::$course = $course;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }

    // public function registerEvents(): array
    // {

    //     // ,ImportExcelRequest $request
    //     return [
    //         // Handle by a closure.
    //         AfterImport::class => function(AfterImport $event) {
    //             // $creator = $event->reader->getProperties()->getCreator();

    //         //  dd($event);

    //         // $course = Self::$course;

    //         // $students_count = $course->students->count();
    //         // if($students_count < 10 ){
    //         //     return response()->json(['status'=>'info','msg'=>'<span>
    //         //                 تم استيراد ملف الدورة بنجاح. يرجى العلم بان الحد الادنى لحجز موعد اختيار هو 10 طلاب للدورة الواحدة</span> ']);
    //         // }else{
    //         // $course->update(['status'=>'قائمة']);
    //         // $has_exam = Exam::where('examable_id', $course->id )->exists();
    //         // // if(!$has_exam){$course->exam()->create($request->all());}

    //         // return response()->json(['status'=>'success','msg'=>'تم استيراد ملف دورة '. $course->book_name . ' للمعلم ' . $course->name.' بنجاح.']);
    //         // }

    //         },

    //     ];
    // }

    // public static function afterSheet(AfterSheet $event)
    // {
    //     // app('log')->info('NewsPost import finished');
    //         $course = Self::$course;

    //         $students_count = $course->students->count();

    //         dd($students_count);
    //         if($students_count < 10 ){
    //             return response()->json(['status'=>'info','msg'=>'<span>
    //                         تم استيراد ملف الدورة بنجاح. يرجى العلم بان الحد الادنى لحجز موعد اختيار هو 10 طلاب للدورة الواحدة</span> ']);
    //         }else{
    //         $course->update(['status'=>'قائمة']);
    //         $has_exam = Exam::where('examable_id', $course->id )->exists();
    //         // if(!$has_exam){$course->exam()->create($request->all());}

    //         return response()->json(['status'=>'success','msg'=>'تم استيراد ملف دورة '. $course->book_name . ' للمعلم ' . $course->name.' بنجاح.']);
    //         }
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

    // public static function afterImport(AfterImport $event)
    // {
    //     // Need to access $finaldata here and send mail to user
    //     // Log::info('after import excel file');
    //     $importer = $event->getConcernable(); // This class
    // $import_id = $importer->import_id; // Access class properties

    //     dd($import_id);
    // }


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // '*.rkm_alhoy' => 'required|numeric|not_teacher:' . SELF::$course->id.'|is_id_valid:' . SELF::$course->id,
            '*.rkm_alhoy' => 'required|numeric|not_teacher:' . SELF::$course->id.'|is_id_valid:' . SELF::$course->id,

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
