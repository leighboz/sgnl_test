<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//search for employee record by RFID card number (cn)https://api.domain.com/some/endpoint?cn=not_found 
Route::get('/some/endpoint', [CardAccessController::class, 'index']);