<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"


/**
 * Advanced table component
 */
export default {
  page: {
    title: "User Permissions",
    meta: [
      {
        name: "description",
        content: appConfig.description,
      },
    ],
  },
  components: {
    Layout,
    PageHeader,
    Loader,

  },
  data() {
    return {
      title: "User Permissions",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "User Permissions",
          active: true,
        },
        
      ],


      // Value from user input for adding option
      data: {
        id: "",
        column: "",
      },
      UserPerm: [ {label:"Manage Permissions",column:'manage_permissions'},
                  { label: "View Users", column: 'view_users' },
                  { label: "Add Users", column: 'add_users' },
                  { label: "Edit Users", column: 'edit_users', },
                  { label: "Delete Users", column: 'delete_users', },
                  { label: "View User roles", column: 'view_user_roles', },
                  { label: "Add User Roles", column: 'add_user_roles', },
                  { label: "Edit Users Roles", column: 'edit_user_roles', },
                  { label: "Delete User Roles", column: 'delete_user_roles', },
                  { label: "View Users Summary", column: 'view_user_summary', },
                  { label: "Manage Access time", column: 'manage_accesstime', },
                  { label: "Edit Access time", column: 'edit_accesstime', },
                ],
          SalesPerm:[
                  { label: "Record New Sale", column: 'new_sale', },
                  {label:"Modify Sales",column:'edit_sales'},
                  {label:"Delete Sales",column:'delete_sales'},
                  {label:"Edit Sale Amount Paid",column:'edit_sales_amountpaid'},
                  {label:"Clear Debtors",column:'clear_debtors'},
                  {label:"View Sales Analysis Report",column:'view_sales_analysis'},
                  {label:"View Payments Report",column:'view_payments_report'},
                  {label:"Confirm Prepared Orders",column:'confirm_orders'},
                  {label:"Confirm Served Orders",column:'confirm_servedorders'},
                  {label:"Edit Price When Selling",column:'edit_selling_price'},
                  {label:"Print Held Sales",column:'print_heldsales'},


            ],
          PurchasePerm: [
                  {label:"Edit Purchases",column:'edit_purchase'},
                  {label:"Delete Purchases",column:'delete_purchase'},
                  {label:"Edit Purchase Amount Paid",column:'edit_purchase_amountpaid'},
                  { label: "Record shop Stock", column: 'add_shop_stock', },
                  { label: "Record store Stock", column: 'add_store_stock', },
                  {label:"Clear Creditors",column:'clear_creditors'},

          ],
      SideNavigation: [ { label: "View Dashboard", column: 'view_dashboard' },
                  { label: "Stock Navigation", column: 'stock_tab' },
                  { label: "Store Navigation", column: 'store_tab', },
                  { label: "Products Navigation", column: 'products_tab', },
                  { label: "Expenses Navigation", column: 'expenses_tab', },
                  { label: "Basic Reports Navigation", column: 'reports_tab', },
                  ],
      StockPerm: [ { label: "View shop Stock", column: 'view_shop_stock' },
                  { label: "Modify Shop Stock", column: 'edit_shop_stock' },
                  { label: "View store Stock", column: 'view_store_stock', },
                  { label: "Modify store Stock", column: 'edit_store_stock' },
                  { label: "View Spoilt Stock", column: 'view_spoilt_stock', },
                  { label: "Record Spoilt Stock", column: 'record_spoilt_stock', },
                  { label: "Delete Spoilt Stock", column: 'delete_spoilt_stock', },
                  { label: "Confirm Spoilt Stock", column: 'confirm_spoilt_stock', },
                  { label: "View Short Expiries", column: 'view_short_expiries', },
                  { label: "View  Expired Stock", column: 'view_expired_stock', },
                  { label: "Record Expired Stock", column: 'record_expired_stock', },
                  { label: "Remove Expired Stock", column: 'remove_expired_stock', },
                  { label: "Reconcile shop Stock", column: 'reconcile_shop_stock', },
                  { label: "Save shop Stock Reconcilation", column: 'savereconcile_shop_stock', },
                  { label: "Reconcile store Stock", column: 'reconcile_store_stock', },
                  { label: "Save store Stock Reconcilation", column: 'savereconcile_store_stock', },
                  { label: "View Stock Reconciliation Report", column: 'view_reconciliation_report', },
                  { label: "Record Stock Transfer", column: 'transfer_stock', },
                  { label: "Internal Stock Transfer", column: 'internaltransfer_stock', },
                  { label: "Direct Stock Transfer", column: 'directtransfer_stock', },
                  { label: "Receive  Stock Transfer In", column: 'receive_transferred_stock', },
                  { label: "View  LPO", column: 'view_lpo', },

                  ],
      OtherSettingPerm:[
                      {label:"View Audits",column:'view_audits'},
                      {label:"View Customers",column:'view_customers'},
                      {label:"Add Customers",column:'add_customers'},
                      {label:"Edit Customers",column:'edit_customers'},
                      {label:"Delete Customers",column:'delete_customers'},
                      {label:"View Suppliers",column:'view_suppliers'},
                      {label:"Add Suppliers",column:'add_suppliers'},
                      {label:"Edit Suppliers",column:'edit_suppliers'},
                      {label:"Delete Suppliers",column:'delete_suppliers'},
                      {label:"View Supplier Balances",column:'view_supplierbalances'},
                      {label:"View General Summary",column:'view_general_summary'},
                  ],
      FinancePerm:[
                  {label:"View Cash In",column:'view_cash_in'},
                  {label:"Add Cash In",column:'add_cash_in'},
                  {label:"Edit Cash In",column:'edit_cash_in'},
                  {label:"Delete Cash In",column:'delete_cash_in'},
                  {label:"View Cash Out",column:'view_cash_out'},
                  {label:"Add Cash Out",column:'add_cash_out'},
                  {label:"Edit Cash Out",column:'edit_cash_out'},
                  {label:"Delete Cash Out",column:'delete_cash_out'},
                  {label:"View Money Transfers",column:'view_money_transfers'},
                  {label:"Add Money Transfers",column:'add_money_transfers'},
                  {label:"Edit Money Transfers",column:'edit_money_transfers'},
                  {label:"Delete Money Transfers",column:'delete_money_transfers'},
                  {label:"View Payment Options",column:'view_payment_options'},
                  {label:"Add Payment Options",column:'add_payment_options'},
                  {label:"Edit Payment Options",column:'edit_payment_options'},
                  {label:"Delete Payment Options",column:'delete_payment_options'},
                  {label:"View Payment Option Statement",column:'view_option_statement'},
                  {label:"Vie Currencies",column:'view_currencies'},
                  {label:"View Supplier Deposits",column:'view_supplier_deposits'},
                  {label:"Add Supplier Deposits",column:'add_supplier_deposits'},
                  {label:"Edit Supplier Deposits",column:'edit_supplier_deposits'},
                  {label:"Delete Supplier Deposits",column:'delete_supplier_deposits'},
                  {label:"View Customer Deposits",column:'view_customer_deposits'},
                  {label:"Add Customer Deposits",column:'add_customer_deposits'},
                  {label:"Edit Customer Deposits",column:'edit_customer_deposits'},
                  {label:"Delete Customer Deposits",column:'delete_customer_deposits'},
                  {label:"View Customer Balances",column:'view_customer_balances'},
              ],
      
      ExpensePerm: [
                    {label:"View Expenses",column:'view_expenses'},
                    {label:"Record Expenses",column:'add_expenses'},
                    {label:"Edit Expenses",column:'edit_expenses'},
                    {label:"Delete Expenses",column:'delete_expenses'},
                    {label:"View Expenses Categories",column:'view_expense_categories'},
                    {label:"Add Expenses Categories",column:'add_expense_categories'},
                    {label:"Edit Expenses Categories",column:'edit_expense_categories'},
                    {label:"Delete Expenses Categories",column:'delete_expense_categories'},
                        

              ],
      ProductPerm:[
                    {label:"View Products",column:'view_products'},
                    {label:"Add Products",column:'add_products'},
                    {label:"Edit Products",column:'edit_products'},
                    {label:"Delete Products",column:'delete_products'},
                    {label:"View Business Products",column:'view_business_products'},
                    {label:"Add Business Products",column:'add_business_products'},
                    {label:"Edit Business Products",column:'edit_business_products'},
                    {label:"Delete Business Products",column:'delete_business_products'},
                    {label:"View Product Categories",column:'view_product_categories'},
                    {label:"Add Product Categories",column:'add_product_categories'},
                    {label:"Edit Product Categories",column:'edit_product_categories'},
                    {label:"Edit Product Categories",column:'delete_product_categories'},
                    {label:"View Product Units",column:'view_product_units'},
                    {label:"Add Product Units",column:'add_product_units'},
                    {label:"Edit Product Units",column:'edit_product_units'},
                    {label:"Delete Product Units",column:'delete_product_units'},
                    {label:"View Units",column:'view_units'},
                    {label:"Add Units",column:'add_units'},
                    {label:"Edit Units",column:'edit_units'},
                    {label:"Add Units",column:'delete_units'},
                    {label:"View Services",column:'view_services'},

      ],
      SystemSettingPerm:[
                    {label:"View Messages",column:'view_messages'},
                    {label:"General Settings",column:'view_general_settings'},
                    {label:"Receipt Settings",column:'view_receipt_settings'},
                    {label:"Advanced Features Navigation",column:'advanced_features_tab'},
      ],
      ReportsPerm:[                    
                    {label:"View Sales Report",column:'view_sales_report'},
                    {label:"View Advanced Sales Report",column:'view_advanced_sales_report'},
                    {label:"View Purchase Report ",column:'view_purchases_report'},
                    {label:"View Advanced Shop Purchase Report ",column:'view_advanced_shoppurchases_report'},
                    {label:"View Advanced Store Purchase Report ",column:'view_advanced_storepurchases_report'},
                    {label:"View Debtors Report",column:'view_debtors_report'},
                    {label:"View Advanced Debtors Report",column:'view_advanced_debtors_report'},
                    {label:"View Creditors",column:'view_creditors_report'},
                    {label:"View Advanced Creditors",column:'view_advanced_creditors_report'},
                    {label: "View Stock Transfer report", column:'view_stock_transfers', },
                    {label: "View Advanced Stock Transfer report", column:'view_advanced_stock_transfers', },
                    {label:"View Held Sales Report",column:'view_held_sales'},
                    {label:"View Advanced Held Sales Report",column:'view_advanced_held_sales'},
                    {label:"View Expenses Report",column:'view_expenses_report'},
                    {label:"View Advanced Expenses Report",column:'view_advanced_expenses_report'},
                    {label:"View Purchase_payments Report",column:'view_purchase_payments_report'},
                    {label:"View Transactions Report",column:'view_transactions_report'},
                    {label:"View Product Demand Report",column:'view_product_demand_report'},
                    {label:"View Profit Analysis Report",column:'view_profitanalysis_report'},
                    {label:"View Income Statement",column:'view_income_statement'},
                    {label: "Advanced Reports Navigation", column: 'advanced_reports_tab', },
                    {label: "View Sale Returns Report", column: 'view_salereturns_report', },
                    {label: "View Purchase Returns Report", column: 'view_purchasereturns_report', },
                    {label: "View ShopPrice Changes Report", column: 'view_shopprice_changes', },
                    {label: "View StorePrice Changes Report", column: 'view_storeprice_changes', },
                    {label: "View Selling Price Changes Report", column: 'view_sellingprice_changes', },
      ],
      ConsumablePerm:[
                    {label: 'Add Items', column:'add_consumable_items'},
                    {label: 'Edit Items', column:'edit_consumables'},
                    {label: 'Delete Items', column:'delete_consumables'},
                    {label: 'Stocking', column:'stocking_consumables'},
                    {label: 'Usage', column:'using_consumables'},
                    {label: 'Stocking Report', column:'stocking_report'},
                    {label: 'Usage Report', column:'usage_report'},
      ]
    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("users", ["Permissions", "SaveStatus"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Permissions ? this.Permissions.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Permissions ? this.Permissions.length : 1;
  },

  methods: {
    ...mapActions({
      FetchPermissions: "users/fetchPermissions",
      ChangePermissions: "users/changePermissions",
    }),


    UpdateSetting(val,id) {
      this.data.id=id;
      this.data.column=val;
      this.ChangePermissions(this.data);
    },
},
    created() {
      this.FetchPermissions();
    },

};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-9">
               <!-- {{ Permissions }} -->
<!--                <h4 class="card-title">All Payment Methods</h4>-->
              </div>

            </div>


            </div>
            <!-- Table -->
            <div class="table-responsive mb-0">
                <b-tabs content-class="p-3 text-muted">
              <b-tab active class="border-0">
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-home"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Sales</span>
                </template>
                 <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in SalesPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />                           
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="far fa-user"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Purchases</span>
                </template>
                   <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in PurchasePerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="far fa-envelope"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Finance</span>
                </template>
                 <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in FinancePerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Products</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in ProductPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Stock</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in StockPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Users</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in UserPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
               <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Expenses</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in ExpensePerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Other Settings</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in OtherSettingPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Side Navigation</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in SideNavigation" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">System Settings</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in SystemSettingPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Reports</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in ReportsPerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
              <b-tab>
                <template v-slot:title>
                  <span class="d-inline-block d-sm-none">
                    <i class="fas fa-cog"></i>
                  </span>
                  <span class="d-none d-sm-inline-block">Consumables</span>
                </template>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Permission</th>
                      <th v-for="rol in Permissions" :key="rol.id" >
                        {{rol.role}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="p in ConsumablePerm" :key="p.id">
                      <td>{{p.label}}</td>
                      <td v-for="r in Permissions" :key="r.id" >
                        <div class="form-check form-switch form-switch-lg mb-3">
                          
                            <input
                             v-if="r[p.column]==1"                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                              checked
                            />
                            <input
                             v-else                              
                             @change="UpdateSetting(p.column,r.id)"
                              class="form-check-input"
                              type="checkbox"
                              id="SwitchCheckSizelg"
                            />
                            
                            
                          </div>
                         
                      </td>
                    </tr>

                  </tbody>
                </table>
                 
              </b-tab>
            </b-tabs>

             
                

                



            </div>
            <Loader v-if="!Permissions" />

          </div>
        </div>
      </div>
  </Layout>

</template>

