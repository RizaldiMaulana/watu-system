<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoodsReceiptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIK (Bisa diakses Siapa Saja / Customer)
| Domain: watucoffeeandroastery.com
|--------------------------------------------------------------------------
*/
Route::domain(env('APP_DOMAIN_PUBLIC', null))->group(function () {
    Route::get('/', [WebController::class, 'index'])->name('home');
    Route::get('/order', [WebController::class, 'order'])->name('order');
    Route::get('/menu', [WebController::class, 'menu'])->name('public.menu');
    Route::get('/roast-beans', [WebController::class, 'roastBeans'])->name('public.roast_beans');
    Route::post('/order/cafe', [PublicOrderController::class, 'storeCafe'])->name('public.cafe.store');
    Route::post('/order/reservation', [PublicOrderController::class, 'storeReservation'])->name('public.reservation.store');
    Route::get('/order/invoice/{uuid}', [PublicOrderController::class, 'showInvoice'])->name('public.invoice');
    Route::get('/reservation/{id}/pre-order', [PublicOrderController::class, 'preOrder'])->name('public.reservation.pre-order');
    Route::post('/reservation/{id}/pre-order', [PublicOrderController::class, 'storePreOrder'])->name('public.reservation.pre-order.store');
});

/*
|--------------------------------------------------------------------------
| RUTE ADMIN / STAFF (Wajib Login)
| Domain: watu-system.com
|--------------------------------------------------------------------------
*/
Route::domain(env('APP_DOMAIN_SYSTEM', null))->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {

        // --- AKSES UMUM (SEMUA ROLE BISA) ---
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Notifications
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/check', [App\Http\Controllers\NotificationController::class, 'check'])->name('notifications.check');
        Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

        // --- AREA BARISTA & ROASTER (POS, Operasional & Penerimaan Barang) ---
        Route::middleware(['role:admin,manager,owner,barista,roaster'])->group(function () {
            Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
            Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
            Route::get('/pos/print/{uuid}', [PosController::class, 'print'])->name('pos.print');

            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::post('/dashboard/complete-order/{id}', [AdminController::class, 'completeWebOrder'])->name('admin.complete.order');

            // Web Order Management
            Route::resource('web-orders', App\Http\Controllers\WebOrderController::class)->except(['create', 'store', 'show']);

            // Goods Receipt (Penerimaan Barang) - ALL STAFF
            Route::get('/goods-receipt', [GoodsReceiptController::class, 'index'])->name('goods-receipt.index');
            
            // Manual Receipt (Input Faktur Fisik)
            Route::get('/goods-receipt/manual', [GoodsReceiptController::class, 'createManual'])->name('goods-receipt.create-manual');
            Route::post('/goods-receipt/manual', [GoodsReceiptController::class, 'storeManual'])->name('goods-receipt.store-manual');

            Route::get('/goods-receipt/{purchase}/receive', [GoodsReceiptController::class, 'create'])->name('goods-receipt.create');
            Route::post('/goods-receipt/{purchase}/receive', [GoodsReceiptController::class, 'store'])->name('goods-receipt.store');

            // Reservation Management
            Route::get('/reservations', [App\Http\Controllers\ReservationController::class, 'index'])->name('reservations.index');
            Route::get('/reservations/{id}/edit', [App\Http\Controllers\ReservationController::class, 'edit'])->name('reservations.edit');
            Route::put('/reservations/{id}', [App\Http\Controllers\ReservationController::class, 'update'])->name('reservations.update');
            Route::patch('/reservations/{id}/status', [App\Http\Controllers\ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
            Route::delete('/reservations/{id}', [App\Http\Controllers\ReservationController::class, 'destroy'])->name('reservations.destroy');
            Route::get('/reservations/{id}/process-pos', [App\Http\Controllers\ReservationController::class, 'processToPos'])->name('reservations.process_pos');
        });

        // --- AREA KHUSUS MANAJEMEN (Admin, Manager, Owner, Roaster) ---
        Route::middleware(['role:admin,manager,owner,roaster'])->group(function () {
            Route::resource('ingredients', IngredientController::class);
            Route::get('/laporan-stok', [ReportController::class, 'stock'])->name('reports.stock');
            
            // SALES MANAGEMENT
            Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
            Route::get('/sales/{uuid}', [SalesController::class, 'show'])->name('sales.show');
            Route::post('/sales/{uuid}/void', [SalesController::class, 'void'])->name('sales.void');
        
            // Data Master: Promotions & Settings
            Route::resource('promotions', PromotionController::class);
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

            // --- DSS (Decision Support System) ---
            Route::get('/dss/restock', [App\Http\Controllers\DssController::class, 'index'])->name('dss.restock');
        });

        // --- AREA STRICT MANAJEMEN (Hanya Admin, Manager, Owner) ---
        Route::middleware(['role:admin,manager,owner'])->group(function () {
            
            // User Management (Admin & Owner ONLY)
            Route::middleware(['role:admin,owner'])->group(function () {
                Route::resource('users', App\Http\Controllers\UserController::class);
            });

            Route::post('/goods-receipt/{id}/upload-proof', [GoodsReceiptController::class, 'uploadProof'])->name('goods-receipt.upload-proof');
            Route::get('/goods-receipt/{id}/validate', [GoodsReceiptController::class, 'showValidate'])->name('goods-receipt.validate');
            Route::post('/goods-receipt/{id}/verify', [GoodsReceiptController::class, 'verify'])->name('goods-receipt.verify');
            Route::get('/goods-receipt/{id}/print', function ($id) {
                $purchase = \App\Models\Purchase::with(['supplier', 'items.ingredient', 'items.product', 'signer'])->findOrFail($id);
                return view('goods_receipt.print', compact('purchase'));
            })->name('goods-receipt.print');

            Route::get('/laporan-keuangan', [ReportController::class, 'jurnal'])->name('reports.index');
            
            Route::resource('products', ProductController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers', SupplierController::class);
            Route::resource('customers', App\Http\Controllers\CustomerController::class);
            
            Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
            Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
            Route::get('/purchases/{purchase}/print', [PurchaseController::class, 'print'])->name('purchases.print');
            Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
            
            Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
            Route::post('/purchases/{id}/pay', [PurchaseController::class, 'pay'])->name('purchases.pay');
            
            // --- MODUL RESEP (BILL OF MATERIALS) --- (YANG HILANG TADI)
            Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
            Route::get('/recipes/{product}/manage', [RecipeController::class, 'edit'])->name('recipes.edit');
            Route::post('/recipes/{product}/add', [RecipeController::class, 'store'])->name('recipes.store');
            Route::delete('/recipes/{recipe}/remove', [RecipeController::class, 'destroy'])->name('recipes.destroy');

            // --- FINANCE DASHBOARD (UNIFIED AP/AR) ---
            Route::get('/finance', [App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');
            
            // --- REPORT PRINTING & EXPORT ---
            Route::get('/reports/print/{type}', [App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');

            // --- ACCOUNTS RECEIVABLE (AR) ---
            Route::get('/ar', [App\Http\Controllers\ArController::class, 'index'])->name('ar.index');
            Route::get('/ar/{uuid}', [App\Http\Controllers\ArController::class, 'show'])->name('ar.show');
            Route::post('/ar/{uuid}/pay', [App\Http\Controllers\ArController::class, 'storePayment'])->name('ar.storePayment');

            // --- ACCOUNTING MODULE ---
            Route::get('/accounting', [App\Http\Controllers\AccountingController::class, 'index'])->name('accounting.index');
            
            // Chart of Accounts
            Route::get('/accounting/coa', [App\Http\Controllers\AccountingController::class, 'coa'])->name('accounting.coa');
            Route::post('/accounting/coa', [App\Http\Controllers\AccountingController::class, 'storeCoa'])->name('accounting.coa.store');
            Route::patch('/accounting/coa/{id}', [App\Http\Controllers\AccountingController::class, 'updateCoa'])->name('accounting.coa.update');
            Route::delete('/accounting/coa/{id}', [App\Http\Controllers\AccountingController::class, 'destroyCoa'])->name('accounting.coa.destroy');
            
            // Tax Management
            Route::resource('taxes', App\Http\Controllers\TaxController::class);

            // --- FIXED ASSETS ---
            Route::get('/accounting/assets', [App\Http\Controllers\FixedAssetController::class, 'index'])->name('accounting.assets.index');
            Route::get('/accounting/assets/create', [App\Http\Controllers\FixedAssetController::class, 'create'])->name('accounting.assets.create');
            Route::post('/accounting/assets', [App\Http\Controllers\FixedAssetController::class, 'store'])->name('accounting.assets.store');
            Route::post('/accounting/assets/depreciate', [App\Http\Controllers\FixedAssetController::class, 'depreciate'])->name('accounting.assets.depreciate');

            // --- SLIDER MANAGEMENT (CMS) ---
            Route::resource('sliders', App\Http\Controllers\SliderController::class);

            // Manual Journal
            Route::get('/accounting/journal/create', [App\Http\Controllers\AccountingController::class, 'createManualJournal'])->name('accounting.journal.create');
            Route::post('/accounting/journal', [App\Http\Controllers\AccountingController::class, 'storeManualJournal'])->name('accounting.journal.store');

            // Reports
            Route::get('/accounting/reports/balance-sheet', [App\Http\Controllers\AccountingController::class, 'balanceSheet'])->name('accounting.reports.balance_sheet');
            Route::get('/accounting/reports/income-statement', [App\Http\Controllers\AccountingController::class, 'incomeStatement'])->name('accounting.reports.income_statement');
            Route::get('/accounting/reports/cash-flow', [App\Http\Controllers\AccountingController::class, 'cashFlow'])->name('accounting.reports.cash_flow');
            Route::get('/accounting/reports/accounts-payable', [App\Http\Controllers\AccountingController::class, 'accountsPayable'])->name('accounting.reports.accounts_payable');
        });
    });
});

require __DIR__.'/auth.php';