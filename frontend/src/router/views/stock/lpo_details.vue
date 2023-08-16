<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import { ModelListSelect } from "vue-search-select";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions,mapState } from "vuex"
import Swal from "sweetalert2";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "LPO Details",
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
    ModelListSelect,
    Loader,
  },
  data() {
    return {
      title: "LPO Details",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Purchase Orders",
          active: true,
        },
      ],
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
          key: "product",
          sortable: true,
          label: "PRODUCT",
        },
        {
          key: "qty_field",
          sortable: true,
          label: "QUANTITY",
        },
        {
          key: "received_field",
          sortable: true,
          label: "RECEIVED",
        },
        {
          key: "actions",
          sortable: false,
          label: "ACTION",
        },
      ],

      totalRows1: 1,

      currentPage1: 1,
      perPage1: 10,
      pageOptions1: [10, 25, 50, 100, 200, 1000],
      filter1: null,
      todoFilter1: null,
      filterOn1: [],
      todofilterOn1: [],
      sortDesc1: false,
      modal_id1: "",
      fields1: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "product",
          sortable: true,
          label: "PRODUCT",
        },
        {
          key: "quantity",
          sortable: true,
          label: "QUANTITY",
        },
        {
          key: "price_field",
          sortable: true,
          label: "UNIT PRICE",
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
          key: "actions",
          sortable: false,
          label: "ACTION",
        },
      ],
      id: atob(this.$route.params.id),

      data: {
        id: atob(this.$route.params.id),
        product: "",
        unit: "",
        quantity: "",
        unit_price: "",        
      },

      receive: {
          id: "",
          lpoId: atob(this.$route.params.id),
          product: "",
          qty: "",
          batch: "",
          name: "",
          unit_price: "",
          expiry: "",
      },
      edit:{
        id:"",
        lpo:"",
        product:"",
        name:"",
        qty:"",
      },
      deletestock:{
        id:"",
        lpo:"",
        name:"",
      },
      totals: {
        mode: "",
        paid: 0,
        discount: 0,
        total: 0,
        supplier: "",
        lpo: atob(this.$route.params.id),
      },
      deletedata: {
        id: "",
        lpo: atob(this.$route.params.id),
        name: "",
      },

      payment:{
        amount:"",
        id:atob(this.$route.params.id),
      },

      existingItem: [],
      unitsArr: [],

      amountError: "",
      modeError: "",
      qtyError: "",
      unitPriceError: "",
      EditqtyError:"",
      PaymentError:"",
      itemError: "",
      quantityError: "",
      unitError: "",
      unit_priceError:""

    };
  },
  computed: {
         // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
      ]),
    ...mapGetters("setting", ["PaymentOptions"]),
    ...mapGetters("stock", ["LPO","SaveStatus","LPOReceiveCart","LPOReceiveCartTot"]),
    ...mapGetters("products", ["Products"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.LPO ? this.LPO.length : 1;
    },
    rows1() {
      return this.LPOReceiveCart ? this.LPOReceiveCart.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.LPO ? this.LPO.length : 1;
    this.totalRows1 = this.LPOReceiveCartTot ? this.LPOReceiveCartTot.length : 1;
  },
  updated() {
    
  },
  watch: {
    show(newValue) {
      if (newValue == true) {
        if (this.type == "success") {
            this.closeModal(this.modal_id);
          Swal.fire("Success!", this.message.msg, "success");
        } else {
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
    PaymentOptions(newValue) {
      if (newValue) {
        this.setDefaultMode();
      }
    },
    LPOReceiveCartTot(newValue) {
      if (newValue) {
        this.setSupplier();
      }
    },
  },
  methods: {
    ...mapActions({
      Receive: "stock/addToCartLpoReceive",
      Edit: "stock/editLPO",
      Delete: "stock/deleteLPO",
      AddPayment: "stock/LPOPayment",
      FetchModes: "setting/fetchPaymentOptions",
      FetchLPO: "stock/fetchLPODetails",
      FetchLPOCart: "stock/fetchLpoReceiveCart",
      FetchLPOTot: "stock/fetchLpoReceiveCartTot",
      DeleteCartItem: "stock/deleteCartLpoReceive",
      EditCartItems: "stock/editCartLpoReceive",
      SavePurchaseOrder: "stock/ReceivePurchaseOrder",
      AddLPOProduct: "stock/addLPOProduct",
      FetchItems: "products/fetchProducts",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    onFiltered1(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows1 = filteredItems.length;
      this.currentPage1 = 1;
    },
    // COMMA SEPERATORS
    thousandSeperator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
            .toString()
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        return m;
      }
    },
    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.totals.mode = defaultop[0]?defaultop[0].id:0;
    },
    setSupplier(){
        this.totals.supplier = this.LPO.supplier_id;
        this.totals.total = this.LPOReceiveCartTot;
    },

    assignArr(){
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product;
        });
        this.unitsArr = selectedPdt[0].units;
        this.data.unit = this.unitsArr[0].id;
        this.data.unit_price = this.thousandSeperator(selectedPdt[0].stock.buying_price); 
    },

    assignUnitPrices(){
        let selectedUnit = this.unitsArr.filter((unit)=>{
            return unit.id == this.data.unit;
        });
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product;
        });      
        this.data.unit_price = this.thousandSeperator(selectedPdt[0].stock.buying_price*selectedUnit[0].base_qty);
    },

    checkItem() {
      if (this.data.product != "") {
        this.existingItem = this.LPO.filter((item) => {
          return item.product_id == this.data.product;
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
      this.data.quantity = "";
      this.data.unit_price = "";
    },

    resetModal() {
      this.receive.id = "";
      this.receive.product = "";
      this.receive.unit_price = "";
      this.receive.qty = "";
      this.receive.batch = "";
      this.receive.expiry = ""; 
      this.payment.amount = "";  
      this.resetData();   
    },
    resetEditModal() {
      this.edit.id = "";
      this.edit.product = "";
      this.edit.name = "";
      this.edit.qty = "";
    },
    resetDeleteModal(){
      this.delete.id = "";
      this.delete.name = "";
    },
    resetError(){
        this.qtyError = "";
        this.EditqtyError = "";
        this.unitPriceError = "";
        this.modeError = "";
        this.PaymentError = "";
        this.itemError = "";
        this.unitError = "";
        this.quantityError = "";
        this.unit_priceError = "";
    },
    resetDeleteItem() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },
    addCommas() {
        this.receive.unit_price = this.thousandSeperator(this.receive.unit_price);
        this.payment.amount = this.thousandSeperator(this.payment.amount);
    },
    ShowReceive(data) {
        this.receive.id = data.id;
        this.receive.qty = parseInt(data.qty)-parseInt(data.received);
        this.receive.product = data.product_id;
        this.receive.name = data.product;
    },
    ShowEdit(data) {
        this.edit.id = data.id;
        this.edit.lpo = data.lpo_id;
        this.edit.qty = parseInt(data.qty)-parseInt(data.received);
        this.edit.name = data.product;
    },
    ShowDelete(data) {
        this.deletestock.id = data.id;
        this.deletestock.lpo = data.lpo_id;
        this.deletestock.name = data.product;
    },

    showDeleteModal(data) {
      this.deletedata.name = data.product;
      this.deletedata.id = data.id;
    },

    deleteItem(modalId) {
      this.modal_id = modalId;
      this.DeleteCartItem(this.deletedata);
    },    
    ReceiveStock(modalId){
        this.modal_id = modalId;
        if(this.receive.qty==""){
            this.qtyError = "Quantity Received is required";
            return
        }
        else{
            this.qtyError = "";
        }
        if(this.receive.unit_price==""){
            this.unitPriceError = "Unit Buying Price is required";
            return
        }
        else{
            this.unitPriceError = "";
        }
        this.Receive(this.receive);
    },
    EditStock(modalId){
      this.modal_id = modalId;
      if(this.edit.qty==""){
          this.EditqtyError = "Quantity Field is required";
          return
      }
      else{
          this.EditqtyError = "";
      }
      this.Edit(this.edit);
    },
    DeleteStock(modalId){
      this.modal_id = modalId;
      this.Delete(this.deletestock);
    },

    savePurchaseOrder() {
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      }
      this.SavePurchaseOrder(this.totals);
    },

    addProduct(modalId) {
      if (this.data.product == "") {
        this.itemError = "Product is required";
        return;
      } else {
        this.itemError = "";
      }
      if (this.data.quantity == "") {
        this.quantityError = "Quantity Ordered is required";
        return;
      } else {
        this.quantityError = "";
      }
      if (this.data.unit_price == "") {
        this.unit_priceError = "Unit price is required";
        return;
      } else {
        this.unit_priceError = "";
      }
      if (this.existingItem.length) {
        this.itemError = "This Item exists already please";
        return;
      } else {
        this.itemError = "";
      }
      this.AddLPOProduct(this.data);
      this.closeModal(modalId);
      this.resetData();
    },

    SavePayment(modalid){
      if (this.payment.amount == "" || this.payment.amount <= 0) {
        this.PaymentError = "Amount field is required";
        return;
      } else {
        this.PaymentError = "";
      }      
      this.AddPayment(this.payment)
      this.closeModal(modalid);
    },
    resetmodeError() {
      this.modeError = "";
    },
    resetTotals(){
      this.totals.paid = 0;
      this.totals.discount = 0;
    },
    redirect(value) {
      this.$router.push(
        `/lpo/print/${btoa(value)}`
      );
    },
    closeModal(modalId) {
      this.resetError();
      this.resetModal();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

  },
  created() {
    this.FetchModes();
    this.FetchLPO(this.id);
    this.FetchLPOCart(this.id);
    this.FetchLPOTot(this.id);
    this.FetchItems();
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
              <div class="col-md-8">
                <h4 class="card-title">LPO Generated By {{ LPO?LPO.user:"---" }} on {{ LPO?LPO.dateAdded:"---" }} To: {{ LPO?LPO.supplier:"---" }}</h4>
              </div>
               <div class="col-md-4" align="right">
                <b-button-group>
                  <button class="btn btn-warning btn-sm" v-b-modal.payment-modal v-if="BranchData.BranchId != 0"><i class="fa fa-plus"></i> Add Payment</button>
                  <b-button class="btn btn-info btn-sm" v-b-modal.product-modal v-if="BranchData.BranchId != 0"><i class="fa fa-plus"></i> Add Product</b-button>
                  <b-button
                    size="sm"
                    variant="primary"
                    @click="$router.go(-1)"
                    ><i class="fa fa-undo"></i> Back</b-button
                  >
                  <b-button
                    size="sm"
                    variant="success"
                    @click="redirect(receive.lpoId)"
                    ><i class="fa fa-print"></i> Print</b-button
                  >
                  
                  </b-button-group>
              </div>
            </div>
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
                <div
                    id="tickets-table_filter"
                    class="dataTables_filter text-md-end"
                >
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
                  :items="LPO?LPO.all:[]"
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
                <template #cell(qty_field)="data">
                  {{ data.item.qty }} {{ data.item.unit }}
                </template>
                <template #cell(received_field)="data">
                  {{ data.item.received }} {{ data.item.unit }}
                </template>
                <template #cell(actions)="data" v-if="BranchData.BranchId != 0">
                  <button @click="ShowReceive(data.item)" class="btn btn-primary btn-sm" v-b-modal.clear-modal><i class="fa fa-arrow-down"></i> Receive</button>&nbsp;
                  <button @click="ShowEdit(data.item)" class="btn btn-info btn-sm" v-b-modal.edit-modal><i class="fa fa-pencil-alt"></i> Edit</button>&nbsp;
                  <button @click="ShowDelete(data.item)" class="btn btn-danger btn-sm" v-b-modal.delete-modal><i class="fa fa-trash"></i> Delete</button>
                </template>
              </b-table>
            </div>
            <Loader  v-if="!LPO"/>
            <div class="row">
              <div class="col">
                <div
                    class="dataTables_paginate paging_simple_numbers float-end"
                >
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
            <h3><span class="text-info">Total: {{ LPO && LPO.totAmt?(LPO.totAmt).toLocaleString():0 }}</span>  <span class="text-warning">Paid: {{ LPO && LPO.paid?(LPO.paid).toLocaleString():0 }}</span>  <span class="text-success">Balance: {{ LPO ?(LPO.totAmt-LPO.paid).toLocaleString():0 }}</span>  <span class="text-warning">Delivered: {{ LPO && LPO.used?(LPO.used).toLocaleString():0 }}</span></h3>
          </div>
        </div>
      </div>
    </div>

    <!-- cart starts -->
    <div class="row" v-if="LPOReceiveCartTot?LPOReceiveCartTot>0:false">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <div class="row mt-4">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Show&nbsp;
                    <b-form-select
                        class="form-select form-select-sm"
                        v-model="perPage1"
                        size="sm"
                        :options="pageOptions1"
                    ></b-form-select
                    >&nbsp;entries
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div
                    id="tickets-table_filter"
                    class="dataTables_filter text-md-end"
                >
                  <label class="d-inline-flex align-items-center">
                    Search:
                    <b-form-input
                        v-model="filter1"
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
                  :items="LPOReceiveCart?LPOReceiveCart:[]"
                  :fields="fields1"
                  responsive="sm"
                  :per-page="perPage1"
                  :current-page="currentPage1"
                  :sort-desc.sync="sortDesc1"
                  :filter="filter1"
                  :filter-included-fields="filterOn1"
                  @filtered="onFiltered1"
              >
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>
                <template #cell(price_field)="data">
                  {{ data.item.unitP ?thousandSeperator(data.item.unitP):0 }}
                </template>
                <template #cell(actions)="data">
                  <button @click="showDeleteModal(data.item)" class="btn btn-danger btn-sm" v-b-modal.delete-item-modal><i class="fa fa-trash"></i> Delete</button>
                </template>
              </b-table>
            </div>
            <div class="row">
              <div class="col">
                <div
                    class="dataTables_paginate paging_simple_numbers float-end"
                >
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination
                        v-model="currentPage1"
                        :total-rows="rows1"
                        :per-page="perPage1"
                    ></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h4 class="text-success">TOTAL AMOUNT: {{ LPOReceiveCartTot?(LPOReceiveCartTot).toLocaleString():0 }}</h4>
            <label>Amount Paid: <span class="text-danger">*</span></label>
            <div class="input-group">
              <input
                type="number"
                step="any"
                class="form-control"
                v-model="totals.paid"
                placeholder="Amount Paid"
              />
            </div>
            <label class="mt-2">Discount Received:</label>
            <div class="input-group">
              <input
                type="number"
                step="any"
                class="form-control"
                v-model="totals.discount"
              />
            </div>
            <div class="form-group">
              <label class="mt-2">Payment Method <span class="text-danger">*</span></label>
              <model-list-select
                  :list="PaymentOptions ? PaymentOptions : []"
                  v-model="totals.mode"
                  option-value="id"
                  option-text="mode"
                  placeholder="Select Method"
                  @input="resetError()"
              >
              </model-list-select>
          </div>
          <div class="text-danger" v-if="modeError">
              <em>{{ modeError }}</em>
          </div>
          <center><button @click="savePurchaseOrder()" class="btn btn-primary mt-3"><i class="fa fa-check"></i> Save Purchase</button></center>
          </div>
        </div>
      </div>    
    </div>
    <!-- Cart stops -->
 <!-- modal mark as expired starts -->
    <b-modal id="clear-modal" hide-footer @hidden="resetModal()">
      <template #modal-title>
        <h4 class="text-warning">Receive {{ receive.name }}</h4></template
      >
        <label>Quantity: <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="number"
            step="any"
            class="form-control"
            v-model="receive.qty"
            placeholder="Quantity Received"
            @input="resetError()"
          />
        </div>
        <div class="text-danger" v-if="qtyError">
          <em>{{ qtyError }}</em>
        </div>
        <label class="mt-2">Unit Price:<span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="text"
            step="any"
            class="form-control"
            v-model="receive.unit_price"
            placeholder="Unit Buy Price"
            @input="resetError(),addCommas()"
          />
        </div>
        <div class="text-danger" v-if="unitPriceError">
          <em>{{ unitPriceError }}</em>
        </div>
        
        <label class="mt-2">Expiry Date</label>
        <div class="input-group">
          <input
            type="date"
            class="form-control"
            v-model="receive.expiry"
          />
        </div>
        <label class="mt-2">Batch Number</label>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            placeholder="Batch Number"
            v-model="receive.batch"
          />
        </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('clear-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="ReceiveStock('clear-modal')">
          <i class="fa fa-arrow-down"></i> Receive
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>

    <!-- Payment Model -->
    <b-modal id="payment-modal" hide-footer @hidden="resetModal()">
      <template #modal-title>
        <h4 class="text-warning">Add Payment to this LPO</h4></template
      >
        <label class="mt-2">Amount Paid:<span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="text"
            step="any"
            class="form-control"
            v-model="payment.amount"
            placeholder="Enter Amount Paid"
            @input="resetError(),addCommas()"
          />
        </div>
        <div class="text-danger" v-if="PaymentError">
          <em>{{ PaymentError }}</em>
        </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('payment-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="SavePayment('payment-modal')">
          <i class="fa fa-arrow-down"></i> Confirm
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>

    <!-- Product Model -->
    <b-modal id="product-modal" hide-footer @hidden="resetModal()">
      <template #modal-title>
        <h4 class="text-warning">Add Product to this LPO</h4></template
      >
      <div class="form-group mb-3">
        <label>Product <span class="text-danger">*</span></label>
        <model-list-select
          :list="Products ? Products : []"
          v-model="data.product"
          option-value="id"
          option-text="name"
          placeholder="Select Product"
          @input="resetError(),assignArr(), checkItem()"
        >
        </model-list-select>
        <div class="text-danger" v-if="itemError">
          <em>{{ itemError }}</em>
        </div>
      </div>
      <div class="form-group mb-3">
        <label>Quantity <span class="text-danger">*</span></label>
        <input
        type="number"
        class="form-control"
        step="any"
        v-model="data.quantity"
        @input="resetError()"
        placeholder="Enter Quantity"
        />
        <div class="text-danger" v-if="quantityError">
        <em>{{ quantityError }}</em>
        </div>
      </div>
      <div class="form-group mb-3">
        <label>Unit Measure <span class="text-danger">*</span></label>
        <model-list-select
        :list="unitsArr ? unitsArr : []"
        v-model="data.unit"
        option-value="id"
        option-text="unitsym"
        placeholder="Select Unit"
        @input="resetError(),assignUnitPrices()"
        >
        </model-list-select>
        <div class="text-danger" v-if="unitError">
        <em>{{ unitError }}</em>
        </div>
      </div>  
      <div class="form-group mb-3">
        <label>Unit Price <span class="text-danger">*</span></label>
        <input
          type="text"
          class="form-control"
          step="any"
          id="input3"
          v-model="data.unit_price"
          @keyup.enter="addProduct('product-modal')"
          @input="resetError(),buyingCommas()"
          placeholder="Enter Unit Price"
        />
        <div class="text-danger" v-if="unit_priceError">
          <em>{{ unit_priceError }}</em>
        </div>
      </div>            
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('product-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <b-button
          @click="addProduct('product-modal')"
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
    </b-modal>

<!-- Edit Stock Modal -->
<b-modal id="edit-modal" hide-footer @hidden="resetEditModal()">
      <template #modal-title>
        <h4 class="text-info">Edit {{ edit.name }}</h4></template
      >
        <label>Quantity: <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="number"
            step="any"
            class="form-control"
            v-model="edit.qty"
            placeholder="Quantity"
            @input="resetError()"
          />
        </div>
        <div class="text-danger" v-if="EditqtyError">
          <em>{{ EditqtyError }}</em>
        </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('edit-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="EditStock('edit-modal')">
          <i class="fa fa-pencil-alt"></i> Submit
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>

    <!-- Edit Stock Modal -->
<b-modal id="delete-modal" hide-footer @hidden="resetDeleteModal()">
  <template #modal-title>
        <h4 class="text-danger">Delete {{ deletestock.name }}</h4></template
      >
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('delete-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-danger" @click="DeleteStock('delete-modal')">
          <i class="fa fa-trash"></i> Proceed
          <b-spinner
            v-if="SaveStatus"
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>

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
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
