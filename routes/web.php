<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;

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
    Route::get('customers/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');
    Route::post('customers/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('customers/ajax', [CustomerController::class, 'ajax'])->name('customers.ajax');
    Route::post('customers/getcustomers', [CustomerController::class, 'getcustomers'])->name('customers.getcustomers');
    Route::get('customers/amountdue', [CustomerController::class, 'amountdue'])->name('customers.amountdue');
    Route::get('customers/transactions', [CustomerController::class, 'transactions'])->name('customers.transactions');
    Route::get('customers/showpayprevious', [CustomerController::class, 'showpayprevious'])->name('customers.showpayprevious');
    Route::post('customers/payprevious', [CustomerController::class, 'payprevious'])->name('customers.payprevious');
    Route::get('customers/autocomplete', [CustomerController::class, 'autocomplete'])->name('customers.autocomplete');


    Route::get('invoices/all', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('invoices/newcode', [InvoiceController::class, 'newcode'])->name('invoices.newcode');
    Route::post('invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/view/{id}', [InvoiceController::class, 'view'])->name('invoices.view');
    Route::get('invoices/edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('invoices/update', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('invoices/delete/{id}', [InvoiceController::class, 'delete'])->name('invoices.delete');
    Route::get('invoices/ajax', [InvoiceController::class, 'ajax'])->name('invoices.ajax');
    Route::get('invoices/paymentdetails', [InvoiceController::class, 'paymentdetails'])->name('invoices.paymentdetails');
    
    
    Route::get('payments/all', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/ajax', [PaymentController::class, 'ajax'])->name('payments.ajax');


    Route::get('expenses/all', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::get('expenses/ajax', [ExpenseController::class, 'ajax'])->name('expenses.ajax');
    Route::post('expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/edit/{id}', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::get('expenses/delete/{id}', [ExpenseController::class, 'delete'])->name('expenses.delete');
    Route::post('expenses/update', [ExpenseController::class, 'update'])->name('expenses.update');

    
    Route::get('expenses/category', [ExpenseCategoryController::class, 'index'])->name('expenses.category');
    Route::get('expenses/category_ajax', [ExpenseCategoryController::class, 'ajax'])->name('expenses.category_ajax');
    Route::get('expenses/category_edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('expenses.category_edit');
    Route::get('expenses/category_delete/{id}', [ExpenseCategoryController::class, 'delete'])->name('expenses.category_delete');
    Route::post('expenses/category_update', [ExpenseCategoryController::class, 'update'])->name('expenses.category_update');
    Route::post('expenses/category_store', [ExpenseCategoryController::class, 'store'])->name('expenses.category_store');
    


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
