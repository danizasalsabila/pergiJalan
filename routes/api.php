<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DestinasiController;

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

//INI
// Route::get('destinasi',[DestinasiController::class, 'index']);
Route::get('destinasi', function () {
    $destinasi = DestinasiController::select('name_destinasi')->get();
    return response()->json($destinasi);
});


Route::get('destinasi/{id}',[DestinasiController::class, 'show']);
//or with (under this) code, works too!!
// Route::get('destinasi',[DestinasiController::class, 'index']);
Route::post('destinasi/store', [DestinasiController::class, 'store']);
// Route::get('destinasi/{id}', [DestinasiController::class, 'show']);

// Route::get('/destinasi/{id}', 'DestinasiController@show');

//INI
Route::put('destinasi/update/{id}', [DestinasiController::class, 'update']);

// Route::prefix('/destinasi')->group(function () {
//     Route::get('/', function (Request $request) {
//         $json = json_decode(file_get_contents(public_path('testdata/destinasi/get.json')), true);
//         return response()->json($json);
//     });
// });
