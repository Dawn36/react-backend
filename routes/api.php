<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\MovieController;


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

Route::post('/register', [AuthController::class, 'register']);
Route::get('/siteMap', [SiteController::class, 'siteMap']);
Route::post('/login', [AuthController::class, 'login']);

Route::resource('movies', MovieController::class);

Route::middleware('jwt.verify')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/siteMapDetails', [SiteController::class, 'siteMapDetails']);
Route::prefix('api')->group(function () {
    // Route::post('/register', 'Api\AuthController@register');
});

// Route::get('/site_map', [SiteController::class, 'siteMap'])->name('site_map');
// Route::post('/login', [AuthController::class, 'login'])->name('login');




// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
