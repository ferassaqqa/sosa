<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\books\newBookRequest;
use App\Http\Requests\controlPanel\books\updateBookRequest;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\CourseBookCategory;
use App\Models\CourseBookPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($department)
    {
        switch ($department){
            case 0 : {$department_name =  'جميع الكتب';}break;
            case 1 : {$department_name =  'كتب التحفيظ';}break;
            case 2 : {$department_name =  'كتب الدورات العلمية وتصنيفها';}break;
            case 3 : {$department_name =  'الطلاب';}break;
        }
        $courseBookCategories = CourseBookCategory::whereHas('books')->get();
        $courseBookAuthors = Book::whereNotNull('author')->distinct()->get(['author'])->pluck('author');
//        dd($courseBookAuthors);
        return view('control_panel.books.basic.index',compact('department','department_name','courseBookAuthors','courseBookCategories'));
    }
    public function getData(Request $request,$department)
    {
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'author',      'dt' => 2 ),
            array( 'db' => 'hours_count',      'dt' => 3 ),
            array( 'db' => 'category_id',      'dt' => 4 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);

        $courseBookCategory = (int)$request->courseBookCategory ? (int)$request->courseBookCategory : 0;
        $author = trim($request->author) ? trim($request->author) : 0;

        $value = array();
//        var_dump($courseBookCategory,$author);
        if(!empty($search)){
            $count = Book::search($search)
                ->coursebookcategory($courseBookCategory)
                ->author($author)
                ->department($department)
                ->count();
            $books = Book::search($search)
                ->coursebookcategory($courseBookCategory)
                ->author($author)
                ->department($department)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = Book::department($department)
                ->coursebookcategory($courseBookCategory)
                ->author($author)
                ->count();
            $books = Book::
                limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->coursebookcategory($courseBookCategory)
                ->author($author)
                ->department($department)
                ->get();
        }
        foreach ($books as $index => $item){
            array_push($value , $item->book_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getBookStudentCategory(Book $book){
        return ['<span style="color: red;  font-size:18px;">'.$book->hours_count.'</span>','<span style="color: #2ca02c; font-size:18px;">'.$book->student_category_string.'</span>'];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($department)
    {
//        dd($department);
        $bookCategories = CourseBookCategory::all();
        $bookCategoriesSelect = '<option value="0">-- اختر --</option>';

        foreach($bookCategories as $key => $bookCategory){
            $bookCategoriesSelect .='<option value="'.$bookCategory->id.'">'.$bookCategory->name.'</option>';
        }
        $years = '';
        $current_year = Carbon::now()->format('Y');
        for($i = 0;$i<6;$i++){
            $year = ($current_year-5)+$i;
            $years .='<option value="'.$year.'">'.$year.'</option>';
        }
        $book = new Book();

        if($department == 1 || $department == 2) {
            return view('control_panel.books.basic.create', compact('book', 'department','years','bookCategoriesSelect'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newBookRequest $request)
    {
        $student_category = [];
        $required_students_number = 0;
        foreach ($request->student_category as $key => $student_category_value){
            $required_students_number += $student_category_value;
            if((int)$student_category_value){
                if($key == 0){
                    array_push($student_category,'ابتدائية ( 7 - 12 )');
                }elseif ($key == 1){
                    array_push($student_category,'اعدادية ( 13 - 15 )');
                }elseif ($key == 2){
                    array_push($student_category,'ثانوية فما فوق ( 16 فما فوق )');
                }elseif ($key == 3){
                    array_push($student_category,'ثانوية فما فوق ( 16 فما فوق )');
                }
            }
        }
        Book::create(array_merge($request->except('student_category','_token','_method'),
            [
                'student_category'=>$student_category,
                'required_students_number'=>$required_students_number,
                'required_students_number_array'=>$request->student_category
            ]));
        return response()->json(['msg'=>'تم اضافة كتاب جديد','title'=>'اضافة','type'=>'success']);
//        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $bookCategories = CourseBookCategory::withCount(['books'=>function($query) use($book){
            $query->where('id',$book->id);
        }])->get();
        $bookCategoriesSelect = '<option value="0">-- اختر --</option>';
        foreach($bookCategories as $key => $bookCategory){
            $selected = $bookCategory->books_count ? 'selected' : '' ;
            $bookCategoriesSelect .='<option value="'.$bookCategory->id.'" '.$selected.'>'.$bookCategory->name.'</option>';
        }
        $years = '';
        $current_year = Carbon::now()->format('Y');
        for($i = 0;$i<6;$i++){
            $year = ($current_year-5)+$i;
            $selected = $book->year == $year ? 'selected' : '' ;
            $years .='<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
        }
//        dd($years);
        $department = $book->department;
        return view('control_panel.books.basic.update',compact('book','department','years','bookCategoriesSelect'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateBookRequest $request, Book $book)
    {
//        dd($request->all());
        $student_category = [];
        $required_students_number = 0;
        foreach ($request->student_category as $key => $student_category_value){
            $required_students_number += $student_category_value;
            if((int)$student_category_value){
                if($key == 0){
                    array_push($student_category,'ابتدائية ( 7 - 12 )');
                }elseif ($key == 1){
                    array_push($student_category,'اعدادية ( 13 - 15 )');
                }elseif ($key == 2){
                    array_push($student_category,'ثانوية فما فوق ( 16 فما فوق )');
                }elseif ($key == 3){
                    array_push($student_category,'ثانوية فما فوق ( 16 فما فوق )');
                }
            }
        }
        $book->update(array_merge($request->except('student_category','_token','_method'),
            [
                'student_category'=>$student_category,
                'required_students_number'=>$required_students_number,
                'required_students_number_array'=>$request->student_category
            ]));
        return response()->json(['msg'=>'تم تعديل بيانات الكتاب بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['msg'=>'تم حذف بيانات الكتاب بنجاح','title'=>'حذف','type'=>'success']);
    }

    public function showDeletedItem(Book $book){
        //checkPermissionHelper('تصفح بيانات كتاب محذوف');
        return view('control_panel.books.restoreItemView',compact('book'));
    }
    public function deleteSelected(Request $request){
        //checkPermissionHelper('حذف الكتب المحددة');
        Book::destroy($request->ids);
//        return $request;
        return response()->json(['msg' => 'تم حذف كافة البيانات المحددة بنجاح.', 'title' => 'حذف', 'type' => 'success']);
    }
    public function deletedItems($department){
        //checkPermissionHelper('تصفح بيانات الكتب المحذوفة');
        return view('control_panel.books.deletedIndex',compact('department'));
    }
    public function deletedItemsData(Request $request,$department){
        //checkPermissionHelper('تصفح بيانات الكتب المحذوفة');
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if(!empty($search)){
            $count = Book::select('id','name')
                ->department($department)
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->orWhere('name', 'like', "%" . $search . "%");
                })
                ->get()
                ->count();
            $books = Book::select('id','name')
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->department($department)
                ->onlyTrashed()
                ->where(function ($query) use ($search){
                    $query->where('id', 'like', "%" . $search . "%")
                        ->orWhere('name', 'like', "%" . $search . "%");
                })
                ->get();
//            dd($books->toArray(),$search);
        } else {
            $count = Book::select('id','name')
                ->department($department)
                ->onlyTrashed()->count();
            $books = Book::select('id','name')
                ->department($department)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->onlyTrashed()->get();
        }
        foreach ($books as $index => $item){
            array_push($value , $item->book_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function restoreSelected(Request $request){
        //checkPermissionHelper('استرجاع الكتبالمحذوفة المحددة');
        Book::onlyTrashed()->whereIn('id',$request->ids)->get()->each(function($item){
            $item->restore();
        });
        return response()->json(['msg' => 'تم استرجاع كافة البيانات المحددة بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }
    public function restoreItem(Book $book){
        //checkPermissionHelper('استرجاع بيانات كتاب محذوف');
        $book->restore();
        return response()->json(['msg' => 'تم استرجاع بيانات الكتاب بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    }

    public function getYearsDoesNotHaveThisBook(Book $book){
//        dd($book);
        return $book->years_does_not_have_this_book;
    }
    public function copyBookDetailsToYear($year,Book $book){
        $newBook = $book->replicate();
        $newBook->year = $year;
        $newBook->save();
        return response()->json(['msg' => 'تمت اضافة الكتاب', 'title' => 'اضافة', 'type' => 'success']);
    }
}
