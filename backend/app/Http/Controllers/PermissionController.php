<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(Auth::user()->branch_id==0){
            $admin = Permission::where('business_id', Auth::user()->business_id)->min('id');
            $roles = Permission::where('business_id', Auth::user()->business_id)->where('id', '!=', Auth::user()->role)->where('id', '!=', $admin)->get();
        }
        else{
            $admin = Permission::where('branch_id', Auth::user()->branch_id)->min('id');
            $roles = Permission::where('branch_id', Auth::user()->branch_id)->where('id', '!=', Auth::user()->role)->where('id', '!=', $admin)->get();
        }
        return $roles;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roles = Permission::where('id', Auth::user()->role)->where('business_id', Auth::user()->business_id)->get();
        return $roles;
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
        // return $request;
        $perm = Permission::where('id', $id)->first([$request->column]);
        $val= $perm[$request->column];
        if ($val ===1){
            Permission::where('id',$request->id)->update([$request->column=>0]);
        }
        else{
            Permission::where('id',$request->id)->update([$request->column=>1]);
        }

        if($request->column == "view_store_stock"  || $request->column=="add_store_stock" || $request->column=="reconcile_store_stock" || $request->column=="view_purchases_report"){
            $per = Permission::find($id);
             $val = $perm->view_store_stock +$per->add_store_stock+ $per->reconcile_store_stock+$per->view_purchases_report;
             if ((int)$val > 0){
              $per->store_tab=1;
              $per->save();
             }else{
                $per->store_tab=0;
                $per->save();

             }
        }
        if($request->column == "view_shop_stock"
        || $request->column=="add_shop_stock"
        || $request->column=="view_spoilt_stock"
        || $request->column=="view_short_expiries"
        || $request->column=="reconcile_shop_stock"
        || $request->column=="transfer_stock"
        || $request->column=="view_reconciliation_report"
        || $request->column=="view_expired_stock"
        ){
            $per = Permission::find($id);

            $val= $per->view_shop_stock+
            $per->add_shop_stock+
            $per->view_spoilt_stock+
            $per->view_short_expiries+
            $per->reconcile_shop_stock+
            $per->transfer_stock+
            $per->view_reconciliation_report+
            $per->view_expired_stock;
            if ((int)$val > 0){
                $per->stock_tab=1;
                $per->save();
            }else{
                $per->stock_tab=0;
                $per->save();

            }
        }

        if($request->column == "view_products"
        || $request->column=="view_product_categories"
        || $request->column=="view_business_products"
        || $request->column=="view_units"
        || $request->column=="view_product_units"
        || $request->column=="view_services"

        ){
            $per = Permission::find($id);

            $val= $per->view_products+
            $per->view_product_categories+
            $per->view_business_products+
            $per->view_units+
            $per->view_product_units+
            $per->view_services;
            if ((int)$val > 0){
                $per->products_tab=1;
                $per->save();
            }else{
                $per->products_tab=0;
                $per->save();
            }
        }

        if($request->column == "view_expenses"
        || $request->column=="view_expense_categories"
        || $request->column=="view_expenses_report"
        ){
            $per = Permission::find($id);

            $val= $per->view_expenses+
            $per->view_expense_categories+
            $per->view_expenses_report;
            if ((int)$val > 0){
                $per->expenses_tab=1;
                $per->save();
            }else{
                $per->expenses_tab=0;
                $per->save();
            }
        }
        if($request->column == "view_sales_report"
        || $request->column=="view_purchases_report"
        || $request->column=="view_debtors_report"
        || $request->column=="view_creditors_report"
        || $request->column=="view_stock_transfers"
        || $request->column=="view_sales_analysis"
        ){
            $per = Permission::find($id);

            $val= $per->view_sales_report+
            $per->view_purchases_report+
            $per->view_debtors_report+
            $per->view_creditors_report+
            $per->view_stock_transfers+
            $per->view_sales_analysis;
            if ((int)$val > 0){
                $per->reports_tab=1;
                $per->save();
            }else{
                $per->reports_tab=0;
                $per->save();
            }
        }
        if($request->column == "view_advanced_sales_report"
        || $request->column=="view_advanced_purchases_report"
        || $request->column=="view_advanced_debtors_report"
        || $request->column=="view_advanced_creditors_report"
        || $request->column=="view_sadvanced_tock_transfers"
        || $request->column=="view_advanced_sales_analysis"
        ){
            $per = Permission::find($id);

            $val= $per->view_advanced_sales_report+
            $per->view_advanced_purchases_report+
            $per->view_advanced_debtors_report+
            $per->view_advanced_creditors_report+
            $per->view_advanced_stock_transfers+
            $per->view_advanced_ales_analysis;
            if ((int)$val > 0){
                $per->advanced_reports_tab=1;
                $per->save();
            }else{
                $per->advanced_reports_tab=0;
                $per->save();
            }
        }

        if($request->column == "manage_permissions"
        || $request->column=="view_payment_options"
        || $request->column=="view_money_transfers"
        || $request->column=="view_cash_in"
        || $request->column=="view_cash_out"
        || $request->column=="view_general_settings"
        || $request->column=="view_receipt_settings"
        || $request->column=="view_user_roles"
        || $request->column=="view_users"
        || $request->column=="view_suppliers"
        || $request->column=="view_supplier_deposits"
        || $request->column=="view_customers"
        || $request->column=="view_customer_deposits"
        || $request->column=="view_customer_balances"
        || $request->column=="view_lpo"
        || $request->column=="view_audits"
        ){
            $per = Permission::find($id);

            $val= $per->manage_permissions+
            $per->view_payment_options+
            $per->view_money_transfers+
            $per->view_cash_in+
            $per->view_cash_out+
            $per->view_general_settings+
            $per->view_receipt_settings+
            $per->view_user_roles+
            $per->view_users+
            $per->view_suppliers+
            $per->view_supplier_deposits+
            $per->view_customers+
            $per->view_customer_deposits+
            $per->view_customer_balances+
            $per->view_lpo+
            $per->view_audits;
            if ((int)$val > 0){
                $per->advanced_features_tab=1;
                $per->save();
            }else{
                $per->advanced_features_tab=0;
                $per->save();
            }
            if($request->column=="add_consumable_items"
            || $request->column=="consumable_items"
            || $request->column=="stocking_consumables"
            || $request->column=="using_consumables"
            || $request->column=="stocking_report"
            || $request->column=="usage_report"
            ){
                $per = Permission::find($id);
    
                $val= $per->add_consumable_items+
                $per->consumable_items+
                $per->stocking_consumables+
                $per->using_consumables+
                $per->stocking_report+
                $per->usage_report;
                if ((int)$val > 0){
                    $per->consumables_tab=1;
                    $per->save();
                }else{
                    $per->consumables_tab=0;
                    $per->save();
                }
            }
        }
        return response(['message'=>"Permission updated successfully"],200);


















    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
