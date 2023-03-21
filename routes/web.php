<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();



Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('customers/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('customers/ajax', [CustomerController::class, 'ajax'])->name('customers.ajax');
    Route::post('customers/getcustomers', [CustomerController::class, 'getcustomers'])->name('customers.getcustomers');
    Route::get('customers/amountdue', [CustomerController::class, 'amountdue'])->name('customers.amountdue');
    

    Route::get('invoices/all', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('invoices/newcode', [InvoiceController::class, 'newcode'])->name('invoices.newcode');
    Route::post('invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/view/{id}', [InvoiceController::class, 'view'])->name('invoices.view');
    Route::get('invoices/edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('invoices/update', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('invoices/delete/{id}', [InvoiceController::class, 'delete'])->name('invoices.delete');


    Route::get('payments/delete/{id}', [PaymentController::class, 'delete'])->name('payments.delete');



    Route::get('products/getproducts', [ProductController::class, 'getproducts'])->name('products.getproducts');
    
    // Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
    // Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    // Route::post('customers/update', [CustomerController::class, 'update'])->name('customers.update');
    // Route::get('customers/ajax', [CustomerController::class, 'ajax'])->name('customers.ajax');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
