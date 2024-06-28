<?php


use App\Http\Controllers\LaneController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lanes', [LaneController::class, 'index']);
Route::put('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'update']);
Route::delete('/lanes/{laneId}/tickets/{ticketId}', [TicketController::class, 'delete']);


Route::post('/tickets', [TicketController::class, 'store']);
Route::put('/tickets/move', [TicketController::class, 'moveTicket']);