<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\CreativeWork;
use App\Http\Controllers\CreativeWorkController;
use App\Http\Controllers\PersonController;


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

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/search', function () {
    return view('pages.search');
});

Route::get('/people', function () {
    return view('pages.people');
});

// Route::get('/creativework/{id}', function ($id) {
//     return view('pages.creativework', compact('id'));
// })->where('id', '[0-9]+');


// Route::get('creativework/{id}','App\Http\Controllers\CreativeWorkController@view')->where('id', '[0-9]+');

Route::get('/creativework/{id}', [CreativeWorkController::class, 'view'])->name('record.view');

Route::get('/person/{id}', [PersonController::class, 'view'])->name('record.view');
