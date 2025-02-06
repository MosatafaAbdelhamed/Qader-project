<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginControllerOrg;
use App\Http\Controllers\Api\LoginControllerVol;

use App\Http\Controllers\Api\RegisterControllerOrg;
use App\Http\Controllers\Api\RegisterControllerVol;
use App\Http\Controllers\Api\PasswordResetController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OpportunitiesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OrganizationController;



Route::get('test' , [\App\Http\Controllers\TestApiController::class,'test']);

Route::post('/register/organization' , [RegisterControllerOrg::class,'register']);
Route::post('/login/organization' , [LoginControllerOrg::class,'login_org']);
Route::post('/logout' , [LoginControllerOrg::class,'logout'])->middleware('auth:sanctum');

Route::post('/register/volunteer' , [RegisterControllerVol::class,'registervol']);
Route::post('/login/volunteer' , [LoginControllerVol::class,'login_vol']);
Route::post('/logout' , [LoginControllerVol::class,'logout'])->middleware('auth:sanctum');




// Route::post('/password/email' , [PasswordResetController::class,'sendResetLinkEmail'])->middleware('auth:sanctum');
// Route::post('/password/reset' , [PasswordResetController::class,'reset'])->name('password.reset')->middleware('signed');


Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/opportunities', [OpportunitiesController::class, 'index']);
Route::get('/search', [SearchController::class, 'search']);

Route::post('/organizations', [OrganizationController::class, 'index']);
