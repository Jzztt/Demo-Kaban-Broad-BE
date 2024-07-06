<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LaneController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\BoardController;

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

//Open Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected Routes
Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});


//Restricted Routes
Route::middleware(['auth:api', 'board.invitation'])->group(function () {
    Route::get('/boards/{board_id}', [BoardController::class, 'show']);
    Route::put('/boards/{board_id}', [BoardController::class, 'update']);
    Route::delete('/boards/{board_id}', [BoardController::class, 'delete']);

    Route::get('/lanes', [LaneController::class, 'index']);
    Route::put('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'update']);
    Route::delete('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'delete']);


    Route::post('/tickets', [TicketController::class, 'store']);
    Route::put('/tickets/move', [TicketController::class, 'moveTicket']);
});
