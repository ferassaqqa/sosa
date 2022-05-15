<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\Circles\Books\newCircleBookRequest;
use App\Http\Requests\controlPanel\Circles\Books\updateCircleBookRequest;
use App\Models\CircleBooks;
use App\Models\CircleMonthlyReport;
use Illuminate\Http\Request;

class CirclesBooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('control_panel.circles.books.basic.index');
    }
    public function getData(Request $request)
    {
        $columns = array(
            array(
                'db' => 'id',
                'dt' => 'DT_RowId',
                'formatter' => function( $d, $row ) {
                    // Technically a DOM id cannot start with an integer, so we prefix
                    // a string. This can also be useful if you have multiple tables
                    // to ensure that the id is unique with a different prefix
                    return 'row_'.$d;
                }
            ),
            array( 'db' => 'id_col',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'pass_mark',      'dt' => 2 ),
            array( 'db' => 'hadith_count',      'dt' => 3 ),
            array( 'db' => 'book_code',      'dt' => 4 ),
            array( 'db' => 'tools',      'dt' => 5 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);


        $value = array();

        if($columns[$order]["db"]=='id'){
            $columns[$order]["db"] = 'location';
        }
        if(!empty($search)){
            $count = CircleBooks::search($search)
                ->count();
            $books = CircleBooks::search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = CircleBooks::count();
            $books = CircleBooks::
                limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        CircleBooks::$counter = $start;
        foreach ($books as $index => $item){
            array_push($value , $item->book_display_data);
        }
//        dd($value);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book = new CircleBooks();
        return view('control_panel.circles.books.basic.create', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCircleBookRequest $request)
    {
        $book = CircleBooks::create($request->all());
        return response()->json(['msg'=>'تم اضافة كتاب تحفيظ جديد','title'=>'اضافة','type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CircleBooks  $circleBooks
     * @return \Illuminate\Http\Response
     */
    public function show(CircleBooks $circleBooks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CircleBooks  $circleBooks
     * @return \Illuminate\Http\Response
     */
    public function edit(CircleBooks $circleBook)
    {
        $book = $circleBook;
//        dd($book);
        return view('control_panel.circles.books.basic.update', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CircleBooks  $circleBooks
     * @return \Illuminate\Http\Response
     */
    public function update(updateCircleBookRequest $request, CircleBooks $circleBook)
    {

        $circleBook->update($request->all());
        return response()->json(['msg'=>'تم تعديل بيانات كتاب التحفيظ','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CircleBooks  $circleBooks
     * @return \Illuminate\Http\Response
     */
    public function destroy(CircleBooks $circleBook)
    {
        $circleBook->delete();
        return response()->json(['msg'=>'تم حذف بيانات كتاب التحفيظ بنجاح','title'=>'حذف','type'=>'success']);
    }
    public function arrangeBooks(Request $request){
        $monthlyReports = CircleMonthlyReport::count();
        if(!$monthlyReports) {
            foreach ($request->ids as $key => $value) {
                $circleBook = CircleBooks::find($value);
                if ($circleBook) {
//                dd($circleBook);
                    $circleBook->update(['location' => $key]);
                }
            }
            return response()->json(['msg'=>'تم اعادة ترتيب الكتب بنجاح','title'=>'رسالة','type'=>'success']);
        }else{
            return response()->json(['msg'=>'لا يمكن اعادة الترتيب ، يوجد طلاب ملتزمة بالترتيب الموجود','title'=>'خطأ!','type'=>'danger']);
        }
//        dd($request->toArray());
    }
}
