<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Models\Business;
use App\Models\SmsBalance;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Log;
use App\Models\PaymentOption;
use App\Models\ProductCategory;
use App\Models\ReceiptSetting;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::with('user')->orderBy("created_at", "desc")->get();
        $businesses = Business::all();
        $countries = Country::all();
        $permissions = Permission::all();
        return view('branches.branches')->with(['permissions' => $permissions, 'branches'=>BranchResource::collection($branches), 'businesses'=>$businesses,'countries'=>$countries]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function fetchBranches()
    {
        $branches=Branch::where('business_id', Auth::user()->business_id)->get();
        return $branches;
    }


    public function businessdetails()
    {
        $branch = Branch::find(Auth::user()->branch_id);
        $businesses = Business::find(Auth::user()->business_id);
        $arr = [
            'businessname'=>$businesses->name,
            'branchname'=>$branch->name,
            'branchemail'=>$branch->email,
            'branchaddress'=>$branch->address,
            'branchphone1'=>$branch->phone1,
            'branchphone2'=>$branch->phone2,
        ];
        return $arr;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'business' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);
        $checkifexist = Branch::where('name', $request->name)->where('business_id', 'business')->get();
        if (count($checkifexist)>0) {
            return response(['message'=>"Branch with name $request->name already exists for this business ",202]);
        }
        $branch = Branch::create([
            'business_id' => $request->business,
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone1' => $request->phone,
            'phone2' => $request->phone2,
            'country_id' => $request->country,
            'currency_code' => $request->currency,
            'user_id' => Auth::user()->id,
        ]);
        // insert into general setting
        GeneralSetting::create([
            'branch_id' => $branch->id,
            'business_id' => $branch->business_id,
         ]);
        // insert into receipt setting
        ReceiptSetting::create([
            'branch_id' => $branch->id,
            'business_id' => $branch->business_id,
         ]);
        // insert payment account
         PaymentOption::create([
            'name' => "Cash Account",
            'balance' => 0,
            'type' => 'cash',
            'type_name' => 'cash',
            'is_default' => 1,
            'user_id' => 0,
            'branch_id' => $branch->id,
            'business_id' => $branch->business_id,
         ]);
        //  Create default product category
        ProductCategory::create([
            'name' => "Default",
            'user_id' => 0,
            'branch_id' => $branch->id,
            'business_id' => $branch->business_id,
        ]);

         // insert into messages
        SmsBalance::create([
            'amount' => 30,
            'branch_id' => $branch->id,
            'business_id' => $branch->business_id,
        ]);
        // Create two roles
        $roles = [
            ['role' => 'Admin','user_id' => 0,'branch_id' => $branch->id,'business_id' => $branch->business_id,
            'manage_permissions' => 1, 'view_users' => 1, 'add_users' => 1, 'edit_users' => 1, 'delete_users' => 1,
            'view_user_roles' => 1, 'add_user_roles' => 1, 'edit_user_roles' => 1, 'delete_user_roles' => 1,
            'manage_accesstime' => 1, 'edit_accesstime' => 1, 'new_sale' => 1, 'edit_sales' => 1, 'delete_sales' => 1,
            'edit_sales_amountpaid' => 1, 'clear_debtors' => 1, 'view_sales_analysis' => 1,'view_payments_report' => 1, 'edit_purchase' => 1,
            'delete_purchase' => 1, 'edit_purchase_amountpaid' => 1, 'add_shop_stock' => 1,'add_store_stock' => 0, 'clear_creditors' => 1,
            'view_dashboard' => 1, 'stock_tab' => 1, 'store_tab' => 0,'products_tab' => 1, 'expenses_tab' => 1,
            'reports_tab' => 1, 'view_shop_stock' => 1, 'edit_shop_stock' => 1,'view_store_stock' => 0, 'edit_store_stock' => 0,
            'view_spoilt_stock' => 1, 'record_spoilt_stock' => 1, 'delete_spoilt_stock' => 1,'confirm_spoilt_stock' => 1, 'view_short_expiries' => 1,
            'view_expired_stock' => 1, 'record_expired_stock' => 1, 'remove_expired_stock' => 1,'reconcile_shop_stock' => 1, 'savereconcile_shop_stock' => 1,
            'reconcile_store_stock' => 0, 'savereconcile_store_stock' => 0, 'view_reconciliation_report' => 1,'transfer_stock' => 1, 'receive_transferred_stock' => 1,
            'view_lpo' => 1, 'view_audits' => 1, 'view_customers' => 1,'add_customers' => 1, 'edit_customers' => 1,
            'delete_customers' => 1, 'view_suppliers' => 1, 'add_suppliers' => 1,'edit_suppliers' => 1, 'delete_suppliers' => 1,
            'view_supplierbalances' => 1, 'view_cash_in' => 1, 'add_cash_in' => 1,'edit_cash_in' => 1, 'delete_cash_in' => 1,
            'view_cash_out' => 1, 'add_cash_out' => 1, 'edit_cash_out' => 1,'delete_cash_out' => 1, 'view_money_transfers' => 1,
            'add_money_transfers' => 1, 'edit_money_transfers' => 1, 'delete_money_transfers' => 1,'view_payment_options' => 1, 'add_payment_options' => 1,
            'edit_payment_options' => 1, 'delete_payment_options' => 1, 'view_option_statement' => 1,'view_currencies' => 1, 'view_supplier_deposits' => 1,
            'add_supplier_deposits' => 1, 'edit_supplier_deposits' => 1, 'delete_supplier_deposits' => 1,'view_customer_deposits' => 1, 'add_customer_deposits' => 1,
            'edit_customer_deposits' => 1, 'delete_customer_deposits' => 1, 'view_customer_balances' => 1,'view_expenses' => 1,'add_expenses' => 1,
            'edit_expenses' => 1, 'delete_expenses' => 1, 'view_expense_categories' => 1,'add_expense_categories' => 1, 'edit_expense_categories' => 1,
            'delete_expense_categories' => 1, 'view_products' => 1, 'add_products' => 1,'edit_products' => 1, 'delete_products' => 1,
            'view_business_products' => 1, 'add_business_products' => 1, 'edit_business_products' => 1,'delete_business_products' => 1, 'view_product_categories' => 1,
            'add_product_categories' => 1, 'edit_product_categories' => 1, 'delete_product_categories' => 1,'view_product_units' => 1, 'add_product_units' => 1,
            'edit_product_units' => 1, 'delete_product_units' => 1, 'view_units' => 1, 'add_units' => 1, 'edit_units' => 1, 'view_advanced_storepurchases_report' => 0,
            'delete_units' => 1, 'view_services' => 1, 'view_messages' => 1, 'view_general_settings' => 1, 'view_receipt_settings' => 1,
            'advanced_features_tab' => 1, 'view_sales_report' => 1, 'view_advanced_sales_report' => 1, 'view_purchases_report' => 1, 'view_advanced_shoppurchases_report' => 1,
            'view_debtors_report' => 1, 'view_advanced_debtors_report' => 1, 'view_creditors_report' => 1, 'view_advanced_creditors_report' => 1, 'view_stock_transfers' => 1,
            'view_advanced_stock_transfers' => 1, 'view_held_sales' => 1, 'view_advanced_held_sales' => 1,'view_expenses_report' => 1, 'view_advanced_expenses_report' => 1,
            'view_purchase_payments_report' => 1, 'view_transactions_report' => 1, 'view_product_demand_report' => 1, 'view_profitanalysis_report' => 1, 'view_income_statement' => 1,
            'advanced_reports_tab' => 1, 'view_salereturns_report' => 1, 'view_purchasereturns_report' => 1, 'view_shopprice_changes' => 1, 'view_storeprice_changes' => 0,
            'add_consumable_items' => 1, 'edit_consumables' => 1, 'delete_consumables' => 1,'stocking_consumables' => 1, 'using_consumables' => 1,
            'stocking_report' => 1, 'usage_report' => 1, 'confirm_orders' => 0, 'confirm_servedorders' => 0, 'view_sellingprice_changes' => 1, 'edit_selling_price' => 1, 'modify_sales' => 1,
            'modify_purchases' => 1, 'view_user_summary' => 1, 'view_general_summary' => 1,'print_heldsales' => 1
            ],
            // ['role' => 'Attendant','user_id' => 0,'branch_id' => $branch->id,'business_id' => $branch->business_id]
        ];
        Permission::insert($roles);
        Log::create([
            'activity' => "Added a new branch ".$branch->name." for business ".Business::find($request->business)->name,
            'category' => 0,
            'user_id' => 0,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::where('id',$id)->with('business','country')->first();
        $countries = Country::all();
        return view('branches.edit')->with(['branches'=>$branch, 'countries' => $countries]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);

        $business = Business::find($id);
        $business->name = $request->name;
        $business->email = $request->email;
        $business->website = $request->website;
        $business->phone1 = $request->phone;
        $business->phone2 = $request->phone2;
        $business->tin = $request->tin;
        $business->address = $request->address;
        $business->country_id = $request->country;
        $business->currency_code = $request->currency;
        $business->logo = $request->logo;
        $business->save();

        Log::create([
            'activity' => "Updated details for a business ".$business->name,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        $businessx = Business::all();
        $countries = Country::all();
        return redirect('/businesses')->with(['businesses'=>BranchResource::collection($businessx), 'countries'=>$countries]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
        if ($branch->status==0){
            $action = "Activated branch ".$branch->name;
            $branch->status = 1;
        }
        else {
            $action = "Deactivated branch ".$branch->name;
            $branch->status = 0;
        }
        $branch->save();
        Log::create([
            'activity' => $action,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);
        return redirect()->back();
    }

    public function branchPermissions()
    {
        $branches = Branch::get();
        return view('branches.upload')->with('branches',BranchResource::collection($branches));
    }

    public function upload($id)
    {
        $branch = Branch::find($id);
        if ($branch->upload==0){
            $action = "Activated products upload for branch ".$branch->name;
            $branch->upload = 1;
        }
        else {
            $action = "Deactivated products upload for branch ".$branch->name;
            $branch->upload = 0;
        }
        $branch->save();
        Log::create([
            'activity' => $action,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);
        return redirect()->back();
    }
}
