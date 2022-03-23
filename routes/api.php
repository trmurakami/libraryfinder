<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CreativeWorkController;
use App\Http\Controllers\ImportLattesXMLController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\Z3950Controller;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('creative_work')->group(
    function () {
        Route::get('', [CreativeWorkController::class, 'index']);
        Route::get('{id}', [CreativeWorkController::class, 'show']);
    }
);

Route::prefix('person')->group(
    function () {
        Route::get('{id}', [PersonController::class, 'show']);
    }
);

Route::prefix('people')->group(
    function () {
        Route::get('', [PersonController::class, 'index']);
    }
);

Route::prefix('import')->group(
    function () {
        Route::post('lattesxml', [ImportLattesXMLController::class, 'parse']);
    }
);

Route::prefix('facets')->group(
    function () {
        Route::get('simple', [CreativeWorkController::class, 'facetSimple']);
    }
);

Route::get('cover', [CoverController::class, 'show']);

Route::get('z3950', [Z3950Controller::class, 'searchZ3950']);