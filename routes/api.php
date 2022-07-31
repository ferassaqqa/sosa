<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('storePlace',function(Request $request){
    foreach($request->all() as $array) {
        \App\Models\Place::create([
            'name' => $array['name'],
            'area_id' => $array['area_name'],
            'address' => $array['address']
        ]);
    }
});

Route::post('storeUser',function(Request $request){
    foreach($request->all() as $key => $array) {
        $user = \App\Models\User::where('id_num',$array['mobile'])->first();
        if($user) {
            if ($array['issuper'] == 3) {
                $user->assignRole('مشرف عام');
            } elseif ($array['issuper'] == 4) {
                $user->assignRole('مشرف ميداني');
            } elseif ($array['issuper'] == 5) {
                $user->assignRole('مشرف جودة');
            } elseif ($array['issuper'] == 1) {
                $user->assignRole('مدير الدائرة');
            }
        }
        if(!$user) {
//            if ($array['issuper'] == 1) {
//                $user = \App\Models\User::create([
//                    'username' => $array['username'],
//                    'name' => $array['name'],
//                    'password' => \Illuminate\Support\Facades\Hash::make($array['password']),
//                    'id_num' => $array['mobile']
//                ]);
//                $user->userExtraData()->create($request->only('email', 'mobile'));
//                $user->assignRole('مدير الدائرة');
//            } else {
//                $area = \App\Models\Area::where('name', 'like', '%' . $array['title'] . '%')->first();
//                if ($area) {
//                    $user = \App\Models\User::create([
//                        'username' => $array['username'],
//                        'name' => $array['name'],
//                        'area_id' => $area->id,
//                        'password' => \Illuminate\Support\Facades\Hash::make($array['password']),
//                        'id_num' => $array['mobile']
//                    ]);
//                    $user->userExtraData()->create($request->only('email', 'mobile'));
//                    if ($array['issuper'] == 3) {
//                        $user->assignRole('مشرف عام');
//                    } elseif ($array['issuper'] == 4) {
//                        $user->assignRole('مشرف ميداني');
//                    } elseif ($array['issuper'] == 5) {
//                        $user->assignRole('مشرف جودة');
//                    }
//                }
//            }
        }
    }

});
Route::post('storeTeachers',function(Request $request){
//    dd($request->all()[0]);
    foreach($request->all() as $key => $array) {
        $user = \App\Models\User::where('id_num',$array['id_nu'])->first();
        if(!$user) {
            $user = \App\Models\User::create([
                'sons_count' => $array['sons'],
                'material_status' => $array['social_status'],
                'prefix' => $array['title'],
                'dob' => $array['dob'],
                'pob' => $array['pob'],
                'address' => $array['address'],
                'title' => $array['title'],
                'role' => 1,
//            'username' => $array['username'],
                'name' => $array['name'],
                'password' => \Illuminate\Support\Facades\Hash::make($array['id_nu']),
                'id_num' => $array['id_nu']
            ]);
            $user->userExtraData()->create([
                'user_id' => $user->id,
                'email' => $array['email'],
                'home_tel' => $array['phone'],
                'fb_link' => $array['facebook'],
                'collage' => $array['college'],
                'speciality' => $array['specialty'],
                'occupation' => $array['jop'],
                'occupation_place' => $array['work_place'],
                'monthly_income' => $array['income'],
                'join_date' => $array['start_date'],
                'qualification' => $array['level'],
                'mobile' => $array['mobile'],
                'contract_type' => $array['type'],
                'contract_type_value' => $array['amount'],
                'computer_skills' => $array['computer_skills'],
                'english_skills' => $array['english_skills'],
                'health_skills' => $array['health_skills'],
            ]);
            if (strtolower($array['user_type']) == 'circles') {
                $user->assignRole('محفظ');
            } else if (strtolower($array['user_type']) == 'courses') {
                $user->assignRole('معلم');
            }
        }else{
//            $place = \App\Models\Place::where('area_id',$array['sub_area'])->where('name','like','%'.$array['place'].'%')->first();
//            dd($place);
//            $user->update(['place_id'=>$place_id]);
        }
    }
});
Route::post('storeMoallem',function(\Maatwebsite\Excel\Excel $excel,Request $request){
//    \App\Models\User::department(2)->get()->each(function($item){$item->delete();});
        $import = new \App\Imports\MoallemImporter($excel);
        $import->import(request()->file('file'));
//        $excel->store(
//            new RoleFaultsExport($import->failures())
//            , 'public/roles_faulties.xlsx');
//        dd(asset('storage/roles_faulties.xlsx'));
//        return Response::download();
        return response()->json(['file_link'=>asset('storage/roles_faulties.xlsx')]);

});
Route::get('/areaWithOutSupervisor',function(){
    $areas = \App\Models\Area::whereNull('area_supervisor_id')->whereNull('sub_area_supervisor_id')->get()->toArray();

//    dd($areas);
});
