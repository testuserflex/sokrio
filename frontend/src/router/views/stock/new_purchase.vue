<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/cartloader";
import { ModelListSelect } from "vue-search-select";
import { mapActions, mapGetters, mapState } from "vuex";
import Swal from "sweetalert2";
import moment from "moment";
/**
 * Form Layouts component
 */
export default {
  page: {
    title: "New Purchase",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: { Layout, PageHeader, ModelListSelect,Loader, },
  data() {
    return {
      title: "Add to stock",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "New Purchase",
          active: true,
        },
      ],
      selected: null,
      totalRows: 1,

      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100, 200, 1000],
      filter: null,
      todoFilter: null,
      filterOn: [],
      todofilterOn: [],
      sortDesc: false,
      modal_id: "",
      fields: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "product.product_name",
          sortable: true,
          label: "ITEM",
        },
        {
          key: "quantity_field",
          sortable: true,
          label: "QUANTITY",
        },
        {
          key: "unit_price",
          sortable: true,
          label: "UNIT PRICE",
        },
        {
          key: "unit_sellingprice",
          sortable: true,
          label: "UNIT SELLING PRICE",
        },
        {
          key: "total_price",
          sortable: true,
          label: "TOTAL PRICE",
        },
        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "expiry",
          sortable: true,
          label: "EXPIRY",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingItem: [],
      unitsArr: [],

      // Value from user input for adding position
      data: {
        source: 0,
        product: "",
        unit: "",
        quantity: "",
        unit_price: "",
        unit_sellingprice: "",
        batch: "",
        expiry: "",
        category: 0,
      },
      itemError: "",
      quantityError: "",
      unitError: "",
      unit_priceError: "",
      batch_noError: "",
      expiry_dateError: "",
      totals: {
        paid: "",
        total: "",
        supplier: "",
        mode: "",
        discount: 0,
        date: moment(new Date()).format("YYYY-MM-DD"),
      },
      limit:0,
      deposits:0,
      // edit item
      editdata: {
        id: "",
        name: "",
        quantity: "",
        batch: "",
        expiry: "",
        unit_price: "",
        unit_sellingprice: "",
      },
      editsup: {
        sup_id: "",
      },
      // delete item
      deletedata: {
        name: "",
        id: "",
      },
      modeError: "",
      supError: "",
      paidError: "",
      CreditLimitError:"",
      BuyingPrice: 0,
      SellingPrice: 0,
    };
  },

  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapState("stock", ["total_shop_cart", "purchaseId"]),
    ...mapGetters("products", ["Products"]),
    ...mapGetters("stock", [
      "SaveStatus",
      "Suppliers",
      "SupplierDeposits",
      "StockCart",
      "TotalShopCart",
    ]),
    ...mapGetters("setting", ["PaymentOptions","ReceiptSettings","GeneralSettings"]),
    ...mapGetters("auth",["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.StockCart ? this.StockCart.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    this.totals.paid = this.TotalShopCart;
    this.totals.total = this.TotalShopCart;
    // Set the initial number of items
    this.totalRows = this.StockCart ? this.StockCart.length : 1;
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue) {
        if (this.type == "success") {
          this.closeModal(this.modal_id);
          Swal.fire("Success!", this.message.msg, "success");
          // this.$toast.success(this.message.msg);
        } else {
          // this.$toast.error(this.message.msg);
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
    total_shop_cart(newValue) {
      if (newValue) {
        this.totals.paid = this.thousandSeprator(this.TotalShopCart);
      }
    },
    PaymentOptions(newValue) {
      if (newValue) {
        this.setDefaultMode();
      }
    },
    purchaseId(newValue) {
      if (newValue && this.ReceiptSettings.print_receipt_after_purchase == 1) {
        window.open(
          `https://${window.location.host}/receipts/purchasesreceipt.php?id=${btoa(newValue)}`
        );
      }
    },
  },
  methods: {
    ...mapActions({
      FetchCart: "stock/fetchCart",
      FetchItems: "products/fetchProducts",
      FetchSuppliers: "stock/fetchSuppliers",
      AddCart: "stock/addToCart",
      DeleteCartItem: "stock/deleteCart",
      FetchTotal: "stock/fetchTotShopCart",
      EditCartItems: "stock/editCart",
      EditSupplier: "stock/editSup",
      SavePurchasePrint: "stock/savePurchasePrint",
      SavePurchaseExit: "stock/savePurchaseExit",
      FetchModes: "setting/fetchPaymentOptions",
      FetchReceiptSettings: "setting/fetchReceiptSettings",
      FetchSupplierDeposits: "stock/fetchSupplierDeposits",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      
    }),

    // COMMA SEPERATORS
    thousandSeprator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
          .toString()
          .replace(/[^0-9.]+/g, "")
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        return m;
      }
    },

    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    paidCommas(){
      this.totals.paid = this.thousandSeprator(this.totals.paid);
    },
    discountCommas(){
      this.totals.discount = this.thousandSeprator(this.totals.discount);
    },
    buyingCommas(){
      this.data.unit_price = this.thousandSeprator(this.data.unit_price);
    },
    editBuyingCommas(){
      this.editdata.unit_price = this.thousandSeprator(this.editdata.unit_price);
    },
    editSellingCommas(){
      this.editdata.unit_sellingprice = this.thousandSeprator(this.editdata.unit_sellingprice);
    },
    sellingCommas(){
      this.data.unit_sellingprice = this.thousandSeprator(this.data.unit_sellingprice);
    },
    assignArr(){
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product.split(' ')[0] || product.code == this.data.product;
        })
        this.unitsArr = selectedPdt[0].units;
        this.data.unit = this.unitsArr[0].id;
        this.BuyingPrice = selectedPdt[0].stock.buying_price;
        this.SellingPrice = selectedPdt[0].selling;        
        this.data.unit_price = this.GeneralSettings.autofillprices_onstocking == 1?this.thousandSeprator(selectedPdt[0].stock.buying_price):'';
        this.data.unit_sellingprice =  this.GeneralSettings.autofillprices_onstocking == 1?this.thousandSeprator(selectedPdt[0].selling):'';
    },
    assignUnitPrices(){
        let selectedUnit = this.unitsArr.filter((unit)=>{
            return unit.id == this.data.unit;
        })
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product.split(' ')[0] || product.code == this.data.product;
        })
        this.BuyingPrice = selectedPdt[0].stock.buying_price*selectedUnit[0].base_qty;
        this.SellingPrice = selectedUnit[0].selling;        
        this.data.unit_price = this.GeneralSettings.autofillprices_onstocking == 1?this.thousandSeprator(selectedPdt[0].stock.buying_price*selectedUnit[0].base_qty):'';
        this.data.unit_sellingprice =  this.GeneralSettings.autofillprices_onstocking == 1?this.thousandSeprator(selectedUnit[0].selling):'';
    },
    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.totals.mode = defaultop[0]?defaultop[0].id:0;
    },

    checkItem() {
      if (this.data.product != "") {
        this.existingItem = this.StockCart.filter((item) => {
          return item.product.id == this.data.product;
        });
        if (this.existingItem.length) {
          this.itemError = "This Item exists already";
        } else {
          this.itemError = "";
        }
      } else {
        this.existingItem = [];
      }
    },

    resetData() {
      this.data.product = "";
      this.data.unit = "";
      this.data.unit_price = "";
      this.data.unit_sellingprice = "";
      this.data.batch = "";
      this.data.expiry = "";
      this.data.quantity = "";
      this.BuyingPrice = "";  
      this.SellingPrice = "";  
    },
    resetDeleteItem(modalId) {
      this.closeModal(modalId);
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetEditSup(modalId) {
      this.closeModal(modalId);
      this.editsup.sup_id = "";
      this.modal_id = "";
    },

    resetEdit(modalId) {
      this.closeModal(modalId);
      this.editdata.name = "";
      this.editdata.id = "";
      this.editdata.quantity = "";
      this.editdata.expiry = "";
      this.editdata.batch = "";
      this.editdata.unit_price = "";
      this.modal_id = "";
    },

    resetErrors() {
      this.itemError = "";
      this.unitError = "";
      this.CreditLimitError = "";
      this.quantityError = "";
      this.unit_priceError = "";
      this.batch_noError = "";
      this.expiry_dateError = "";
    },

    showEditModal(data) {
      this.editdata.product = data.product.id;
      this.editdata.name = data.product.product_name;
      this.editdata.id = data.id;
      this.editdata.quantity = data.quantity;
      this.editdata.expiry = data.expiry;
      this.editdata.batch = data.batch;
      this.editdata.unit_price = this.thousandSeprator(data.unitprice);
      this.editdata.unit_sellingprice = this.thousandSeprator(data.unitsellingprice);
    },

    showDeleteModal(data) {
      this.deletedata.name = data.product.product_name;
      this.deletedata.id = data.id;
    },
    editItem(modalId) {
      this.modal_id = modalId;
      this.EditCartItems(this.editdata);
      this.focusProduct();
    },

    deleteItem(modalId) {
      this.modal_id = modalId;
      this.DeleteCartItem(this.deletedata);
      this.focusProduct();
    },
    savePurchasePrint() {
      let discount1 = this.totals.discount?(this.totals.discount).replaceAll(/,/g, ''):0;
      if(this.GeneralSettings.enable_credit_limit == 1 && this.totals.supplier != ""){
      let limit = 0;
      let deposits = 0;
      this.SupplierDeposits.forEach((item)=>{
          if(item.supplier.id == this.totals.supplier){            
            limit = item.supplier.credit_limit;
            deposits = deposits+parseInt(item.amount);            
          }
      });
      this.limit = limit;
      this.deposits = deposits;
      if((this.limit+this.deposits+parseInt((this.totals.paid).replaceAll(/,/g, ''))+discount1) < this.TotalShopCart){
        this.CreditLimitError = 'The Credit Limit Exceeded';
        return;
      }else{
        this.CreditLimitError = "";      
      }
      }
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }
      let paid1 = (this.totals.paid).replaceAll(/,/g, '');      
      let tt = parseFloat(paid1)+parseFloat(discount1);
      if (this.totals.supplier == "" && (tt < this.totals.total)) {
        this.supError = "Supplier is required";
        return;
      } else {
        this.supError = "";
      }      
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      }
      this.SavePurchasePrint(this.totals);     
    },
    savePurchaseExit() {      
      let discount1 = this.totals.discount?(this.totals.discount).replaceAll(/,/g, ''):0;
      if(this.GeneralSettings.enable_credit_limit == 1 && this.totals.supplier != ""){
      let limit = 0;
      let deposits = 0;
      this.SupplierDeposits.forEach((item)=>{
          if(item.supplier.id == this.totals.supplier){            
            limit = item.supplier.credit_limit;
            deposits = deposits+parseInt(item.amount);            
          }
      });
      this.limit = limit;
      this.deposits = deposits;
      if((this.limit+this.deposits+parseInt((this.totals.paid).replaceAll(/,/g, ''))+discount1) < this.TotalShopCart){
        this.CreditLimitError = 'The Credit Limit Exceeded';
        return;
      }else{
        this.CreditLimitError = "";     
      }
      }
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }
      let paid1 = (this.totals.paid).replaceAll(/,/g, '');
      let tt = parseFloat(paid1)+parseFloat(discount1);
      if (this.totals.supplier == "" && (tt < this.totals.total)) {
        this.supError = "Supplier is required";
        return;
      } else {
        this.supError = "";
      }      
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      } 
      this.SavePurchaseExit(this.totals);
      this.focusProduct();
    },
    resetmodeError() {
      this.modeError = "";
    },
    resetSupError() {
      this.supError = "";
    },
    addToCart() {
      if (this.data.product == "") {
        this.itemError = "Product is required";
        return;
      } else {
        this.itemError = "";
      }
      if (this.data.quantity == "") {
        this.quantityError = "Quantity Purchased is required";
        return;
      } else {
        this.quantityError = "";
      }
      if (this.data.unit_price == "") {
        this.unit_priceError = "Unit Price Field is required";
        return;
      } else {
        this.unit_priceError = "";
      }
      if(this.data.batch != '' && this.data.expiry == ''){
        this.expiry_dateError = 'Expiry Date is required';
        return;
      }else{
        this.expiry_dateError = "";
      }    
      if (this.existingItem.length) {
        this.itemError = "This Item exists already please";
        return;
      } else {
        this.itemError = "";
        this.BuyingPrice = "";
        this.SellingPrice = "";
      }     
      this.data.product = this.data.product.split(' ')[0];    
      this.AddCart(this.data);
      this.resetData();
      this.focusProduct();
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
        this.resetData();
        this.resetErrors();
      });
    },

    nextPlease() {
      document.getElementById('input2').focus();
    },

    focusPrice() {
      document.getElementById('input3').focus();
    },

    focusProduct() {
      this.$refs.cartProduct.focus();
    },
  },
  created() {
    this.FetchSupplierDeposits();
    this.FetchSuppliers();
    this.FetchItems();
    this.FetchCart();
    this.FetchTotal();
    this.FetchModes();
    this.FetchReceiptSettings();
    this.FetchGeneralSettings();
    setTimeout(()=> {
         this.setDefaultMode();
    }, 3000);
  },
  updated() {
    this.totals.total = this.TotalShopCart;
  },
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Items in Cart</h4>
            <div class="row mt-4">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Show&nbsp;
                    <b-form-select
                      class="form-select form-select-sm"
                      v-model="perPage"
                      size="sm"
                      :options="pageOptions"
                    ></b-form-select
                    >&nbsp;entries
                  </label>
                </div>
              </div>              
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_filter" class="dataTables_filter text-md-end">
                  <label class="d-inline-flex align-items-center">
                    Search:
                    <b-form-input
                      v-model="filter"
                      type="search"
                      placeholder="Search..."
                      class="form-control form-control-sm ms-2"
                    ></b-form-input>
                  </label>
                </div>
              </div>
              <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive mb-0">
              <b-table
                class="datatables"
                :items="StockCart ? StockCart : []"
                :fields="fields"
                responsive="sm"
                :per-page="perPage"
                :current-page="currentPage"
                :sort-desc.sync="sortDesc"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
              >
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>

                <template #cell(unit_price)="data">
                  {{ data.item.unitprice ? thousandSeprator(data.item.unitprice) : 0 }}
                </template>
                <template #cell(unit_sellingprice)="data">
                  {{ data.item.unitsellingprice ? thousandSeprator(data.item.unitsellingprice) : 0 }}
                </template>
                <template #cell(quantity_field)="data">
                  {{ data.item ? data.item.quantity : 0 }} {{ data.item ? data.item.unitsym : 0 }}
                </template>

                <template #cell(actions)="data">
                  <b-button
                    size="sm"
                    v-if="MyUserPerm.edit_purchase == 1"
                    variant="primary"
                    v-b-modal.edit-item-modal
                    class="m-2"
                    @click="showEditModal(data.item)"
                    ><i class="fa fa-edit"></i></b-button
                  >
                  <b-button
                    v-if="MyUserPerm.delete_purchase == 1"
                    v-b-modal.delete-item-modal
                    variant="danger"
                    size="sm"
                    @click="showDeleteModal(data.item)"
                    ><i class="fa fa-trash"></i></b-button
                  >
                </template>
              </b-table>
                
              <div class="row">
                <div class="col">
                  <div class="dataTables_paginate paging_simple_numbers float-end">
                    <ul class="pagination pagination-rounded mb-0">
                      <!-- pagination -->
                      <b-pagination
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                      ></b-pagination>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <Loader v-if="!StockCart" />
            <div class="row" v-if="TotalShopCart > 0">
              <div class="col-12 text-center mt-4">
                <h4>
                  <strong
                    >TOTAL:
                    {{
                      TotalShopCart ? thousandSeprator(TotalShopCart) : 0
                    }}/=</strong
                  >
                </h4>
              </div>
            </div>

            <b-form v-if="TotalShopCart > 0" class="mt-3">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Amount Paid</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="totals.paid"
                      @input="paidCommas()"
                      min="0"
                    />
                  </div>
                  <div class="text-danger" v-if="CreditLimitError">
                    <em>{{ CreditLimitError }}</em>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Payment Method <span class="text-danger">*</span></label>
                    <model-list-select
                      :list="PaymentOptions ? PaymentOptions : []"
                      v-model="totals.mode"
                      option-value="id"
                      option-text="mode"
                      placeholder="Select Method"
                      @input="resetmodeError()"
                    >
                    </model-list-select>
                  </div>
                  <div class="text-danger" v-if="modeError">
                    <em>{{ modeError }}</em>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <model-list-select
                      :list="Suppliers ? Suppliers : []"
                      v-model="totals.supplier"
                      option-value="id"
                      option-text="name"
                      placeholder="Select Supplier"
                      @input="resetSupError()"
                    >
                    </model-list-select>
                  </div>
                  <div class="text-danger" v-if="supError">
                    <em>{{ supError }}</em>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-md-3">
                  <div class="form-group mb-3">
                    <label>Purchase Date</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="totals.date"
                    />
                  </div>
                </div>
                <div class="col-md-3" v-if="GeneralSettings.purchase_discount == 1">
                  <div class="form-group">
                    <label>Discount Received</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="totals.discount"
                      @input="discountCommas()"
                      min="0"
                    />
                  </div>
                </div>
                <div v-if="MyUserPerm.add_shop_stock == 1 && ReceiptSettings.print_receipt_after_purchase == 1" class="col-md-3 mt-4">
                  <b-button variant="primary" class="float-end"
                    v-if="SaveStatus" disabled><i class="fa fa-check"></i> Save & Print
                    <b-spinner
                      variant="light"
                      small
                      label="Spinning"
                    ></b-spinner>
                  </b-button>
                  <b-button v-else variant="primary" class="float-end" @click="savePurchasePrint()"
                    ><i class="fa fa-check"></i> Save & Print
                  </b-button>
                </div>
                <div v-if="MyUserPerm.add_shop_stock == 1" class="col-md-3 mt-4">
                  <b-button variant="primary" class="float-end"
                    v-if="SaveStatus" disabled><i class="fa fa-check"></i> Save & Exit
                    <b-spinner                      
                      variant="light"
                      small
                      label="Spinning"
                    ></b-spinner>
                  </b-button>
                  <b-button v-else variant="primary" class="float-end" @click="savePurchaseExit()"
                    ><i class="fa fa-check"></i> Save & Exit
                  </b-button>                  
                </div>
              </div>
            </b-form>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <form action="">

              <div class="form-group mb-3">
                <label>Product <span class="text-danger">*</span></label>
                <input
                  id="input1"
                  class="form-control"
                  v-model="data.product"
                  ref="cartProduct"
                  list="products"
                  placeholder="Select Product or Enter Barcode"
                  @input="resetErrors()"
                  @change="assignArr(), checkItem()"
                  @keyup.enter="nextPlease()"
                />
                <datalist id="products">
                <option
                    v-for="(pdt, p) in Products"
                    :value="pdt.name3"
                    :key="p"
                >
                </option>
                </datalist>
                <div class="text-danger" v-if="itemError">
                  <em>{{ itemError }}</em>
                </div>
              </div>
              <div class="row">
                  <h4 class="text-primary text-center" v-if="BuyingPrice">Buying Price: {{ thousandSeprator(BuyingPrice) }}</h4>
                  <h4 class="text-primary text-center" v-if="SellingPrice">Selling Price: {{ thousandSeprator(SellingPrice) }}</h4>
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label>Quantity <span class="text-danger">*</span></label>
                        <input
                        id="input2"
                        type="number"
                        class="form-control"
                        step="any"
                        v-model="data.quantity"
                        @input="resetErrors()"
                        @keyup.enter="focusPrice()"
                        placeholder="Enter Quantity"
                        />
                        <div class="text-danger" v-if="quantityError">
                        <em>{{ quantityError }}</em>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label>Unit Measure <span class="text-danger">*</span></label>
                        <model-list-select
                        :list="unitsArr ? unitsArr : []"
                        v-model="data.unit"
                        option-value="id"
                        option-text="unitsym"
                        placeholder="Select Unit"
                        @input="resetErrors(), assignUnitPrices()"
                        >
                        </model-list-select>
                        <div class="text-danger" v-if="unitError">
                        <em>{{ unitError }}</em>
                        </div>
                    </div>
                  </div>
              </div>
              
              <div class="form-group mb-3">
                <label>Unit Buying Price <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  step="any"
                  id="input3"
                  v-model="data.unit_price"
                  @keyup.enter="focusSellingPrice()"
                  @input="resetErrors(),buyingCommas()"
                  placeholder="Enter Unit Buying Price"
                />
                <div class="text-danger" v-if="unit_priceError">
                  <em>{{ unit_priceError }}</em>
                </div>
              </div>
              <div v-if="GeneralSettings.sellingprice_onstocking == 1" class="form-group mb-3">
                <label>Unit Selling Price <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  step="any"
                  id="input4"
                  v-model="data.unit_sellingprice"
                  @input="sellingCommas()"
                  placeholder="Enter Unit Selling Price"
                />
              </div>
              <div v-if="GeneralSettings.track_expiries == 1" class="form-group mb-3">
                <label>Batch Number</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="data.batch"
                  @input="resetErrors()"
                  placeholder="Enter Batch Number"
                  @keyup.enter="addToCart()"
                />
                <div class="text-danger" v-if="batch_noError">
                  <em>{{ batch_noError }}</em>
                </div>
              </div>
              <div v-if="GeneralSettings.track_expiries == 1" class="form-group mb-3">
                <label>Expiry Date <span v-if="data.batch !='' " class="text-danger">*</span></label>
                <input
                  type="date"
                  class="form-control"
                  v-model="data.expiry"
                  @input="resetErrors()"
                  @keyup.enter="addToCart()"
                />
                <div class="text-danger" v-if="expiry_dateError">
                  <em>{{ expiry_dateError }}</em>
                </div>
              </div>
              <div class="form-group text-center">
                <b-button
                  @click="addToCart()"
                  variant="primary"
                  ><i class="fa fa-plus"></i> Add
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Spinning"
                  ></b-spinner>
                </b-button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- modal edit item starts -->
    <b-modal id="edit-item-modal" hide-footer @hidden="resetEdit()">
      <template #modal-title class="text-primary">
        Edit Item {{ editdata.name }}</template
      >
      <div class="form-group mb-3">
        <label>Quantity</label>
        <input
          type="number"
          class="form-control"
          step="any"
          v-model="editdata.quantity"
        />
      </div>
      <div class="form-group mb-3">
        <label>Unit Buying Price</label>
        <input type="text" class="form-control" step="any" v-model="editdata.unit_price" @input="editBuyingCommas()"/>
      </div>
      <div class="form-group mb-3">
        <label>Unit Selling Price</label>
        <input type="text" class="form-control" step="any" v-model="editdata.unit_sellingprice" @input="editSellingCommas()"/>
      </div>
      <div class="form-group mb-3">
        <label>Batch Number</label>
        <input type="text" class="form-control" v-model="editdata.batch" />
      </div>
      <div class="form-group mb-3">
        <label>Expiry Date</label>
        <input type="date" class="form-control" v-model="editdata.expiry" />
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="closeModal('edit-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="editItem('edit-item-modal')"
        >
          <i class="fa fa-check"></i> Save Changes
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Deleting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
    <!-- modal delete expense starts -->
    <b-modal id="delete-item-modal" hide-footer @hidden="resetDeleteItem">
      <template #modal-title class="text-danger"> Delete {{ deletedata.name }}</template>
      <div class="form-group mb-3">
        <div class="input-group">
          <h5>Do you want to remove this item? Click Proceed to continue.</h5>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('delete-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="deleteItem('delete-item-modal')"
        >
          <i class="fa fa-trash"></i> Proceed
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Deleting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
    <!--  end row -->
  </Layout>
</template>
<style>
.btn-primary, .page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>