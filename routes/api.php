<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf']); // ✅ MUST come first
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'sendInvoiceEmail']);
    Route::get('/dashboard/stats', [InvoiceController::class, 'dashboardStats']);
    Route::post('/branding', [BrandingController::class, 'update']);

});


