<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Circle;
use App\Models\ContractType;
use App\Models\Course;
use App\Models\Income;
use App\Models\OldCourse;
use App\Models\PersonalSkill;
use App\Models\Place;
use App\Models\Prefix;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
//         Route::bind('course',function($course_id){
//             $model = Course::find($course_id);
//             if($model){
//                 return $model;
//             }else{
//                 $model = Course::withoutGlobalScope('relatedCourses')->find($course_id);
// //                dd($role);
//                 if($model){
//                     return $model;
//                 }else{
//                     return null;
//                 }
//             }
//         });
//        Route::bind('area',function($area_id){
//            $model = Area::find($area_id);
//            if($model){
//                return $model;
//            }else{
//                $model = Area::onlyTrashed()->find($area_id);
//                if($model){
//                    return $model;
//                }
//            }
//        });
//        Route::bind('place',function($place_id){
//            $model = Place::find($place_id);
//            if($model){
//                return $model;
//            }else{
//                $model = Place::onlyTrashed()->find($place_id);
//                if($model){
//                    return $model;
//                }
//            }
//        });
//        Route::bind('circle',function($circle_id){
//            $model = Circle::find($circle_id);
//            if($model){
//                return $model;
//            }else{
//                $model = Circle::onlyTrashed()->find($circle_id);
//                if($model){
//                    return $model;
//                }
//            }
//        });
//        Route::bind('user',function($user_id){
//            $model = User::find($user_id);
//            if($model){
//                return $model;
//            }else{
//                $model = User::onlyTrashed()->find($user_id);
//                if($model){
//                    return $model;
//                }
//            }
//        });
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
