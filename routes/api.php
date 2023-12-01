<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify/{token}', [AuthController::class, 'verifyUser']);
Route::post('/setSession', [AuthController::class, 'setSession']);
Route::post('/getSession', [AuthController::class, 'getSession']);

Route::post('/prueba', function () {
    return session()->all();
})->middleware('auth:sanctum');

Route::prefix('invoice')->group(function () {
    Route::post('/createInvoice', [InvoiceController::class, 'createInvoice']);
    Route::get('/getInvoices', [InvoiceController::class, 'getInvoices']);
    Route::get('/getInvoice/{id}', [InvoiceController::class, 'getInvoiceById']);
    Route::post('/updateInvoice', [InvoiceController::class, 'updateInvoice']);
    Route::delete('/deleteInvoice/{id}', [InvoiceController::class, 'deleteInvoice']);
    Route::delete('/deleteInvoicePermanently/{id}', [InvoiceController::class, 'deleteInvoicePermanently']);
    Route::get('/generatePdf/{id}', [InvoiceController::class, 'generatePdf']);
    Route::put('/reactivate/{id}', [InvoiceController::class, 'reactivateInvoice']);
});
