<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//route / redirect to login

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    if (session()->has('usuario')) {
        return redirect()->route('inicio');
    }
    return view('login');
})->name('login');

Route::get('/iniciar_sesion', [AuthController::class, 'login'])->name('iniciar_sesion');
Route::get('/cerrar_sesion', [AuthController::class, 'logout'])->name('cerrar-sesion');

Route::get('/registrar', function () {
    if (session()->has('usuario')) {
        return redirect()->route('inicio');
    }
    return view('registrar');
});
//Route::get('/inicio', [AuthController::class, 'inicio'])->name('inicio');
Route::get('/inicio', function () {
    if (!session()->has('usuario')) {
        return redirect()->route('login');
    } else {
        return (new AuthController())->inicio();
    }
})->name('inicio');
Route::get('/papelera', function () {
    if (!session()->has('usuario')) {
        return redirect()->route('login');
    } else {
        return (new AuthController())->papelera();
    }
})->name('inicio');
Route::prefix('invoice')->group(function () {
    Route::get('/create', function () {
        return view('invoices.createInvoice');
    })->name('invoice.create');
    Route::get('/edit/{id}', [InvoiceController::class, 'editInvoice'])->name('invoice.edit');

    Route::get('/generatePdf/{id}', [InvoiceController::class, 'generatePdf']);
});
