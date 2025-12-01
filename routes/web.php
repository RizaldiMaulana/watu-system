<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIK (Bisa diakses Siapa Saja / Customer)
|--------------------------------------------------------------------------
*/

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/order', [WebController::class, 'order'])->name('order');
Route::post('/order/cafe', [PublicOrderController::class, 'storeCafe'])->name('public.cafe.store');
Route::post('/order/reservation', [PublicOrderController::class, 'storeReservation'])->name('public.reservation.store');
Route::get('/order/invoice/{id}', [PublicOrderController::class, 'showInvoice'])->name('public.invoice');

/*
|--------------------------------------------------------------------------
| RUTE ADMIN / STAFF (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- AKSES UMUM (SEMUA ROLE BISA) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- AREA BARISTA & ROASTER (POS & Operasional) ---
    Route::middleware(['role:admin,manager,owner,barista'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/print/{id}', [PosController::class, 'print'])->name('pos.print');

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/complete-order/{id}', [AdminController::class, 'completeWebOrder'])->name('admin.complete.order');
    });

    // --- AREA KHUSUS MANAJEMEN (Admin, Manager, Owner, Roaster) ---
    Route::middleware(['role:admin,manager,owner,roaster'])->group(function () {
        Route::resource('ingredients', IngredientController::class);
        Route::get('/laporan-stok', [ReportController::class, 'stock'])->name('reports.stock');
    });

    // --- AREA STRICT MANAJEMEN (Hanya Admin, Manager, Owner) ---
    Route::middleware(['role:admin,manager,owner'])->group(function () {
        Route::get('/laporan-keuangan', [ReportController::class, 'jurnal'])->name('reports.index');
        
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        
        Route::get('/purchases/create', function () {
            $suppliers = \App\Models\Supplier::all();
            $ingredients = \App\Models\Ingredient::all();
            return view('purchases.create', compact('suppliers', 'ingredients'));
        })->name('purchases.create');
        
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        
        // --- MODUL RESEP (BILL OF MATERIALS) --- (YANG HILANG TADI)
        Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::get('/recipes/{product}/manage', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::post('/recipes/{product}/add', [RecipeController::class, 'store'])->name('recipes.store');
        Route::delete('/recipes/{recipe}/remove', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });
});

require __DIR__.'/auth.php';