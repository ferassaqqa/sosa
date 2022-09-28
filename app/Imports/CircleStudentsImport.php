<?php

namespace App\Imports;


use App\Models\CircleStudent;
use App\Models\Exam;
use App\Models\User;
use App\Models\CircleMonthlyReport;
use App\Models\CircleMonthlyReportStudent;
use App\Models\CircleBooks;
use Illuminate\Support\Facades\DB;



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


use Illuminate\Support\Facades\Validator;


use Throwable;

class CircleStudentsImport implements
    ToModel,
    WithValidation,
    WithHeadingRow,
    SkipsOnFailure,
    SkipsEmptyRows,
    // SkipsOnError,
    WithBatchInserts,
    WithEvents

{
    use Importable, SkipsFailures,  RegistersEventListeners;


    public static $counter = 0;
    private $errors = [];
    private static $circle;
    public function __construct($circle)
    {
        Self::$circle = $circle;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }



    public function model(array $row)
    {
        self::$counter++;


        $validator = Validator::make($row, $this->rules(), $this->validationMessages());
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    // accumulating errors:
                    $this->errors[] = $error;
                }
            }
        }

        $circle = Self::$circle;
        $id_num = (int)$row['rkm_alhoy'];
        $hadith_count = (int)$row['aadd_alahadyth'];

        // $hadith_count = 950;




        $old_user = User::where('id_num', $id_num)->first();

        if ($old_user) {

            if (!$old_user->hasRole('طالب دورات علمية')) {
                $old_user->assignRole('طالب دورات علمية');
            }

    
            $circleReports = CircleMonthlyReport::where('circle_id', $circle->id)->whereMonth('date', date('m'))->first();

            $current_book_id = 0;

            $result = DB::table('circle_books')
                ->select('*', DB::raw("ABS(to_hadith_count - " . $hadith_count . ") AS distance"))
                ->orderBy('distance')
                ->get();

            foreach ($result as $key => $book) {
                if ($book->to_hadith_count >= $hadith_count) {
                    $current_book_id = $book->id;
                    break;
                }
            }


            $circleBooks = CircleBooks::where('id', $current_book_id)->first();

            $previous_hadith_count = 0;

            if ($circleBooks->location > 0) {
                $previousCircleBooks = CircleBooks::where('location', $circleBooks->location - 1)->first();
                $previous_hadith_count = $previousCircleBooks->to_hadith_count;
            }


            $student = CircleMonthlyReportStudent::create([
                'circle_monthly_report_id' => $circleReports->id,
                'student_id' => $old_user->id,
                'book_id' => $current_book_id,
                'previous_from' => 0,
                'previous_to' => $previous_hadith_count,
                'current_from' => 0,
                'current_to' => $hadith_count - $previous_hadith_count

            ]);
            $student->save();
        } else {  //empty old user

            $user_validity_check = getGetDataFromIdentityNum($id_num);

            if ($user_validity_check) {
                $user_interior_ministry_data = array_merge(getUserBasicData($user_validity_check), ['place_id' => $circle->place_id]);
                if (!$old_user) {
                    $user_data = array_merge(['id_num' => $id_num, 'password' => Hash::make($id_num)], $user_interior_ministry_data);
                    $user = User::create($user_data);

                    $user->assignRole('طالب دورات علمية');

                    // return new CircleStudent([
                    //     'student_id' => $user->id,
                    //     'circle_id' => $circle->id
                    // ]);

                    $circleReports = CircleMonthlyReport::where('circle_id', $circle->id)->get();
                    $circleBooks = CircleBooks::orderBy('location', 'desc')->get();
                    foreach ($circleReports as $key => $report) {
                        foreach ($circleBooks as $key => $book) {
                            $student = CircleMonthlyReportStudent::create([
                                'circle_monthly_report_id' => $report->id,
                                'student_id' => $user->id,
                                'book_id' => $book->id,
                                'previous_from' => 0,
                                'previous_to' => 0,
                                'current_from' => 0,
                                'current_to' => 0

                            ]);
                            $student->save();
                        }
                    }
                }
            } else {
            }
        }
    }



    public static function afterImport(AfterImport $event)
    {

        $students_count = SELF::$circle->students->count();

        if ($students_count > 10) {
            SELF::$circle->update(['status' => 'قائمة']);
        }
    }



    // this function returns all validation errors after import:
    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            '*.rkm_alhoy' => 'required|numeric|not_teacher_circle:' . SELF::$circle->id,
        ];
    }

    public function validationMessages()
    {
        return [
            'rkm_alhoy.required' => 'رقم الهوية مطلوب',
            'rkm_alhoy.numeric' => 'رقم الهوية من نوع عدد',
            'rkm_alhoy.unique' => 'رقم هوية الطالب مسجله من قبل',
        ];
    }
}
