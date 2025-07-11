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

use App\Http\Controllers\OrganizationProfileController;
use App\Http\Controllers\VolunteerProfileController;

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\VolunteerNotificationController;
use App\Http\Controllers\OrganizationNotificationController;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\EmailVerificationController;


// Route::get('/home', [HomeController::class, 'index']);
// Route::get('test' , [\App\Http\Controllers\TestApiController::class,'test']);

Route::post('/register/organization' , [RegisterControllerOrg::class,'register']);
Route::post('/login/organization' , [LoginControllerOrg::class,'login_org']);
Route::post('/logout' , [LoginControllerOrg::class,'logout'])->middleware('auth:sanctum');

Route::post('/register/volunteer' , [RegisterControllerVol::class,'registervol']);
Route::post('/login/volunteer' , [LoginControllerVol::class,'login_vol']);
Route::post('/logout' , [LoginControllerVol::class,'logout'])->middleware('auth:sanctum');



Route::get('/categories', [CategoriesController::class, 'getCategories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/opportunities', [OpportunitiesController::class, 'index']);
    Route::get('/search-opportunities', [OpportunitiesController::class, 'search']);
    Route::get('/opportunities/clusters', [OpportunitiesController::class, 'getClusters']);
    Route::get('/opportunities/cluster/{cluster}', [OpportunitiesController::class, 'searchByCluster']);
    Route::get('/opportunities/cluster-summary', [OpportunitiesController::class, 'getClusterSummary']);
    //
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/search', [OrganizationController::class, 'search']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organization/profile/show', [OrganizationProfileController::class, 'show']);
    Route::patch('/organization/profile/update', [OrganizationProfileController::class, 'update']);
    Route::post('/organization/profile/update-image', [OrganizationProfileController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/volunteer/profile/show', [VolunteerProfileController::class, 'show']);
    Route::patch('/volunteer/profile/update', [VolunteerProfileController::class, 'update']);
    Route::post('/volunteer/profile/update-image', [VolunteerProfileController::class, 'update']);

});

Route::middleware('auth:sanctum')->get('/volunteer/applications', [ApplicationController::class, 'myApplications']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/opportunities/{opportunity_id}/apply', [ApplicationController::class, 'apply']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('applications/{id}/status', [ApplicationController::class, 'updateStatus']);
});


Route::middleware('auth:sanctum')->get('/organization/notifications', [OrganizationNotificationController::class, 'index']);
Route::middleware('auth:sanctum')->post('/organization/notifications/{id}/read', [OrganizationNotificationController::class, 'markAsRead']);


Route::middleware('auth:sanctum')->get('/volunteer/notifications', [VolunteerNotificationController::class, 'index']);
Route::middleware('auth:sanctum')->post('/volunteer/notifications/{id}/read', [VolunteerNotificationController::class, 'markAsRead']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-opportunities', [OpportunitiesController::class, 'myOpportunities']);
    Route::post('/opportunities', [OpportunitiesController::class, 'store']);
    Route::put('/opportunities/{opportunity_id}', [OpportunitiesController::class, 'update']);
    Route::delete('/opportunities/{opportunity_id}', [OpportunitiesController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
});

Route::middleware('auth:sanctum')->post('/reports/create', [ReportController::class, 'store']);

Route::get('/reports/show', [ReportController::class, 'index']);




Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('reset-password/{user_type}/{token}', [PasswordResetController::class, 'reset']);


Route::post('email/verification-notification', [\App\Http\Controllers\Api\EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');



