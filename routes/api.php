<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\DestinasiController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\AuthControllerUser;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//DESTINASI
Route::get('destinasi',[DestinasiController::class, 'index']);
Route::post('destinasi/store', [DestinasiController::class, 'store']);
Route::get('destinasi/{id}', [DestinasiController::class, 'show']);
Route::put('destinasi/update/{id}', [DestinasiController::class, 'update']);
Route::delete('destinasi/destroy/{id}', [DestinasiController::class, 'destroy']);
//search Destinasi
// Route::get('destinasi/search/{name_destinasi}', [DestinasiController::class, 'search']);
Route::get('/search/destinasi', 'App\Http\Controllers\API\DestinasiController@search');
Route::get('/city/destinasi', 'App\Http\Controllers\API\DestinasiController@city');
Route::get('/category/destinasi', 'App\Http\Controllers\API\DestinasiController@category');


//TICKET
Route::get('ticket', function () {
    return \App\Models\Ticket::with('destinasi')->get();
});
Route::post('ticket', [TicketController::class, 'store']);
Route::get('ticket/{id}', [TicketController::class, 'show']);
Route::delete('ticket/destroy/{id}', [TicketController::class, 'destroy']);


//REVIEW
Route::get('review', function () {
    return \App\Models\Review::with('destinasi')->get();
});
Route::post('review', [ReviewController::class, 'store']);
Route::get('review/{id}', [ReviewController::class, 'show']);



//USER AUTHENTICATION
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthControllerUser::class, 'logout']);
});
Route::post('register', [AuthControllerUser::class, 'register']);
Route::post('login', [AuthControllerUser::class, 'login']);
Route::get('user', [AuthControllerUser::class, 'index']);
Route::get('user/{id}', [AuthControllerUser::class, 'show']);
Route::get('/email/user', 'App\Http\Controllers\API\AuthControllerUser@email');








// //INI
// Route::get('destinasi',[DestinasiController::class, 'index']);
// // Route::get('destinasi', function () {
// //     $destinasi = DestinasiController::select('name_destinasi')->get();
// //     return response()->json($destinasi);
// // });


// Route::get('destinasi/{id}',[DestinasiController::class, 'show']);
// //or with (under this) code, works too!!
// // Route::get('destinasi',[DestinasiController::class, 'index']);
// Route::post('destinasi/store', [DestinasiController::class, 'store']);
// // Route::get('destinasi/{id}', [DestinasiController::class, 'show']);

// // Route::get('/destinasi/{id}', 'DestinasiController@show');

// //INI
// Route::put('destinasi/update/{id}', [DestinasiController::class, 'update']);

// // Route::prefix('/destinasi')->group(function () {
// //     Route::get('/', function (Request $request) {
// //         $json = json_decode(file_get_contents(public_path('testdata/destinasi/get.json')), true);
// //         return response()->json($json);
// //     });
// // });
