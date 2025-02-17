<?php

use App\Http\Controllers\AnonymChatController;
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


Route::get('/', [AnonymChatController::class, 'index']);
Route::post('/user', [AnonymChatController::class, 'storeUserParams']);
Route::post('/message', [AnonymChatController::class, 'message']);
Route::post('/disconnect', [AnonymChatController::class, 'disconnect']);

