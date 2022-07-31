<?php

namespace App\Http\Controllers\controlPanel\Asaneed;

use App\Http\Controllers\Controller;
use App\Http\Requests\asaneedBookCategories\newAsaneedBookCategory;
use App\Http\Requests\asaneedBookCategories\updateAsaneedBookCategory;
use App\Models\AsaneedBookCategory;
use Illuminate\Http\Request;

class AsaneedBookCategoriesController extends Controller
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
        return view('control_panel.asaneed.courseBookCategories.basic.index');
    }
    public function getData (Request $request)
    {
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
            $count = AsaneedBookCategory::search($search)
                ->count();
            $CourseBookCategory = AsaneedBookCategory::search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = AsaneedBookCategory::count();
            $CourseBookCategory = AsaneedBookCategory::
            limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        AsaneedBookCategory::$counter = $start;
        foreach ($CourseBookCategory as $index => $item){
            array_push($value , $item->cat_display_data);
        }
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }
    public function getCatBooks(AsaneedBookCategory $asaneedBookCategory){
//        dd($asaneedBookCategory);
        return view('control_panel.asaneed.courseBookCategories.basic.books',compact('asaneedBookCategory'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $asaneedBookCategory = new AsaneedBookCategory();
        return view('control_panel.asaneed.courseBookCategories.basic.create',compact('asaneedBookCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newAsaneedBookCategory $request)
    {
        AsaneedBookCategory::create($request->all());
        return response()->json(['msg'=>'تم اضافة تصنيف كتب الاسانيد جديد','title'=>'اضافة','type'=>'success']);
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
    public function edit(AsaneedBookCategory $asaneedBookCategory)
    {
        return view('control_panel.asaneed.courseBookCategories.basic.update',compact('asaneedBookCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAsaneedBookCategory $request, AsaneedBookCategory $asaneedBookCategory)
    {
        $asaneedBookCategory->update($request->all());
        return response()->json(['msg'=>'تم تعديل تصنيف كتب الاسانيد بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AsaneedBookCategory::destroy($id);
        return response()->json(['msg'=>'تم حذف تصنيف كتب','title'=>'حذف','type'=>'success']);
    }
}
