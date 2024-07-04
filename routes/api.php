<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LaneController;
use App\Http\Controllers\Api\TicketController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/lanes', [LaneController::class, 'index']);
Route::put('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'update']);
Route::delete('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'delete']);


Route::post('/tickets', [TicketController::class, 'store']);
Route::put('/tickets/move', [TicketController::class, 'moveTicket']);
