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


 
 
Route::apiResource('/faq','App\Http\Controllers\Api\Faqs\FaqController'); 
Route::apiResource('/faqs/faq-category','App\Http\Controllers\Api\Faqs\FaqCategoryController'); 
Route::apiResource('/product','App\Http\Controllers\Api\Products\ProductController'); 
Route::apiResource('/page','App\Http\Controllers\Api\Pages\PageController');  
Route::apiResource('/pages/page-category','App\Http\Controllers\Api\Pages\PageCategoryController'); 