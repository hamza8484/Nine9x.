<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ReturnReportSalesController;
use App\Http\Controllers\purchasesReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Account;
//use App\Http\Controllers\JournalEntryLineController;
use App\Http\Controllers\AccountReconciliationController;
use App\Http\Controllers\ReconciliationLineController;



Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// 🌐 مسارات عامة
Route::get('/lang/{locale}', 'LanguageController@changeLanguage')->name('changeLanguage');
Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->middleware('setlocale')->name('setLocale');

// 📦 مسارات عامة / تستخدم في العمليات مثل الباركود أو التحقق من الرصيد
Route::get('/category/{id}', 'PurchaseController@getproducts');
Route::get('/product-details/{id}', 'PurchaseController@getProductDetails');
Route::get('/get-products-by-category/{categoryId}', [PurchaseController::class, 'getProductsByCategory']);
Route::get('/get-suplier-balance/{id}', function ($id) {
    $supplier = \App\Supplier::findOrFail($id);
    return response()->json(['sup_balance' => $supplier->sup_balance]);
});
Route::get('/get-customer-balance/{id}', function ($id) {
    $customer = \App\Customer::findOrFail($id);
    return response()->json(['cus_balance' => $customer->cus_balance]);
});
Route::get('/get-employee-salary/{id}', function ($id) {
    $employee = \App\Employee::findOrFail($id);
    return response()->json(['salary' => $employee->emp_salary]);
});
Route::get('get-product-by-barcode/{barcode}', [ProductController::class, 'getProductByBarcode']);
Route::get('/search-product-by-barcode', [ProductController::class, 'searchProductByBarcode']);

// 🧾 الاشتراك وخطط الدفع (قد تُفتح قبل تسجيل الدخول)
Route::prefix('subscription')->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/process/{plan}', [SubscriptionController::class, 'processCheckout'])->name('subscription.process');
    Route::get('/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/manage', [SubscriptionController::class, 'manage'])->name('subscription.manage');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::post('/renew', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/checkout/{planId}', [SubscriptionController::class, 'processCheckout'])->name('subscription.processCheckout');
});

