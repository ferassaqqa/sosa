<?php

namespace App\Http\Controllers\controlPanel\Asaneed;

use App\Http\Controllers\Controller;
use App\Http\Requests\asaneedBooks\newAsaneedBookRequest;
use App\Http\Requests\asaneedBooks\updateAsaneedBookRequest;
use App\Models\AsaneedBook;
use App\Models\AsaneedBookCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsaneedBooksController extends Controller
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
    public function index()
    {
        $asaneedBookCategories = AsaneedBookCategory::whereHas('books')->get();
        $asaneedBookAuthors = AsaneedBook::whereNotNull('author')->distinct()->get(['author'])->pluck('author');
        return view('control_panel.asaneed.books.basic.index',compact('asaneedBookAuthors','asaneedBookCategories'));
    }
    public function getData(Request $request)
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

        $asaneedBookCategory = (int)$request->courseBookCategory ? (int)$request->courseBookCategory : 0;
        $author = trim($request->author) ? trim($request->author) : 0;

        $value = array();
//        var_dump($courseBookCategory,$author);
        if(!empty($search)){
            $count = AsaneedBook::search($search)
                ->asaneedbookcategory($asaneedBookCategory)
                ->author($author)
                ->count();
            $books = AsaneedBook::search($search)
                ->asaneedbookcategory($asaneedBookCategory)
                ->author($author)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = AsaneedBook::
                asaneedbookcategory($asaneedBookCategory)
                ->author($author)
                ->count();
            $books = AsaneedBook::
                limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->asaneedbookcategory($asaneedBookCategory)
                ->author($author)
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
    public function getBookStudentCategory(AsaneedBook $asaneedBook){
        return ['<span style="color: red;">'.$asaneedBook->hours_count.'</span>','<span style="color: #2ca02c;">'.$asaneedBook->student_category_string.'</span>'];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        dd($department);
        $bookCategories = AsaneedBookCategory::all();
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
        $asaneedBook = new AsaneedBook();
        return view('control_panel.asaneed.books.basic.create', compact('asaneedBook','years','bookCategoriesSelect'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAsaneedBookRequest $request)
    {

        $student_category = [];
        $required_students_number = $request->required_students_number;

        $book = AsaneedBook::create(array_merge($request->except('student_category','_token','_method'),
            [

                'required_students_number'=>$required_students_number,

            ]));
        return response()->json(['msg'=>'تم اضافة كتاب جديد في قسم الاسانيد والاجازات','title'=>'اضافة','type'=>'success']);

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
    public function edit(AsaneedBook $asaneedBook)
    {
        $bookCategories = AsaneedBookCategory::withCount(['books'=>function($query) use($asaneedBook){
            $query->where('id',$asaneedBook->id);
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
            $selected = $asaneedBook->year == $year ? 'selected' : '' ;
            $years .='<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
        }
//        dd($years);
        return view('control_panel.asaneed.books.basic.update',compact('asaneedBook','years','bookCategoriesSelect'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAsaneedBookRequest $request, AsaneedBook $asaneedBook)
    {

        $required_students_number = $request->required_students_number;

        $asaneedBook->update(array_merge($request->except('student_category','_token','_method'),
            [
                'required_students_number'=>$required_students_number,
            ]));
        return response()->json(['msg'=>'تم تعديل بيانات الكتاب بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AsaneedBook $asaneedBook)
    {
        $asaneedBook->delete();
        return response()->json(['msg'=>'تم حذف بيانات الكتاب بنجاح','title'=>'حذف','type'=>'success']);
    }

    public function getYearsDoesNotHaveThisBook(AsaneedBook $asaneedBook){
//        dd($book);
        return $asaneedBook->years_does_not_have_this_book;
    }
    public function copyBookDetailsToYear($year,AsaneedBook $asaneedBook){
        $newBook = $asaneedBook->replicate();
        $newBook->year = $year;
        $newBook->save();
        return response()->json(['msg' => 'تمت اضافة الكتاب', 'title' => 'اضافة', 'type' => 'success']);
    }
}
