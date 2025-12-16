<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- استيراد الـ Controllers الجديدة ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;

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

// --- المسارات العامة (Public Routes) ---
// لا تحتاج إلى مصادقة
Route::post('/login', [AuthController::class, 'login']);


// --- المسارات المحمية (Protected Routes) ---
// تتطلب مصادقة باستخدام Sanctum
Route::middleware('auth:sanctum')->group(function () {


     Route::get('/dashboard', [DashboardController::class, 'stats'])
         ->middleware('can:dashboard.view') // حماية المسار بالصلاحية
         ->name('dashboard.stats');


          Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/company-statement/{company}', [ReportController::class, 'companyStatement'])
             ->middleware('can:view,company'); // يمكن فقط لمن يرى الشركة أن يرى تقريرها
    });

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);

    // جلب بيانات المستخدم الحالي مع أدواره وصلاحياته
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles:id,name', 'roles.permissions:id,name');
        return response()->json($user);
    });

    // --- مسارات إدارة الأدوار والصلاحيات ---
    // جلب كل الصلاحيات المتاحة في النظام (مفيد عند تعديل دور)
    Route::get('roles/permissions', [RoleController::class, 'getAllPermissions'])->name('roles.permissions');
    Route::apiResource('roles', RoleController::class);

    // --- مسارات إدارة المستخدمين ---
    Route::apiResource('users', UserController::class);


    // --- مسارات إدارة كيانات المشروع ---
    // استخدام apiResource لتعريف كل المسارات (index, store, show, update, destroy) تلقائيًا
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('documents', DocumentController::class)->except(['update']); // استثناء مسار التحديث للمستندات

});
