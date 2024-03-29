<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\DestinasiController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\ETicketController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\AdminTransactionController;
use App\Http\Controllers\API\AuthControllerUser;
use App\Http\Controllers\API\AuthControllerOwnerBusiness;

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
Route::get('destinasi/byowner/{id_owner}', [DestinasiController::class, 'showByIdOwner']);
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
Route::put('ticket/update/{id}', [TicketController::class, 'update']);
Route::get('ticketsold', [TicketController::class, 'getTicketSoldByDestination']);
Route::get('ticketsold/ownerinmonth', [TicketController::class, 'getTicketSoldByDestinationInMonth']);
Route::get('ticketsold/ownerinyear', [TicketController::class, 'getTicketSoldByDestinationInYear']);
Route::get('ticketsold/ownerinweek', [TicketController::class, 'getTicketSoldByDestinationInWeek']);
Route::get('ticketsold/owner', [TicketController::class, 'getTicketSoldByIdOwner']);
Route::get('ticket/mostsales/owner', [TicketController::class, 'showByMostSoldTicket']);



//E-TICKET
Route::get('eticket', [ETicketController::class, 'index']);
Route::get('eticket/{id}', [ETicketController::class, 'show']);

Route::get('eticket/byowner/{id}', [ETicketController::class, 'showByIdOwner']);
Route::get('eticket/byuser/{id}', [ETicketController::class, 'showByIdUser']);
Route::get('eticket/byticket/{id}', [ETicketController::class, 'showByIdTicket']);
Route::post('eticket', [ETicketController::class, 'store']);
Route::get('eticket/byowner/year/{id_owner}', [ETicketController::class, 'getETicketByYearOwner']);
Route::get('eticket/byowner/month/{id_owner}', [ETicketController::class, 'getETicketByMonthOwner']);
Route::get('eticket/byowner/week/{id_owner}', [ETicketController::class, 'getETicketByWeekOwner']);


//ADMIN TRANSACTION
Route::get('adminprice', [AdminTransactionController::class, 'index']);

//REVIEW
Route::get('/rating/{id}', [ReviewController::class, 'getRating']);
Route::get('/rating/byowner/{id_owner}', [ReviewController::class, 'getRatingByIdOwner']);
Route::get('review', function () {
    return \App\Models\Review::with('destinasi', 'user')->get();
});
Route::post('review', [ReviewController::class, 'store']);
Route::get('review/{id}', [ReviewController::class, 'show']);
Route::get('review/all/owner/{id}', [ReviewController::class, 'showByIdUser']);

// Route::get('review/{id}', function () {
//     return \App\Models\Review::with('user')->get();
// });



//USER AUTHENTICATION
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthControllerUser::class, 'logout']);
});
Route::post('register', [AuthControllerUser::class, 'register']);
Route::post('login', [AuthControllerUser::class, 'login']);
Route::put('user/{id}', [AuthControllerUser::class, 'update']);
Route::get('user', [AuthControllerUser::class, 'index']);
Route::get('user/{id}', [AuthControllerUser::class, 'show']);
Route::get('/email/user', 'App\Http\Controllers\API\AuthControllerUser@email');

//OWNER BUSINESS USER AUTHENTICATION
Route::post('owner/register', [AuthControllerOwnerBusiness::class, 'register']);
Route::post('owner/login', [AuthControllerOwnerBusiness::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('owner/logout', [AuthControllerOwnerBusiness::class, 'logout']);
});
Route::get('owner/{id}', [AuthControllerOwnerBusiness::class, 'show']);
Route::put('owner/{id}', [AuthControllerOwnerBusiness::class, 'update']);
Route::get('owner', [AuthControllerOwnerBusiness::class, 'index']);








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
