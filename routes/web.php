<?php

use App\Http\Controllers\ConcertOrdersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcertsController;

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

Route::get('/concerts/{id}', [ConcertsController::class, 'show']);

Route::post('/concerts/{id}/orders', [ConcertOrdersController::class, 'store']);
