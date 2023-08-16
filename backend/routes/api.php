<?php
use App\Http\Controllers\DirectTransferCartController;
use App\Http\Controllers\InvoiceCartController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\ProductCategoryController;
use \App\Http\Controllers\UnitsController;
use \App\Http\Controllers\BusinessProductController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\ServiceController;
use \App\Http\Controllers\ProductUnitController;

use \App\Http\Controllers\SpoiltStockController;
use \App\Http\Controllers\StockController;
use \App\Http\Controllers\StockReconciliationController;
use \App\Http\Controllers\StockTransferController;
use \App\Http\Controllers\StockTransferCartController;
use \App\Http\Controllers\PurchaseCartController;
use \App\Http\Controllers\ShopPurchaseController;
use \App\Http\Controllers\SupplierController;
use \App\Http\Controllers\StorePurchaseController;
use \App\Http\Controllers\StorePurchaseCartController;
use \App\Http\Controllers\PaymentOptionController;
use \App\Http\Controllers\CashinController;
use \App\Http\Controllers\CashoutController;
use \App\Http\Controllers\MoneyTransferController;
use \App\Http\Controllers\ExpenseController;
use \App\Http\Controllers\ExpenseCategoryController;
use \App\Http\Controllers\PurchaseReturnController;
use \App\Http\Controllers\LpoController;
use \App\Http\Controllers\LpoCartController;
use \App\Http\Controllers\LpoReceiveCartController;
use \App\Http\Resources\UserResource;
use \App\Http\Controllers\BatchController;
use \App\Http\Controllers\CustomerController;
use \App\Http\Controllers\CustomerDepositController;
use \App\Http\Controllers\RoleController;
use \App\Http\Controllers\SaleController;
use \App\Http\Controllers\SaleHoldController;
use \App\Http\Controllers\SaleCartController;
use \App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use \App\Http\Controllers\GeneralSettingsController;
use \App\Http\Controllers\ReceiptSettingsController;
use \App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierDepositController;
use App\Http\Controllers\ExcelexportController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\ConsumableStockController;
use App\Http\Controllers\ConsumableUsageController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\SmsPackageController;
use \App\Http\Controllers\InstockTransferController;
use \App\Http\Controllers\InstockTransferCartController;
use \App\Http\Controllers\BranchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource(Auth::user());
});
Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::post('logout', [UsersController::class,'logout']);
    Route::post('change_password', [UsersController::class,'changePassword']);
    Route::resource('users', UsersController::class);
    Route::get('business_settings', [BusinessController::class,'settings']);
    Route::resource('roles', RoleController::class);
    Route::get('audits', [RoleController::class,'audits']);
    Route::get('businessdetails', [BranchController::class,'businessdetails']);
    // Dashboard
    Route::post('dashboard_summary', [DashboardController::class,'summary']);
    // products
    Route::resource('product_categories', ProductCategoryController::class);
    Route::resource('units', UnitsController::class);
    Route::resource('business_products', BusinessProductController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('product_units', ProductUnitController::class);
    Route::resource('reconcile', StockReconciliationController::class);
    Route::post('save_reconcile',[StockReconciliationController::class,'reconcile']);
    Route::post('change_base_unit', [ProductUnitController::class,'changeBase']);
    Route::put('update_buyingPrice/{id}', [StockController::class, 'updateBuyingPrice']);
    Route::put('edit_stock/{id}', [StockController::class, 'EditStock']);
    Route::get('stock_movement/{id}', [StockController::class, 'stockMovement']);
    Route::resource('batches', BatchController::class);
    Route::resource('customers', CustomerController::class);

    Route::get('fetchpdts', [ProductController::class,'fetchpdts']);
    Route::post('view_products', [ProductController::class,'viewpdts']);
    Route::post('products_data', [ProductController::class,'products_data']);

//    purchase urls
    Route::resource('shop_purchase_cart', PurchaseCartController::class);
    Route::resource('store_purchase_cart', StorePurchaseCartController::class);
    Route::resource('shop_purchases', ShopPurchaseController::class);
    Route::get('creditors', [ShopPurchaseController::class,'creditors']);
    Route::get('imported_creditors', [ShopPurchaseController::class,'imported_creditors']);
    Route::post('edit_purchase', [ShopPurchaseController::class,'editPurchase']);
    Route::put('update_purchase_amount/{id}', [ShopPurchaseController::class,'updateAmtPaid']);
    Route::put('pay_credit/{id}', [ShopPurchaseController::class,'clearCredit']);
    Route::put('pay_imported_credit/{id}', [ShopPurchaseController::class,'clearImportedCredit']);
    Route::get('total_shop_cart', [PurchaseCartController::class,'totalCart']);

    Route::get('purchase_payments', [ShopPurchaseController::class, 'purchase_payments']);
    Route::get('purchase_print_receipt/{id}', [StorePurchaseController::class,'receipt_data']);



//    payments
    Route::resource('payment_options', PaymentOptionController::class);
    Route::post('statement', [PaymentOptionController::class,'statement']);
    Route::resource('money_transfers', MoneyTransferController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::post('payments', [SaleController::class,'payments']);
    Route::resource('customer_deposit', CustomerDepositController::class);
    Route::get('customer_balances', [CustomerController::class,'balances']);
    Route::get('customer_balancedetails/{id}', [CustomerController::class,'balancedetails']);
    Route::post('customer_sales', [CustomerController::class,'customersales']);

//    expenses
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense_categories', ExpenseCategoryController::class);

    // Sales
    Route::resource('sales_cart', SaleCartController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('sale_hold', SaleHoldController::class);
    Route::get('all_held_sales', [SaleHoldController::class,'all']);
    Route::post('add_held_sale', [SaleCartController::class,'store1']);
    Route::post('debtors', [SaleController::class,'debtors']);
    Route::get('imported_debtors', [SaleController::class,'imported_debtors']);
    Route::put('clear_importeddebtors/{id}', [SaleController::class,'clearImportedDebt']);
    Route::get('get_items', [ProductController::class,'all']);
    Route::put('update_untaken_orders/{id}', [SaleController::class,'updateQtyTaken']);
    Route::put('update_amt_paid/{id}', [SaleController::class,'updateAmtPaid']);
    Route::put('pay_debt/{id}', [SaleController::class,'clearDebt']);
    Route::get('total_cart', [SaleCartController::class,'totalCart']);
    Route::get('print_receipt/{id}', [SaleController::class,'receipt_data']);
    Route::post('fetchsales_details', [SaleController::class,'fetchsales_details']);
    Route::post('sales_data', [SaleController::class,'sales_data']);
//    Settings
    Route::resource('general_settings', GeneralSettingsController::class);
    Route::resource('receipt_settings',ReceiptSettingsController::class);
    Route::resource('permissions', PermissionController::class);




});
Route::post('login', [UsersController::class,'login']);


