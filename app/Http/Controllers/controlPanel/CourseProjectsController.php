<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\CourseProject;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseProjectsController extends Controller
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
        return view('control_panel.CourseProjects.basic.index');
    }
    public function getData (Request $request)
    {
        $columns = array(
            array( 'db' => 'id',        'dt' => 0 ),
            array( 'db' => 'name',      'dt' => 1 ),
            array( 'db' => 'date',      'dt' => 2 ),
        );

        $draw = (int)$request->draw;
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = $request->order[0]["column"];
        $direction = $request->order[0]["dir"];
        $search = trim($request->search["value"]);

        $value = array();

        if(!empty($search)){
            $count = CourseProject::search($search)
                ->count();
            $courseProjecs = CourseProject::search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = CourseProject::count();
            $courseProjecs = CourseProject::
            limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        CourseProject::$counter = $start;
//        dd(json_encode([6,11,21]));
        foreach ($courseProjecs as $index => $item){
            array_push($value , $item->project_display_data);
        }
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
        $courseProject = new CourseProject();
        $books = Book::where('year',Carbon::now()->format('Y'))->get();
//        dd($books);
        return view('control_panel.CourseProjects.basic.create',compact('courseProject','books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        CourseProject::create($request->all());
        return response()->json(['msg'=>'تم اضافة البرنامج بنجاح','title'=>'إضافة','type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseProject  $courseProject
     * @return \Illuminate\Http\Response
     */
    public function show(CourseProject $courseProject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseProject  $courseProject
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseProject $courseProject)
    {
        $books = Book::where('year',Carbon::now()->format('Y'))->get();
//        dd($books[0]->id,$courseProject->books_array,in_array($books[0]->id,$courseProject->books_array));
        return view('control_panel.CourseProjects.basic.update',compact('courseProject','books'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseProject  $courseProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseProject $courseProject)
    {
//        dd($request->all());
        $courseProject->update($request->all());
        return response()->json(['msg'=>'تم تعديل البرنامج بنجاح','title'=>'تعديل','type'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseProject  $courseProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseProject $courseProject)
    {
        //
    }
}
