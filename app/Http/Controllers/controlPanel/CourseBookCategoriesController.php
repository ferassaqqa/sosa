<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\controlPanel\courses\newCourseBookCategory;
use App\Http\Requests\controlPanel\courses\updateCourseBookCategory;
use App\Models\CourseBookCategory;
use Illuminate\Http\Request;

class CourseBookCategoriesController extends Controller
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
        return view('control_panel.courseBookCategories.basic.index');
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
            $count = CourseBookCategory::search($search)
                ->count();
            $CourseBookCategory = CourseBookCategory::search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = CourseBookCategory::count();
            $CourseBookCategory = CourseBookCategory::
                limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        CourseBookCategory::$counter = $start;
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
    public function getCatBooks(CourseBookCategory $CourseBookCategory){
        return view('control_panel.courseBookCategories.basic.books',compact('CourseBookCategory'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $CourseBookCategory = new CourseBookCategory();
        return view('control_panel.courseBookCategories.basic.create',compact('CourseBookCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newCourseBookCategory $request)
    {
        CourseBookCategory::create($request->all());
        return response()->json(['msg'=>'تم اضافة تصنيف كتب جديد','title'=>'اضافة','type'=>'success']);
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
    public function edit(CourseBookCategory $CourseBookCategory)
    {
//        $courseBookCategory = $courseBookCategories;
//        dd($CourseBookCategory);
        return view('control_panel.courseBookCategories.basic.update',compact('CourseBookCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateCourseBookCategory $request, CourseBookCategory $CourseBookCategory)
    {
        $CourseBookCategory->update($request->all());
        return response()->json(['msg'=>'تم تعديل تصنيف كتب','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CourseBookCategory::destroy($id);
        return response()->json(['msg'=>'تم حذف تصنيف كتب','title'=>'حذف','type'=>'success']);
    }
}
