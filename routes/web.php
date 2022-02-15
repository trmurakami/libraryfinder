<?php

use Illuminate\Support\Facades\Route;

use App\Models\CreativeWork;


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

// Route::get('/search', function () {
//     $creative_works = CreativeWork::get();

//     return view('search', [
//         'creative_works' => $creative_works
//     ]);
// });