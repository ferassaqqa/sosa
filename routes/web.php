<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    //Route::resource('dashboard', \App\Http\Controllers\controlPanel\DashboardController::class);

    Route::resource('dashboard', \App\Http\Controllers\controlPanel\DashboardController::class);
    Route::get('updateCourseAndStudentsStatisticsInDashboard', [\App\Http\Controllers\controlPanel\DashboardController::class, 'updateCourseAndStudentsStatisticsInDashboard']);

    /**
     *  Start Roles Operations
     */

    Route::resource('roles', \App\Http\Controllers\controlPanel\RolesController::class);
    Route::get('getRolesData', [\App\Http\Controllers\controlPanel\RolesController::class, 'getData'])->name('roles.getData');
    Route::get('restoreItem/{role}', [\App\Http\Controllers\controlPanel\RolesController::class, 'restoreItem'])->name('roles.restoreItem');
    Route::get('rolePermissions/{role}', [\App\Http\Controllers\controlPanel\RolesController::class, 'permissions'])->name('roles.permissions');
    Route::get('showDeletedItem/{role}', [\App\Http\Controllers\controlPanel\RolesController::class, 'showDeletedItem'])->name('roles.showDeletedItem');
    Route::DELETE('deleteSelected', [\App\Http\Controllers\controlPanel\RolesController::class, 'deleteSelected'])->name('roles.deleteSelected');
    Route::PUT('updatePermissions/{role}', [\App\Http\Controllers\controlPanel\RolesController::class, 'updatePermissions'])->name('roles.updatePermissions');
    Route::get('deletedItems', [\App\Http\Controllers\controlPanel\RolesController::class, 'deletedItems'])->name('roles.deletedItems');
    Route::get('deletedItemsData', [\App\Http\Controllers\controlPanel\RolesController::class, 'deletedItemsData'])->name('roles.deletedItemsData');
    Route::post('restoreSelected', [\App\Http\Controllers\controlPanel\RolesController::class, 'restoreSelected'])->name('roles.restoreSelected');
    Route::get('exportRolesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportRolesExcel'])->name('roles.exportExcel');
    Route::post('importRolesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importRolesExcel'])->name('roles.importExcel');
    Route::get('restoreItemFromExcel/{role}', function (\Spatie\Permission\Models\Role $role) {
        dd(\Illuminate\Support\Facades\Auth::user());
        $role->restore();
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('roles.restoreItemFromExcel');

    /**
     *  End Roles Operations
     */

    /**
     *  Start Places Operations
     */

    Route::resource('places', \App\Http\Controllers\controlPanel\PlacesController::class);
    Route::get('getPlacesDate', [\App\Http\Controllers\controlPanel\PlacesController::class, 'getData'])->name('places.getData');
    Route::get('getPlaceTeachers/{place}/{teacher_id}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'getPlaceTeachersForCourses'])->name('places.getPlaceTeachers');
    Route::get('getPlaceTeachersForCircles/{place}/{teacher_id}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'getPlaceTeachersForCircles'])->name('places.getPlaceTeachersForCircles');
    Route::get('getPlaceAreaSupervisorForCircles/{place}/{teacher_id}', [\App\Http\Controllers\controlPanel\PlacesController::class, 'getPlaceAreaSupervisorForCircles'])->name('places.getPlaceAreaSupervisorForCircles');
    Route::get('searchAreaForPlaces/{search}/{count}', [\App\Http\Controllers\controlPanel\PlacesController::class, 'searchAreaForPlaces'])->name('places.searchAreaForPlaces');
    Route::get('restorePlace/{place}', [\App\Http\Controllers\controlPanel\PlacesController::class, 'restoreItem'])->name('places.restoreItem');
    Route::get('showDeletedPlaces/{place}', [\App\Http\Controllers\controlPanel\PlacesController::class, 'showDeletedItem'])->name('places.showDeletedItem');
    Route::DELETE('deleteSelectedPlaces', [\App\Http\Controllers\controlPanel\PlacesController::class, 'deleteSelected'])->name('places.deleteSelected');
    Route::get('deletedPlaces', [\App\Http\Controllers\controlPanel\PlacesController::class, 'deletedItems'])->name('places.deletedItems');
    Route::get('deletedPlacesData', [\App\Http\Controllers\controlPanel\PlacesController::class, 'deletedItemsData'])->name('places.deletedItemsData');
    Route::post('restoreSelectedPlaces', [\App\Http\Controllers\controlPanel\PlacesController::class, 'restoreSelected'])->name('places.restoreSelected');
    Route::get('exportPlacesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportPlacesExcel'])->name('places.exportExcel');
    Route::post('importPlacesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importPlacesExcel'])->name('places.importExcel');
    Route::get('restorePlaceFromExcel/{place}', function (\App\Models\Place $place) {
        dd(\Illuminate\Support\Facades\Auth::user());
        $place->restore();
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('places.restoreItemFromExcel');

    /**
     *  End Places Operations
     */


    /**
     *  Start Circles Operations
     */

    Route::resource('circles', \App\Http\Controllers\controlPanel\CirclesController::class);

    Route::get('getSubAreaCircleTeachers/{area_id}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'getSubAreaCircleTeachers'])->name('circles.getSubAreaCircleTeachers');
    Route::get('getCirclesDate', [\App\Http\Controllers\controlPanel\CirclesController::class, 'getData'])->name('circles.getData');
    Route::get('changeCircleStatus/{circle}/{status}/{note?}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'changeCircleStatus'])->name('circles.changeCircleStatus');
    Route::get('searchPlaceForCircles/{search}/{count}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'searchPlaceForCircles'])->name('circles.searchPlaceForCircles');
    Route::get('searchTeachersForCircles/{search}/{count}/{place_id}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'searchTeachersForCircles'])->name('circles.searchTeachersForCircles');
    Route::get('searchSupervisorsForCircles/{search}/{count}/{place_id}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'searchSupervisorsForCircles'])->name('circles.searchSupervisorsForCircles');
    Route::get('restoreCircle/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'restoreItem'])->name('circles.restoreItem');
    Route::get('getCircleStudents/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'getCircleStudents'])->name('circles.students');
    Route::get('getSubAreasOfAreaForCircles/{area}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'getSubAreasOfAreaForCircles'])->name('circles.getSubAreasOfAreaForCircles');
    Route::get('showDeletedCircles/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'showDeletedItem'])->name('circles.showDeletedItem');
    Route::DELETE('deleteSelectedCircles', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deleteSelected'])->name('circles.deleteSelected');
    Route::get('deletedCircles', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deletedItems'])->name('circles.deletedItems');
    Route::get('deletedCirclesData', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deletedItemsData'])->name('circles.deletedItemsData');
    Route::post('restoreSelectedCircles', [\App\Http\Controllers\controlPanel\CirclesController::class, 'restoreSelected'])->name('circles.restoreSelected');
    Route::get('exportCirclesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportCirclesExcel'])->name('circles.exportExcel');
    Route::post('importCirclesExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importCirclesExcel'])->name('circles.importExcel');
    Route::get('deleteCircleAppointment/{circleDate}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deleteCircleAppointment'])->name('circles.deleteCircleAppointment');
    Route::get('deleteCircleAppointmentForEver/{circleDate}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deleteCircleAppointmentForEver'])->name('circles.deleteCircleAppointmentForEver');
    Route::get('restoreCircleAppointment/{circleDate}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'restoreCircleAppointment'])->name('circles.restoreCircleAppointment');
    Route::get('showDeletedCircleAppointment/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'showDeletedCircleAppointment'])->name('circles.showDeletedCircleAppointment');
    Route::get('restoreAllAppointments/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'restoreAllAppointments'])->name('circles.restoreAllAppointments');
    Route::get('deleteAllAppointments/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'deleteAllAppointments'])->name('circles.deleteAllAppointments');
    Route::get('restoreCircleFromExcel/{circle}', function (\App\Models\Circle $circle) {
        dd(\Illuminate\Support\Facades\Auth::user());
        $circle->restore();
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('circles.restoreItemFromExcel');
    Route::get('showLoadingCircleStudents/{circle}', [\App\Http\Controllers\controlPanel\CirclesController::class, 'showLoadingCircleStudents'])->name('circles.showLoadingCircleStudents');
    Route::post('importCircleStudentsExcel/{circle}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importCircleStudentsExcel'])->name('circles.importCircleStudentsExcel');
    /**
     *  End Circles Operations
     */


    /**
     *  Start Areas Operations
     */

    Route::resource('areas', \App\Http\Controllers\controlPanel\AreasController::class);
    Route::get('checkAreaTotalPercentage/{area_id}/{percentage}', [\App\Http\Controllers\controlPanel\AreasController::class, 'checkAreaTotalPercentage'])->name('areas.checkAreaTotalPercentage');
    Route::get('getSubAreas/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'getSubAreas'])->name('areas.getSubAreas');
    Route::get('getSubAreaPlaces/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'getSubAreaPlaces'])->name('areas.getSubAreaPlaces');
    Route::get('getAreasData', [\App\Http\Controllers\controlPanel\AreasController::class, 'getData'])->name('areas.getData');
    Route::get('restoreArea/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'restoreItem'])->name('areas.restoreItem');
    Route::get('showDeletedArea/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'showDeletedItem'])->name('areas.showDeletedItem');
    Route::DELETE('deleteSelectedArea', [\App\Http\Controllers\controlPanel\AreasController::class, 'deleteSelected'])->name('areas.deleteSelected');
    Route::get('deletedAreas', [\App\Http\Controllers\controlPanel\AreasController::class, 'deletedItems'])->name('areas.deletedItems');
    Route::get('deletedAreasData', [\App\Http\Controllers\controlPanel\AreasController::class, 'deletedItemsData'])->name('areas.deletedItemsData');
    Route::post('restoreSelectedArea', [\App\Http\Controllers\controlPanel\AreasController::class, 'restoreSelected'])->name('areas.restoreSelected');
    Route::get('deleteSubArea/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'deleteSubArea'])->name('areas.deleteSubArea');
    Route::get('showDeletedSubAreaItem/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'showDeletedSubAreaItem'])->name('areas.showDeletedSubAreaItem');
    Route::get('restoreSubArea/{area}', [\App\Http\Controllers\controlPanel\AreasController::class, 'restoreSubArea'])->name('areas.restoreSubArea');
    Route::get('exportAreasExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportAreasExcel'])->name('areas.exportExcel');
    Route::post('importAreasExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importAreasExcel'])->name('areas.importExcel');
    Route::get('restoreAreaFromExcel/{area}', function (\App\Models\Area $area) {
        dd(\Illuminate\Support\Facades\Auth::user());
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('areas.restoreItemFromExcel');

    /**
     *  End Areas Operations
     */

    /**
     *  Start Users Operations
     */
    Route::resource('users', \App\Http\Controllers\controlPanel\UsersController::class)->except('create');
    Route::get('users/create/{id_num}', [\App\Http\Controllers\controlPanel\UsersController::class, 'create'])->name('users.create');
    Route::get('getUsersData', [\App\Http\Controllers\controlPanel\UsersController::class, 'getData'])->name('users.getData');
    Route::get('getSubAreasForAreaSupervisor/{area}', [\App\Http\Controllers\controlPanel\UsersController::class, 'getSubAreasForAreaSupervisor'])->name('users.getSubAreasForAreaSupervisor');
    Route::get('getAreasForGeneralSupervisor', [\App\Http\Controllers\controlPanel\UsersController::class, 'getAreasForGeneralSupervisor'])->name('users.getAreasForGeneralSupervisor');
    Route::get('restoreUser/{user}', [\App\Http\Controllers\controlPanel\UsersController::class, 'restoreItem'])->name('users.restoreItem');
    Route::get('getCourses/{user}', [\App\Http\Controllers\controlPanel\UsersController::class, 'getCourses'])->name('users.getCourses');
    Route::get('getPassedCourses/{user}', [\App\Http\Controllers\controlPanel\UsersController::class, 'getPassedCourses'])->name('users.getPassedCourses');
    Route::get('getFailedCourses/{user}', [\App\Http\Controllers\controlPanel\UsersController::class, 'getFailedCourses'])->name('users.getFailedCourses');
    Route::get('getStudentDateFromIdentityNum/{id_num}', [\App\Http\Controllers\controlPanel\UsersController::class, 'getStudentDateFromIdentityNum'])->name('users.getStudentDateFromIdentityNum');
    Route::get('showDeletedUser/{user}', [\App\Http\Controllers\controlPanel\UsersController::class, 'showDeletedItem'])->name('users.showDeletedItem');
    Route::DELETE('deleteSelectedUser', [\App\Http\Controllers\controlPanel\UsersController::class, 'deleteSelected'])->name('users.deleteSelected');
    Route::get('deletedUsers/{department}', [\App\Http\Controllers\controlPanel\UsersController::class, 'deletedItems'])->name('users.deletedItems');
    Route::get('deletedUsersData/{department}', [\App\Http\Controllers\controlPanel\UsersController::class, 'deletedItemsData'])->name('users.deletedItemsData');
    Route::post('restoreSelectedUser', [\App\Http\Controllers\controlPanel\UsersController::class, 'restoreSelected'])->name('users.restoreSelected');
    Route::get('exportUsersExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportUsersExcel'])->name('users.exportExcel');
    Route::post('importUsersExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importUsersExcel'])->name('users.importExcel');
    Route::get('restoreUserFromExcel/{user}', function (\App\Models\User $user) {
        dd(\Illuminate\Support\Facades\Auth::user());
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('users.restoreItemFromExcel');
    //Route::get('getSystemSupervisorsAsSelectInput/{user_id}', [\App\Http\Controllers\controlPanel\UsersController::class,'deletedItemsData'])->name('users.getSystemSupervisorsAsSelectInput');

    /**
     *  End Users Operations
     */
    /**
     *  Start Books Operations
     */
    Route::resource('books', \App\Http\Controllers\controlPanel\BooksController::class)->except('index', 'create');
    Route::get('books/index/{department}', [\App\Http\Controllers\controlPanel\BooksController::class, 'index'])->name('books.index');
    Route::get('books/create/{department}', [\App\Http\Controllers\controlPanel\BooksController::class, 'create'])->name('books.create');
    Route::get('getBooksData/{department}', [\App\Http\Controllers\controlPanel\BooksController::class, 'getData'])->name('books.getData');
    Route::get('getBookStudentCategory/{book}', [\App\Http\Controllers\controlPanel\BooksController::class, 'getBookStudentCategory'])->name('books.getBookStudentCategory');
    Route::get('getYearsDoesNotHaveThisBook/{book}', [\App\Http\Controllers\controlPanel\BooksController::class, 'getYearsDoesNotHaveThisBook'])->name('books.getYearsDoesNotHaveThisBook');
    Route::get('copyBookDetailsToYear/{year}/{book}', [\App\Http\Controllers\controlPanel\BooksController::class, 'copyBookDetailsToYear'])->name('books.copyBookDetailsToYear');
    Route::get('restoreBook/{book}', [\App\Http\Controllers\controlPanel\BooksController::class, 'restoreItem'])->name('books.restoreItem');
    Route::get('showDeletedBook/{book}', [\App\Http\Controllers\controlPanel\BooksController::class, 'showDeletedItem'])->name('books.showDeletedItem');
    Route::DELETE('deleteSelectedBook', [\App\Http\Controllers\controlPanel\BooksController::class, 'deleteSelected'])->name('books.deleteSelected');
    Route::get('deletedBooks/{department}', [\App\Http\Controllers\controlPanel\BooksController::class, 'deletedItems'])->name('books.deletedItems');
    Route::get('deletedBooksData/{department}', [\App\Http\Controllers\controlPanel\BooksController::class, 'deletedItemsData'])->name('books.deletedItemsData');
    Route::post('restoreSelectedBook', [\App\Http\Controllers\controlPanel\BooksController::class, 'restoreSelected'])->name('books.restoreSelected');
    Route::get('exportBooksExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportBooksExcel'])->name('books.exportExcel');
    Route::post('importBooksExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importBooksExcel'])->name('books.importExcel');
    Route::get('restoreBookFromExcel/{book}', function (\App\Models\Book $book) {
        dd(\Illuminate\Support\Facades\Auth::user());
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('books.restoreItemFromExcel');
    /**
     *  End Books Operations
     */
    /**
     *  Start Courses Operations
     */
    Route::resource('courses', \App\Http\Controllers\controlPanel\CoursesController::class);
    Route::get('addReservationOrder/{course}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'addReservationOrder'])->name('courses.addReservationOrder');
    Route::get('getCoursesData', [\App\Http\Controllers\controlPanel\CoursesController::class, 'getData'])->name('courses.getData');
    Route::get('getCoursesDetails/{course}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'details'])->name('courses.details');
    Route::get('getSubAreaTeachers/{area_id}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'getSubAreaTeachers'])->name('courses.getSubAreaTeachers');
    Route::get('getSubAreaTeachersNew/{area_id}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'getSubAreaTeachersNew'])->name('courses.getSubAreaTeachersNew');
    Route::get('changeCourseStatus/{course}/{status}/{note?}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'changeCourseStatus'])->name('courses.changeCourseStatus');
    Route::get('getYearBooksForNewCourse/{year}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'getYearBooksForNewCourse'])->name('courses.getYearBooksForNewCourse');
    Route::get('createOutOfPlanBook/{year}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'createOutOfPlanBook'])->name('courses.createOutOfPlanBook');
    Route::get('storeStudentsMarks/{course}', [\App\Http\Controllers\controlPanel\CoursesController::class, 'storeStudentsMarks'])->name('courses.storeStudentsMarks');
    Route::get('exportCourseStudentsMarksExcelSheet/{course}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportCourseStudentsMarksExcelSheet'])->name('courses.exportCourseStudentsMarksExcelSheet');
    Route::post('importCourseStudentsExcel/{course}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importCourseStudentsExcel'])->name('courses.importCourseStudentsExcel');
    Route::post('importCourseStudentsMarkExcel/{exam}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importCourseStudentsMarkExcel'])->name('courses.importCourseStudentsMarkExcel');
    Route::post('importCourseNewStudentsMarkExcel/{exam}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importCourseNewStudentsMarkExcel'])->name('courses.importCourseNewStudentsMarkExcel');
    Route::get('exportCourseExamStudentsListAsExcelFile/{exam}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportCourseExamStudentsListAsExcelFile'])->name('courses.exportCourseExamStudentsListAsExcelFile');
    Route::get('restoreBookFromExcel/{book}', function (\App\Models\Book $book) {
        dd(\Illuminate\Support\Facades\Auth::user());
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('books.restoreItemFromExcel');
    /**
     *  End Courses Operations
     */
    /**
     *  Start Asaneed Courses Operations
     */


    Route::resource('asaneedCourses', \App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class);
    Route::get('getAsaneedCoursesData', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'getData'])->name('asaneedCourses.getData');
    Route::get('changeAsaneedCourseStatus/{asaneedCourse}/{status}/{note?}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'changeCourseStatus'])->name('asaneedCourses.changeCourseStatus');
    Route::get('getYearBooksForNewAsaneedCourse/{year}/{type}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'getYearBooksForNewCourse'])->name('asaneedCourses.getYearBooksForNewCourse');
    Route::get('createOutOfPlanAsaneedBook/{year}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'createOutOfPlanBook'])->name('asaneedCourses.createOutOfPlanBook');
    Route::get('storeAsaneedStudentsMarks/{asaneedCourse}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'storeStudentsMarks'])->name('asaneedCourses.storeStudentsMarks');
    Route::get('getPlaceAsaneedTeachers/{place}/{teacher_id}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'getPlaceTeachersForCourses'])->name('asaneedCourses.getPlaceTeachers');
    Route::get('showLoadingAsaneedStudents/{asaneedCourse}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'showLoadingAsaneedStudents'])->name('asaneedCourses.showLoadingAsaneedStudents');
    Route::post('importAsaneedStudentsExcel/{asaneedCourse}', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importAsaneedStudentsExcel'])->name('asaneedCourses.importAsaneedStudentsExcel');
    Route::get('getSubAreaAsaneedTeachers/{area_id}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedCoursesController::class, 'getSubAreaAsaneedTeachers'])->name('asaneedCourses.getSubAreaAsaneedTeachers');


    /**
     *  End Asaneed Courses Operations
     */
    /**
     *  Start AsaneedBookCategories Operations
     */
    Route::resource('asaneedBookCategories', \App\Http\Controllers\controlPanel\Asaneed\AsaneedBookCategoriesController::class);
    Route::get('getAsaneedBookCategoriesData', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBookCategoriesController::class, 'getData'])->name('asaneedBookCategories.getData');
    Route::get('getAsaneedCatBooks/{asaneedBookCategory}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBookCategoriesController::class, 'getCatBooks'])->name('asaneedBookCategories.getCatBooks');
    /**
     *  End AsaneedBookCategories Operations
     */
    /**
     *  Start Asaneed Courses Operations
     */
    Route::resource('asaneedBooks', \App\Http\Controllers\controlPanel\Asaneed\AsaneedBooksController::class);
    Route::get('getAsaneedBooksData', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBooksController::class, 'getData'])->name('asaneedBooks.getData');
    Route::get('getAsaneedBookStudentCategory/{asaneedBook}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBooksController::class, 'getBookStudentCategory'])->name('asaneedBooks.getBookStudentCategory');
    Route::get('getYearsDoesNotHaveThisAsaneedBook/{asaneedBook}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBooksController::class, 'getYearsDoesNotHaveThisBook'])->name('asaneedBooks.getYearsDoesNotHaveThisBook');
    Route::get('copyAsaneedBookDetailsToYear/{year}/{asaneedBook}', [\App\Http\Controllers\controlPanel\Asaneed\AsaneedBooksController::class, 'copyBookDetailsToYear'])->name('asaneedBooks.copyBookDetailsToYear');

    /**
     *  End Asaneed Courses Operations
     */

    /**
     *  Start AsaneedPlans Operations
     */
    Route::resource('asaneedPlans', \App\Http\Controllers\controlPanel\Asaneed\PlansController::class)->except('create', 'show', 'edit', 'destroy', 'update');
    Route::get('asaneedPlans/show/{book}/{type}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'show'])->name('asaneedPlans.show');
    Route::get('asaneedPlans/create/{year?}/{outOfPlanTotal?}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'create'])->name('asaneedPlans.create');
    Route::get('asaneedPlans/{year}/edit', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'edit'])->name('asaneedPlans.edit');
    Route::get('asaneedPlans/{year}/regenerate/{outOfPlanTotal}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'regenerate'])->name('asaneedPlans.regenerate');
    Route::DELETE('asaneedPlans/{year}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'destroy'])->name('asaneedPlans.destroy');
    Route::put('asaneedPlans/{year}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'update'])->name('asaneedPlans.update');
    Route::get('AsaneedCoursePlansFatherAreaSonsValues/{year}/{area_id}/{book_id}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'CoursePlansFatherAreaSonsValues'])->name('asaneedPlans.CoursePlansFatherAreaSonsValues');
    Route::get('getAsaneedPlansData', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'getData'])->name('asaneedPlans.getData');
    Route::get('areaAsaneedCoursesProgressPercentage/{year}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'areaCoursesProgressPercentage'])->name('asaneedPlans.areaCoursesProgressPercentage');
    Route::get('showAsaneedCoursePlan/{year}', [\App\Http\Controllers\controlPanel\Asaneed\PlansController::class, 'showCoursePlan'])->name('asaneedPlans.showCoursePlan');

    /**
     *  End AsaneedPlans Operations
     */
    /**getTeacherCourseBooks
     *  Start asaneedCourseStudents Operations
     */
    Route::resource('asaneedCourseStudents', \App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class)->except('create');
    Route::get('getAsaneedCourseStudentsData', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getData'])->name('asaneedCourseStudents.getData');
    Route::get('ShowAsaneedCourseStudents/{asaneedCourse}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'ShowAsaneedCourseStudents'])->name('asaneedCourseStudents.ShowAsaneedCourseStudents');
    Route::get('getTeacherAsaneedCourseBooks/{user_id}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getTeacherCourseBooks'])->name('asaneedCourseStudents.getTeacherCourseBooks');
    Route::get('excludeAsaneedStudent/{user_id_num}/{asaneedCourse}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'excludeStudent'])->name('asaneedCourseStudents.excludeStudent');
    Route::get('getBookAsaneedCoursePlaces/{book_id}/{teacher_id}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getBookCoursePlaces'])->name('asaneedCourseStudents.getBookCoursePlaces');
    Route::get('asaneedCourseStudents/create/{id_num}/{asaneedCourse}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'create'])->name('asaneedCourseStudents.create');
    Route::get('getAsaneedCourses/{user}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getCourses'])->name('asaneedCourseStudents.getCourses');
    Route::get('getAsaneedPassedCourses/{user}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getPassedCourses'])->name('asaneedCourseStudents.getPassedCourses');
    Route::get('getAsaneedFailedCourses/{user}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'getFailedCourses'])->name('asaneedCourseStudents.getFailedCourses');
    Route::delete('asaneedCourseStudents/destroy/{user}/{asaneedCourse}', [\App\Http\Controllers\controlPanel\users\AsaneedCoursesStudentsController::class, 'destroy'])->name('asaneedCourseStudents.destroy');

    /**
     *  End asaneedCourseStudents Operations
     */
    /**
     *  Start Circles Books Operations
     */
    Route::resource('circleBooks', \App\Http\Controllers\controlPanel\CirclesBooksController::class);
    Route::get('getCircleBooksData', [\App\Http\Controllers\controlPanel\CirclesBooksController::class, 'getData'])->name('circleBooks.getData');
    Route::post('arrangeCircleBooks', [\App\Http\Controllers\controlPanel\CirclesBooksController::class, 'arrangeBooks'])->name('circleBooks.arrangeBooks');

    /**
     *  End Circles Books Operations
     */

    /**
     *  Start asaneed moallem Operations
     */
    Route::resource('asaneedMoallem', \App\Http\Controllers\controlPanel\users\asaneedMoallemController::class)->except('create');
    Route::get('getAsaneedMoallemData', [\App\Http\Controllers\controlPanel\users\asaneedMoallemController::class, 'getData'])->name('asaneedMoallem.getData');
    Route::get('getAsaneedUserUpdateRolesSelect/{user}', [\App\Http\Controllers\controlPanel\users\asaneedMoallemController::class, 'getUserUpdateRolesSelect'])->name('asaneedMoallem.getUserUpdateRolesSelect');
    Route::get('updateAsaneedUserRoles/{user}/{roles}', [\App\Http\Controllers\controlPanel\users\asaneedMoallemController::class, 'updateUserRoles'])->name('asaneedMoallem.updateUserRoles');
    Route::get('asaneedMoallem/create/{id_num}', [\App\Http\Controllers\controlPanel\users\asaneedMoallemController::class, 'create'])->name('asaneedMoallem.create');

    /**
     *  End asaneed moallem Operations
     */
    /**
     *  Start Circles Plans Operations
     */
    Route::resource('circlePlans', \App\Http\Controllers\controlPanel\CirclePlansController::class)->except('create', 'show');
    Route::get('getCircleBookPlansData', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'getData'])->name('circlePlans.getData');
    Route::get('circlePlans/create/{year}', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'create'])->name('circlePlans.create');
    Route::get('circlePlans/{year}/show', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'show'])->name('circlePlans.show');
    Route::get('circlePlans/{year}/agenda', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'agenda'])->name('circlePlans.agenda');
    Route::get('updateCircleBookPlan/{year}/{book_id}/{value}', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'updateCircleBookPlan'])->name('circlePlans.updateCircleBookPlan');
    Route::get('getAddNewCirclePlanAgendaSemester/{year}', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'getAddNewCirclePlanAgendaSemester'])->name('circlePlans.getAddNewCirclePlanAgendaSemester');
    Route::get('deleteCirclePlanAgendaSemester/{agenda_id}', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'deleteCirclePlanAgendaSemester'])->name('circlePlans.deleteCirclePlanAgendaSemester');
    Route::post('storeNewCirclePlanAgendaSemester/{year}', [\App\Http\Controllers\controlPanel\CirclePlansController::class, 'storeNewCirclePlanAgendaSemester'])->name('circlePlans.storeNewCirclePlanAgendaSemester');

    /**
     *  End Circles Plans Operations
     */
    /**
     *  Start mohafez Operations
     */
    Route::resource('mohafez', \App\Http\Controllers\controlPanel\users\mohafezController::class)->except('create');
    Route::get('getMohafezData', [\App\Http\Controllers\controlPanel\users\mohafezController::class, 'getData'])->name('mohafez.getData');
    Route::get('mohafez/create/{id_num}', [\App\Http\Controllers\controlPanel\users\mohafezController::class, 'create'])->name('mohafez.create');

    /**
     *  End mohafez Operations
     */
    /**
     *  Start moallem Operations
     */
    Route::resource('moallem', \App\Http\Controllers\controlPanel\users\moallemController::class)->except('create');
    Route::get('getMoallemData', [\App\Http\Controllers\controlPanel\users\moallemController::class, 'getData'])->name('moallem.getData');
    Route::get('getUserUpdateRolesSelect/{user}', [\App\Http\Controllers\controlPanel\users\moallemController::class, 'getUserUpdateRolesSelect'])->name('moallem.getUserUpdateRolesSelect');
    Route::get('updateUserRoles/{user}/{roles}', [\App\Http\Controllers\controlPanel\users\moallemController::class, 'updateUserRoles'])->name('moallem.updateUserRoles');
    Route::get('moallem/create/{id_num}', [\App\Http\Controllers\controlPanel\users\moallemController::class, 'create'])->name('moallem.create');
    Route::get('exportMoallemsAsExcelSheet', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportMoallemsAsExcelSheet'])->name('moallem.exportMoallemsAsExcelSheet');

    /**
     *  End moallem Operations
     */
    /**
     *  Start circleStudents Operations
     */
    Route::resource('circleStudents', \App\Http\Controllers\controlPanel\users\circleStudentsController::class)->except('create');
    Route::get('getCircleStudentsData', [\App\Http\Controllers\controlPanel\users\circleStudentsController::class, 'getData'])->name('circleStudents.getData');
    Route::get('/circleStudents/create/{id_num}', [\App\Http\Controllers\controlPanel\users\circleStudentsController::class, 'create'])->name('circleStudents.create');
    Route::delete('circleStudents/destroy/{user}/{circle}', [\App\Http\Controllers\controlPanel\users\CircleStudentsController::class, 'destroy'])->name('circleStudents.destroy');

    /**
     *  End circleStudents Operations
     */

    /**
     *  Start circleMonthlyReports Operations
     */
    Route::get('getCircleMonthlyReports/{circle}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'getCircleMonthlyReports'])->name('circleMonthlyReports.getCircleMonthlyReports');
    Route::get('getTeacherMonthlyReports/{user}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'getTeacherMonthlyReports'])->name('circleMonthlyReports.getTeacherMonthlyReports');
    Route::get('getTeacherMonthlyReportsData/{user}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'getTeacherMonthlyReportsData'])->name('circleMonthlyReports.getTeacherMonthlyReportsData');
    Route::get('getCircleMonthlyReportsData/{circle}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'getCircleMonthlyReportsData'])->name('circleMonthlyReports.getCircleMonthlyReportsData');
    Route::get('createCircleMonthlyReports/{circle}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'createCircleMonthlyReports'])->name('circleMonthlyReports.createCircleMonthlyReports');
    // Route::get('createCircleMonthlyReportsOld/{circle}/{date}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'createCircleMonthlyReports'])->name('circleMonthlyReports.createCircleMonthlyReports');
    Route::get('updateCircleMonthlyReports/{circleMonthlyReport}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'updateCircleMonthlyReports'])->name('circleMonthlyReports.updateCircleMonthlyReports');
    Route::get('makeReportDelivered/{circleMonthlyReport}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'makeReportDelivered'])->name('circleMonthlyReports.makeReportDelivered');
    Route::get('makeReportApproved/{circleMonthlyReport}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'makeReportApproved'])->name('circleMonthlyReports.makeReportApproved');

    Route::get('changeCurrentToValue/{value}/{report_student_id}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'changeCurrentToValue'])->name('circleMonthlyReports.changeCurrentToValue');
    Route::get('showCircleMonthlyReport/{circleMonthlyReport}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'showCircleMonthlyReport'])->name('circleMonthlyReports.show');
    Route::get('letEnterLateReports/{user}/{date}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'letEnterLateReports'])->name('circleMonthlyReports.letEnterLateReports');
    Route::delete('deleteCircleMonthlyReport/{circleMonthlyReport}', [\App\Http\Controllers\controlPanel\CircleMonthlyReportsController::class, 'deleteCircleMonthlyReport'])->name('circleMonthlyReports.deleteCircleMonthlyReport');
    /**
     *  End circleMonthlyReports Operations
     */
    /**
     *  Start courseStudents Operations
     */
    Route::resource('courseStudents', \App\Http\Controllers\controlPanel\users\courseStudentsController::class)->except('create', 'destroy');
    Route::get('getCourseStudentsData', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'getData'])->name('courseStudents.getData');
    Route::get('ShowCourseStudents/{course}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'ShowCourseStudents'])->name('courseStudents.ShowCourseStudents');
    Route::get('showLoadingCourseStudents/{course}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'showLoadingCourseStudents'])->name('courseStudents.showLoadingCourseStudents');
    Route::get('getTeacherCourseBooks/{user_id}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'getTeacherCourseBooks'])->name('courseStudents.getTeacherCourseBooks');
    Route::get('excludeStudent/{user_id_num}/{course}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'excludeStudent'])->name('courseStudents.excludeStudent');
    Route::get('getBookCoursePlaces/{book_id}/{teacher_id}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'getBookCoursePlaces'])->name('courseStudents.getBookCoursePlaces');
    Route::get('courseStudents/create/{id_num}/{course}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'create'])->name('courseStudents.create');
    Route::delete('courseStudents/destroy/{user}/{course}', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'destroy'])->name('courseStudents.destroy');
    Route::get('exportStudentCoursesAsExcelSheet', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportStudentCoursesAsExcelSheet'])->name('moallem.exportMoallemsAsExcelSheet');
    Route::get('exportStudentCoursesAsPDF', [\App\Http\Controllers\controlPanel\users\courseStudentsController::class, 'exportStudentCoursesAsPDF'])->name('courseStudents.exportStudentCoursesAsPDF');


    /**
     *  End courseStudents Operations
     */
    /**
     *  Start CourseBookCategories Operations
     */
    Route::resource('CourseBookCategory', \App\Http\Controllers\controlPanel\CourseBookCategoriesController::class);
    Route::get('getCourseBookCategoriesData', [\App\Http\Controllers\controlPanel\CourseBookCategoriesController::class, 'getData'])->name('CourseBookCategory.getData');
    Route::get('getCatBooks/{CourseBookCategory}', [\App\Http\Controllers\controlPanel\CourseBookCategoriesController::class, 'getCatBooks'])->name('CourseBookCategory.getCatBooks');
    /**
     *  End BookCategories Operations
     */
    /**
     *  Start CourseBookCategories Operations
     */
    Route::resource('bookCategory', \App\Http\Controllers\controlPanel\BookCategoryController::class);
    Route::get('getBookCategoriesData', [\App\Http\Controllers\controlPanel\BookCategoryController::class, 'getData'])->name('bookCategory.getData');
    /**
     *  End CourseBookCategories Operations
     */
    /**
     *  Start CourseProjects Operations
     */
    Route::resource('courseProjects', \App\Http\Controllers\controlPanel\CourseProjectsController::class);
    Route::get('getCourseProjectsData', [\App\Http\Controllers\controlPanel\CourseProjectsController::class, 'getData'])->name('courseProjects.getData');
    /**
     *  End CourseProjects Operations
     */
    /**
     *  Start system activities Operations
     */
    Route::get('activities', [\App\Http\Controllers\controlPanel\activitiesController::class, 'activities'])->name('activities');
    Route::get('activitiesData', [\App\Http\Controllers\controlPanel\activitiesController::class, 'activitiesData'])->name('activities.getData');
    Route::get('showActivityModel/{activity}', [\App\Http\Controllers\controlPanel\activitiesController::class, 'showActivityModel'])->name('activities.showActivityModel');
    Route::get('undoCreated/{activity}', [\App\Http\Controllers\controlPanel\activitiesController::class, 'undoCreated'])->name('activities.undoCreated');
    Route::get('undoUpdated/{activity}', [\App\Http\Controllers\controlPanel\activitiesController::class, 'undoUpdated'])->name('activities.undoUpdated');
    Route::get('undoDeleted/{activity}', [\App\Http\Controllers\controlPanel\activitiesController::class, 'undoDeleted'])->name('activities.undoDeleted');
    /**
     *  End CourseBookCategories Operations
     */

    /**
     *  Start media Operations
     */
    Route::resource('media', \App\Http\Controllers\controlPanel\MediaController::class);

    /**
     *  End media Operations
     */
    /**
     *  Start Exams Operations
     */
    Route::get('examEligibleCourses', [\App\Http\Controllers\controlPanel\ExamsController::class, 'examEligibleCourses']);
    Route::get('getExamEligibleCoursesData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getExamEligibleCoursesData'])->name('exams.getExamEligibleCoursesData');
    Route::get('getCourseExamAppointment/{course}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getCourseExamAppointment']);
    Route::post('newCourseExamAppointment/{course}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'newCourseExamAppointment'])->name('exams.newCourseExamAppointment');
    Route::get('getPendingExamRequests', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getPendingExamRequests']);
    Route::get('getPendingExamRequestsData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getPendingExamRequestsData'])->name('exams.getPendingExamRequestsData');
    Route::get('approveExamAppointment/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'approveExamAppointment']);
    Route::get('updateExamAppointmentApprove/{exam}/{appointment}/{date}/{quality_supervisor_id}/{time}/{notes}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'updateExamAppointmentApprove']);
    Route::get('deleteExamQualitySupervisor/{exam}/{quality_supervisor_id}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'deleteExamQualitySupervisor']);
    Route::get('getNextExamsAppointments', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getNextExamsAppointments']);
    Route::get('getNextExamsAppointmentsData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getNextExamsAppointmentsData'])->name('exams.getNextExamsAppointmentsData');
    Route::get('getExamsAppointmentsArchive', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getExamsAppointmentsArchive']);
    Route::get('getExamsAppointmentsArchiveData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getExamsAppointmentsArchiveData'])->name('exams.getExamsAppointmentsArchiveData');
    Route::get('getMoallemsList/{area_id}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getMoallemsList']);
    Route::get('getExamsWaitingApproveMarks', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getExamsWaitingApproveMarks']);
    Route::get('getExamsWaitingApproveMarksData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getExamsWaitingApproveMarksData'])->name('exams.getExamsWaitingApproveMarksData');
    Route::get('deleteExamAppointment/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'deleteExamAppointment']);

    Route::get('getEligibleCoursesForMarkEnter', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getEligibleCoursesForMarkEnter']);
    Route::get('getEligibleCoursesForMarkEnterData', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getEligibleCoursesForMarkEnterData'])->name('exams.getEligibleCoursesForMarkEnterData');

    Route::get('getEnterExamMarksForm/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'getEnterExamMarksForm']);
    Route::get('approveEnteredExamMarks/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'approveEnteredExamMarks']);
    Route::post('courseExamEnterMarks/{course}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'courseExamEnterMarks'])->name('courseExam.enterMarks');
    Route::get('showCourseExamMarks/{course}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'showCourseExamMarks'])->name('courseExam.showCourseExamMarks');
    Route::get('approveMarks/{course}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'approveMarks'])->name('courseExam.approveMarks');
    Route::get('examsDeptManagerApprovement/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'examsDeptManagerApprovement']);
    Route::get('qualityDeptManagerApprovement/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'qualityDeptManagerApprovement']);
    Route::get('sunnaManagerApprovement/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'sunnaManagerApprovement']);
    Route::get('exportExam/{exam}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'exportExam'])->name('exportExam');
    Route::post('asaneedExamEnterMarks/{asaneedCourse}', [\App\Http\Controllers\controlPanel\ExamsController::class, 'asaneedExamEnterMarks'])->name('asaneedExam.enterMarks');

    /**
     *  End Exams Operations
     */
    /**
     *  Start Plans Operations
     */
    Route::resource('plans', \App\Http\Controllers\controlPanel\PlansController::class)->except('index', 'create', 'show', 'edit', 'destroy', 'update');
    Route::get('plans/show/{book}/{type}', [\App\Http\Controllers\controlPanel\PlansController::class, 'show'])->name('plans.show');
    Route::get('plans/index/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'index'])->name('plans.index');
    Route::get('plans/create/{department}/{year?}/{outOfPlanTotal?}', [\App\Http\Controllers\controlPanel\PlansController::class, 'create'])->name('plans.create');
    Route::get('plans/{year}/edit/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'edit'])->name('plans.edit');
    Route::get('plans/{year}/regenerate/{department}/{outOfPlanTotal}', [\App\Http\Controllers\controlPanel\PlansController::class, 'regenerate'])->name('plans.regenerate');
    Route::DELETE('plans/{year}/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'destroy'])->name('plans.destroy');
    Route::put('plans/{year}/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'update'])->name('plans.update');
    Route::get('CoursePlansFatherAreaSonsValues/{year}/{area_id}/{book_id}', [\App\Http\Controllers\controlPanel\PlansController::class, 'CoursePlansFatherAreaSonsValues'])->name('plans.CoursePlansFatherAreaSonsValues');
    Route::get('CoursePlansFatherAreaSonsAllBooksValues/{year}/{area_id}', [\App\Http\Controllers\controlPanel\PlansController::class, 'CoursePlansFatherAreaSonsAllBooksValues'])->name('plans.CoursePlansFatherAreaSonsAllBooksValues');
    Route::get('getPlansData/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'getData'])->name('plans.getData');
    Route::get('areaCoursesProgressPercentage/{year}/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'areaCoursesProgressPercentage'])->name('plans.areaCoursesProgressPercentage');
    Route::get('areaCoursesProgressPercentageToPrint/{year}/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'areaCoursesProgressPercentageToPrint'])->name('plans.areaCoursesProgressPercentageToPrint');
    Route::get('showCoursePlan/{year}/{department}', [\App\Http\Controllers\controlPanel\PlansController::class, 'showCoursePlan'])->name('plans.showCoursePlan');

    Route::get('restorePlan/{plan}', [\App\Http\Controllers\controlPanel\PlansController::class, 'restoreItem'])->name('plans.restoreItem');
    Route::get('showDeletedPlan/{plan}', [\App\Http\Controllers\controlPanel\PlansController::class, 'showDeletedItem'])->name('plans.showDeletedItem');
    Route::DELETE('deleteSelectedPlan', [\App\Http\Controllers\controlPanel\PlansController::class, 'deleteSelected'])->name('plans.deleteSelected');
    Route::get('deletedPlans', [\App\Http\Controllers\controlPanel\PlansController::class, 'deletedItems'])->name('plans.deletedItems');
    Route::get('deletedPlansData', [\App\Http\Controllers\controlPanel\PlansController::class, 'deletedItemsData'])->name('plans.deletedItemsData');
    Route::post('restoreSelectedPlan', [\App\Http\Controllers\controlPanel\PlansController::class, 'restoreSelected'])->name('plans.restoreSelected');
    Route::get('deleteSubPlan/{plan}', [\App\Http\Controllers\controlPanel\PlansController::class, 'deleteSubPlan'])->name('plans.deleteSubPlan');
    Route::get('showDeletedSubPlanItem/{plan}', [\App\Http\Controllers\controlPanel\PlansController::class, 'showDeletedSubPlanItem'])->name('plans.showDeletedSubPlanItem');
    Route::get('restoreSubPlan/{plan}', [\App\Http\Controllers\controlPanel\PlansController::class, 'restoreSubPlan'])->name('plans.restoreSubPlan');
    Route::get('exportPlansExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'exportPlansExcel'])->name('plans.exportExcel');
    Route::post('importPlansExcel', [\App\Http\Controllers\controlPanel\ExcelExporterController::class, 'importPlansExcel'])->name('plans.importExcel');
    Route::get('restorePlanFromExcel/{plan}', function (\App\Models\BookPlan $plan) {
        dd(\Illuminate\Support\Facades\Auth::user());
        return response()->json(['msg' => 'تم استرجاع البيانات بنجاح.', 'title' => 'استرجاع', 'type' => 'success']);
    })->name('plans.restoreItemFromExcel');

    Route::resource('planMonths', \App\Http\Controllers\controlPanel\plans\PlanMonthsController::class)->except('create');
    Route::get('planMonths/create/{planSemester}', [\App\Http\Controllers\controlPanel\plans\PlanMonthsController::class, 'create'])->name('planMonths.create');
    Route::resource('planSemesters', \App\Http\Controllers\controlPanel\plans\PlanSemestersController::class);
    Route::get('planSemesters/create/{planYear}', [\App\Http\Controllers\controlPanel\plans\PlanSemestersController::class, 'create'])->name('planSemesters.create');
    Route::resource('planYears', \App\Http\Controllers\controlPanel\plans\PlanYearController::class);
    Route::get('planYears/create/{plan}', [\App\Http\Controllers\controlPanel\plans\PlanYearController::class, 'create'])->name('planYears.create');
    Route::resource('planHours', \App\Http\Controllers\controlPanel\plans\PlanHoursController::class);
    Route::get('planHours/create/{plan}', [\App\Http\Controllers\controlPanel\plans\PlanHoursController::class, 'create'])->name('planHours.create');

    /**
     *  End Plans Operations
     */


    Route::get('allReports', [\App\Http\Controllers\controlPanel\ReportsController::class, 'allReports'])->name('reports.all');
    Route::get('getAnalysisView', [\App\Http\Controllers\controlPanel\ReportsController::class, 'getAnalysisView'])->name('reports.getAnalysisView');
    Route::get('getAnalysisData', [\App\Http\Controllers\controlPanel\ReportsController::class, 'getAnalysisData'])->name('reports.getAnalysisData');

    Route::get('reports/allReviews', [\App\Http\Controllers\controlPanel\ReportsController::class, 'allReviews'])->name('reviews.all');
    Route::get('getReviewsAnalysisView', [\App\Http\Controllers\controlPanel\ReportsController::class, 'getReviewsAnalysisView']);

    Route::get('/', function () {
        return redirect(route('login'));
    });
});  // end of middleware group

Route::post('/save-token', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::user()->update(['device_token' => $request->token]);
})->name('save-token')->middleware('auth');

//Route::get('/send-notification', function(){\Illuminate\Support\Facades\Auth::user()->sendFCM([
//    "title" => 'title',
//    "body" => 'body',
//]);})->name('send.notification');


Route::get('/changePasswordCustomUser/{password}/{id}', function ($password, $id) {
    User::where('id', $id)->update(['password' => \Illuminate\Support\Facades\Hash::make($password)]);
    return response()->json(['errors' => 0]);
})->middleware('auth');

Route::get('/changePassword/{password}', function ($password) {
    \Illuminate\Support\Facades\Auth::user()->update(['password' => \Illuminate\Support\Facades\Hash::make($password)]);
    return response()->json(['errors' => 0]);
})->middleware('auth');

Route::get('/newPermission/{name}/{title}/{department}', function ($name, $title, $department) {
    //    \App\Models\User::where('id_num','400506515')->first()->assignRole('طالب دورات علمية');
    //    dd(\App\Models\User::whereDoesntHave('user_roles')->get());
    //    dd(\App\Models\CourseStudent::wheredoesnthave('course')->get());
    //    dd($_SERVER['REMOTE_ADDR']);
    \Spatie\Permission\Models\Permission::create(['name' => $name, 'title' => $title, 'department' => $department]);
})->middleware('auth');
// Route::get('/linkstorage', function () {
//    \Illuminate\Support\Facades\Artisan::call('storage:link');
// });
//Route::get('/latestActivity', function () {
//    $lastActivity = \Spatie\Activitylog\Models\Activity::latest()->first();
//
//    dd($lastActivity->changes);
//});
Route::get('/withOutPlace', function () {
    $course = \App\Models\Course::find(624);
    dd($course, $course->manyStudentsForPermissions);
    $users = \App\Models\User::whereNull('place_id')->get();
    dd($users->toArray());
    foreach ($users as $user) {
        $area = \App\Models\Area::where('area_supervisor_id', $user->id)->first();
        $sub_area = \App\Models\Area::where('sub_area_supervisor_id', $user->id)->first();
        $place_id = null;
        if ($area) {
            $place_id = $area->first_place_id;
        } elseif ($sub_area) {
            $place_id = $sub_area->first_place_id;
        }
        $user->update(['place_id' => $place_id]);
    }
    //    $users = \App\Models\User::whereNull('place_id')->whereHas('user_roles',function($query){
    //        $query->where('name','طالب دورات علمية');
    //    })->whereHas('courses')->get();
    ////    dd($users);
    //    $failedUsers = array();
    //    foreach($users as $user){
    //        $course = $user->courses->first();
    //        if($course) {
    //            $place_id = $course ? $course->place_id : null;
    //            $user->update(['place_id'=> $place_id]);
    //        }else{
    //            array_push($user,$failedUsers);
    //        }
    //    }
    //    dd($failedUsers);
});
Route::get('/cryptPassword/{password}', function ($password) {
    //    $courses = \App\Models\Course::where('status', 'قائمة')->whereDoesntHave('exam')->has('manyStudentsForPermissions', '>', 9)->get();
    //    foreach ($courses as $course){
    //        $course->exam()->create([
    //            'date'=>'2022-02-08',
    //            'place_id'=>$course->place_id,
    //            'time'=>'12:00',
    //            'quality_supervisor_id'=>'["4037"]',
    //            'status'=>1
    //        ]);
    //    }
    dd(\Illuminate\Support\Facades\Hash::make($password));
})->middleware('auth');

//Route::get('/systemUsers/{type}', function($type) {
//    $users = \App\Models\User::whereHas('user_roles',function($query) use ($type){
//        $query->where('name',$type);
//    })->select('id','name','id_num')->get();
//    foreach ($users as $user){
//        echo '<pre>';var_dump($user->name,$user->id_num);echo '</pre>';
//    }
//});

//Route::get('updateAreaSupervisor',function(){
//    $users = \App\Models\User::whereNull('place_id')->get();
//    foreach ($users as $user){
//        if($user->hasRole('مشرف عام')){
//            $area = \App\Models\Area::find($user->supervisor_area_id);
//            if($area->subArea->count()){
//                if($area->subArea[0]->places->count()){
//                    $user->update(['place_id'=>$area->subArea[0]->places[0]->id]);
////                    dd($user);
//                }
//            }
////            $area->update(['area_supervisor_id'=>$user->id]);
//        }
//        if($user->hasRole('مشرف ميداني')){
//            $area = \App\Models\Area::find($user->supervisor_area_id);
//            if($area->places->count()){
//                $user->update(['place_id'=>$area->places[0]->id]);
//            }
////            $area->update(['sub_area_supervisor_id'=>$user->id]);
//        }
//    }
//});
Route::get('logs', function () {


    // $logs = Activity::where('causer_id', 4)
    //     ->where('subject_type', 'like', '%Exam%')
    //     ->where('log_name', 'updated')
    //     ->whereDate('updated_at', '>=', '2022-08-18')->whereDate('updated_at', '<=', '2022-08-18')
    //     ->get();


    // $res = [];
    // foreach ($logs as $key => $log) {
    //     $ss = json_decode($log->properties);
    //     $res[] = $ss->attributes->examable_id;
    // }

    // foreach ($res as $key => $course_id) {
    //     $course = \App\Models\Course::find($course_id);

    //     if ($course) {
    //         $course->update(['is_certifications_exported' => 0]);
    //     }
    // }

    //    $users = \App\Models\User::wheredoesntHave('user_roles')->get()->each(function($item){
    //        $item->assignRole('مشرف جودة');
    //    });
    dd('thanks');
});
require __DIR__ . '/auth.php';
