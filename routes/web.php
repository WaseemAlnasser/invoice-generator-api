<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PublicInvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/public/invoice/{uuid}', [PublicInvoiceController::class, 'show']);
Route::get('/invoice/{invoice}/download', [PublicInvoiceController::class, 'downloadPdf'])->name('download.invoice.pdf');