// 🔐 تجميع كل الروتات المحمية هنا
Route::middleware(['auth'])->group(function () {
    
    //Route::get('lock/screen', 'Admin\AdminAuthenticated@lock_screen');
    // الضرائب، الحسابات، المالية
    Route::get('tax/quarterly-report', [\App\Http\Controllers\TaxController::class, 'quarterlyReport'])->name('taxes.quarterlyReport');
    Route::get('tax/print-report', [\App\Http\Controllers\TaxController::class, 'printReport'])->name('taxes.printReport');
    Route::resource('taxes', TaxController::class);

    Route::resource('company', CompanyController::class);
    Route::resource('branches', BranchController::class);

    // عرض كل التسويات المالية
    Route::get('/reconciliations', [AccountReconciliationController::class, 'index'])->name('reconciliations.index');
    // عرض نموذج إضافة تسوية مالية جديدة
    Route::get('/reconciliations/create', [AccountReconciliationController::class, 'create'])->name('reconciliations.create');
    // تخزين تسوية مالية جديدة
    Route::post('/reconciliations', [AccountReconciliationController::class, 'store'])->name('reconciliations.store');
    // عرض نموذج إضافة خطوط التسوية (Reconciliation Lines) لتسوية معينة
    Route::get('/reconciliations/{reconciliation}/lines/create', [ReconciliationLineController::class, 'create'])->name('reconciliations.lines.create');
    // تخزين خطوط التسوية (Reconciliation Lines) في تسوية معينة
    Route::post('/reconciliations/{reconciliation}/lines', [ReconciliationLineController::class, 'store'])->name('reconciliations.lines.store');
    // عرض تفاصيل تسوية مالية واحدة
    Route::get('/reconciliations/{reconciliation}', [AccountReconciliationController::class, 'show'])->name('reconciliations.show');
    // عرض نموذج تعديل تسوية مالية
    Route::get('/reconciliations/{reconciliation}/edit', [AccountReconciliationController::class, 'edit'])->name('reconciliations.edit');
    // تحديث تسوية مالية
    Route::put('/reconciliations/{reconciliation}', [AccountReconciliationController::class, 'update'])->name('reconciliations.update');
    Route::get('/reconciliations/{id}/export/excel', [AccountReconciliationController::class, 'exportExcel'])->name('reconciliations.export.excel');
    Route::get('/reconciliations/{id}/export/pdf', [AccountReconciliationController::class, 'exportPDF'])->name('reconciliations.export.pdf');


    Route::resource('journal_entry_lines', JournalEntryLineController::class);
    
    Route::get('/accounts/{id}/ledger', [\App\Http\Controllers\AccountController::class, 'showLedger'])->name('accounts.ledger');
    Route::get('/accounts/{id}/ledger/pdf', [\App\Http\Controllers\AccountController::class, 'ledgerPdf'])->name('accounts.ledger.pdf');

    Route::get('/balance-sheet/{fiscalYearId}', [\App\Http\Controllers\ReportController::class, 'showBalanceSheet']);
    Route::get('/balance-sheet-pdf/{fiscalYearId}', [\App\Http\Controllers\ReportController::class, 'generatePdf']);

    Route::get('/accounts/{id}/edit', [\App\Http\Controllers\AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('/accounts/{id}', [\App\Http\Controllers\AccountController::class, 'update'])->name('accounts.update');
    Route::resource('accounts', AccountController::class);

    Route::get('/account-types/{id}/edit', [\App\Http\Controllers\AccountTypeController::class, 'edit'])->name('account_types.edit');
    Route::put('/account-types/{id}', [\App\Http\Controllers\AccountTypeController::class, 'update'])->name('account_types.update');
    Route::resource('account_types', AccountTypeController::class);

    Route::get('/journal-entries/create', [JournalEntryController::class, 'create'])->name('journal_entries.create');
    Route::post('/journal-entries', [JournalEntryController::class, 'store'])->name('journal_entries.store');
    Route::resource('journal_entries', JournalEntryController::class);

    Route::resource('fiscal_years', FiscalYearController::class);
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::resource('cashboxes', CashboxController::class);
    Route::get('transactions/{cashbox_id}', [CashboxTransactionController::class, 'index'])->name('transactions.index');
    Route::resource('transactions', CashboxTransactionController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('products', ProductController::class);
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    Route::get('products/barcode', [BarcodeController::class, 'showProductList'])->name('products.print_product_barcode');
    Route::get('products/barcode/print', [BarcodeController::class, 'printBarcodeLabels'])->name('products.print_barcode');

    Route::get('/suppliers/print/{id}', [SupplierController::class, 'print']);
    Route::get('suppliers/{id}/statement', [SupplierController::class, 'statement'])->name('suppliers.statement');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('supplier_transactions', SupplierTransactionController::class);

    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::get('purchases/print/{id}',['as' =>'purchases.print','uses'=>'PurchaseController@print']);
    Route::get('purchases/pdf/{id}',['as' =>'purchases.pdf','uses'=>'PurchaseController@pdf']);
    Route::resource('purchases', PurchaseController::class);

    Route::get('ret_purchases/create', [RetPurchaseController::class, 'create'])->name('ret_purchases.create');
    Route::get('ret_purchases/print/{id}',['as' =>'ret_purchases.print','uses'=>'RetPurchaseController@print']);
    Route::get('ret_purchases/pdf/{id}',['as' =>'ret_purchases.pdf','uses'=>'RetPurchaseController@pdf']);
    Route::resource('ret_purchases', RetPurchaseController::class);

    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('invoices/invoice/{id}', ['as' => 'invoices.print', 'uses' => 'InvoiceController@print']);
    Route::get('invoices/pdf/{id}',['as' =>'invoices.pdf','uses'=>'InvoiceController@pdf']);
    Route::resource('invoices', InvoiceController::class);

    Route::get('/customers/print/{id}', [CustomerController::class, 'print'])->name('customers.print');
    Route::get('/customers/print-all', [CustomerController::class, 'printAll']);
    Route::get('customers/{id}/transactions', [CustomerController::class, 'showTransactions'])->name('customers.transactions');
    Route::resource('customers', CustomerController::class);
    Route::resource('client_transactions', ClientTransactionController::class);

    Route::get('/ret_invoices/create', [ReturnInvoiceController::class, 'create'])->name('ret_invoices.create');
    Route::get('ret_invoices/print/{id}',['as' =>'ret_invoices.print','uses'=>'ReturnInvoiceController@print']);
    Route::resource('ret_invoices', ReturnInvoiceController::class);

    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::get('quotations/print/{id}',['as' =>'quotations.print','uses'=>'QuotationController@print']);
    Route::get('quotations/pdf/{id}',['as' =>'quotations.pdf','uses'=>'QuotationController@pdf']);
    Route::resource('quotations', QuotationController::class);

    Route::resource('employees', EmployeeController::class);
    Route::get('employees/print/{id}', [EmployeeController::class, 'print'])->name('employees.print');
    Route::patch('/salary-payments/{id}/update-status', [\App\Http\Controllers\SalaryPaymentController::class, 'updatePaymentStatus'])->name('salary_payments.update_status');
    Route::get('/salary-payments/details/{id}', [\App\Http\Controllers\SalaryPaymentController::class, 'showDetails']);

    Route::resource('salary_payments', SalaryPaymentController::class);
    Route::resource('e_categories', ECategoryController::class);
    Route::patch('/e_categories/{id}', [ECategoryController::class, 'update'])->name('e_categories.update');
    Route::delete('/e_categories/{id}', [ECategoryController::class, 'destroy'])->name('e_categories.destroy');
    Route::resource('expenses', ExpensesController::class);

    Route::resource('roles','RoleController');
    Route::resource('users','UserController');

    // تقارير
    Route::get('invoices_report', [InvoicesReportController::class, 'index']);
    Route::post('Search_invoices', [InvoicesReportController::class, 'Search_invoices']);
    Route::get('ret_invoices_report', [ReturnReportSalesController::class, 'index']);
    Route::post('Search_ret_invoices', [ReturnReportSalesController::class, 'Search_ret_invoices']);
    Route::get('purchases_report', [purchasesReportController::class, 'index']);
    Route::post('Search_purchases', [purchasesReportController::class, 'Search_purchases']);
    Route::get('ret_purchases_report', [ReturnpurchasesReportController::class, 'index']);
    Route::post('Search_ret_purchases', [ReturnpurchasesReportController::class, 'Search_ret_purchases']);

    Route::post('/update-cashbox/{cashboxId}', [CashboxController::class, 'updateCashboxBalance']);
    Route::get('export_Invoice', 'InvoiceController@export');

    Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    Route::put('/subscription/{id}/update', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::post('/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');

   



});



// المسار الأخير لأي صفحة
Route::get('/{page}', 'AdminController@index');
