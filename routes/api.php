<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\NewsController;
use App\Http\Middleware\RoleMiddleWare;

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

Route::group([

    'middleware' => 'api',
    
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
    Route::post('regist', [AuthController::class, "regist"]);
    Route::post('refresh', [AuthController::class, "refreshed"]);
});

Route::post('regist', [AuthController::class, "regist"]);
Route::get('init', [AuthController::class, "init"]);

Route::group([
    "middleware" => "jwt.auth",
    "prefix" => "admin",
], function ($router) {
    Route::post("createnews", [NewsController::class, "create"]);
    Route::delete("deletenews", [NewsController::class, "delete"]);
    Route::post("updatenews", [NewsController::class, "update"]);
    Route::post('create', [ProductController::class, "create"])->middleware(RoleMiddleware::class);
    Route::post('update', [ProductController::class, "update"])->middleware(RoleMiddleware::class);
    Route::delete('delete', [ProductController::class, "delete"])->middleware(RoleMiddleware::class);
    Route::delete('deletecategory', [CategoryController::class, "delete"])->middleware(RoleMiddleware::class);
    Route::post('updatecategory', [CategoryController::class, "update"])->middleware(RoleMiddleware::class);
    Route::post('createcategory', [CategoryController::class, "create"])->middleware(RoleMiddleware::class);
    Route::get('getallorders', [OrderController::class, "getAll"])->middleware(RoleMiddleware::class);
    Route::post('editorder', [OrderController::class, "editOrder"])->middleware(RoleMiddleware::class);
});

Route::get('getallcategory', [CategoryController::class, "get"]);
Route::post("file", [ProductController::class, "getFile"]);
Route::get('getall', [ProductController::class, "get"]);
Route::post('getone', [ProductController::class, "getOne"]);

Route::group([
    "middleware" => "jwt.auth",
    "prefix" => "order"
], function ($route) {
    Route::post("create", [OrderController::class, "create"]);
    Route::get("getorderuser", [OrderController::class, "getOrderUser"]);
});

Route::group([
    "middleware" => "jwt.auth",
    "prefix" => "basket"
], function ($route) {
    Route::post("create", [BasketController::class, "create"]);
    Route::get("getuserbasket", [BasketController::class, "getUserBasket"]);
    Route::delete("delete", [BasketController::class, "delete"]);
    Route::put("updatecount", [BasketController::class, "updateCount"]);
});

Route::get("getnews", [NewsController::class, 'get']);