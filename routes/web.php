<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('rezervasyon')->group(function(){
    Route::get('/', [ReservationController::class, 'index'])->name('index');
    Route::get('/tren/{name}', [ReservationController::class, 'show'])->name('show');
    Route::post('/tren/{name}', [ReservationController::class, 'update'])->name('update');
});