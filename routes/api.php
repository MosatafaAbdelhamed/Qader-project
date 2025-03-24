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


Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [CategoriesController::class, 'getCategories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/opportunities', [OpportunitiesController::class, 'index']);
    Route::get('/search-opportunities', [OpportunitiesController::class, 'search']);
    //
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/search', [OrganizationController::class, 'search']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-opportunities', [OpportunitiesController::class, 'myOpportunities']);
    Route::post('/opportunities', [OpportunitiesController::class, 'store']);
    Route::put('/opportunities/{opportunity_id}', [OpportunitiesController::class, 'update']);
    Route::delete('/opportunities/{opportunity_id}', [OpportunitiesController::class, 'destroy']);
});

//new updata
Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('reset-password/{token}', [PasswordResetController::class, 'reset'])->middleware('guest')->name('password.update');

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');



