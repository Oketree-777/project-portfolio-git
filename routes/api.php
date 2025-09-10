<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\AuthApiController;

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

// Public API Routes
Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
    
    // Public Portfolio Routes
    Route::get('/portfolios', [PortfolioApiController::class, 'index']);
    Route::get('/portfolios/{portfolio}', [PortfolioApiController::class, 'show']);
    Route::get('/portfolios/faculty/{faculty}', [PortfolioApiController::class, 'byFaculty']);
    Route::get('/portfolios/major/{major}', [PortfolioApiController::class, 'byMajor']);
    Route::get('/portfolios/search', [PortfolioApiController::class, 'search']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        // User Profile
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        Route::put('/profile/password', [UserApiController::class, 'updatePassword']);
        
        // User Portfolios
        Route::get('/my-portfolios', [PortfolioApiController::class, 'myPortfolios']);
        Route::post('/portfolios', [PortfolioApiController::class, 'store']);
        Route::put('/portfolios/{portfolio}', [PortfolioApiController::class, 'update']);
        Route::delete('/portfolios/{portfolio}', [PortfolioApiController::class, 'destroy']);
        
        // Documents
        Route::get('/documents', [DocumentApiController::class, 'index']);
        Route::post('/documents', [DocumentApiController::class, 'store']);
        Route::get('/documents/{document}', [DocumentApiController::class, 'show']);
        Route::put('/documents/{document}', [DocumentApiController::class, 'update']);
        Route::delete('/documents/{document}', [DocumentApiController::class, 'destroy']);
        Route::get('/documents/{document}/download', [DocumentApiController::class, 'download']);
    });
    
    // Admin Routes
    Route::middleware(['auth:sanctum', 'admin.access'])->group(function () {
        Route::get('/admin/statistics', [UserApiController::class, 'statistics']);
        Route::get('/admin/users', [UserApiController::class, 'index']);
        Route::get('/admin/users/{user}', [UserApiController::class, 'show']);
        Route::put('/admin/users/{user}', [UserApiController::class, 'update']);
        Route::delete('/admin/users/{user}', [UserApiController::class, 'destroy']);
        
        // Document Approval
        Route::post('/admin/documents/{document}/approve', [DocumentApiController::class, 'approve']);
        Route::post('/admin/documents/{document}/reject', [DocumentApiController::class, 'reject']);
        Route::get('/admin/documents/pending', [DocumentApiController::class, 'pending']);
        Route::get('/admin/documents/approved', [DocumentApiController::class, 'approved']);
        Route::get('/admin/documents/rejected', [DocumentApiController::class, 'rejected']);
    });
});
