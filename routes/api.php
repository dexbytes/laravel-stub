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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/user','App\Http\Controllers\Api\UserController'); 
Route::apiResource('/faq/faq-category','App\Http\Controllers\Api\Faq\FaqCategoryController'); 
Route::apiResource('/faq','App\Http\Controllers\Api\Faq\FaqController'); 
Route::apiResource('/product','App\Http\Controllers\Api\Product\ProductController'); 
Route::apiResource('/product/product-category','App\Http\Controllers\Api\Product\ProductCategoryController'); 