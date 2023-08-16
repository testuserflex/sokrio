<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/cartloader";
import { ModelListSelect } from "vue-search-select";
import { mapActions, mapGetters, mapState } from "vuex";
import Swal from "sweetalert2";
// import moment from "moment";
/**
 * Form Layouts component
 */
export default {
  page: {
    title: "New Order",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: {  PageHeader, ModelListSelect,Loader,Layout, },
  data() {
    return {
      title: "Add to Cart",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "New Order",
          active: true,
        },
      ],
      selected: null,
      totalRows: 1,

      currentPage: 1,
      perPage: 1000,
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
          key: "name",
          sortable: true,
          label: "ITEM",
        },
        {
          key: "quantity_field",
          sortable: true,
          label: "QTY",
        },
        // {
        //   key: "qty_field",
        //   sortable: true,
        //   label: "QTY TAKEN",
        // },
        {
          key: "unit_price",
          sortable: true,
          label: "UNIT PRICE",
        },
        {
          key: "total_price",
          sortable: true,
          label: "TOTAL",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],

      fields2: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "name",
          sortable: true,
          label: "ITEM",
        },
        {
          key: "quantity_field",
          sortable: true,
          label: "QTY",
        },
        {
          key: "unit_price",
          sortable: true,
          label: "UNIT PRICE",
        },
        {
          key: "total_price",
          sortable: true,
          label: "TOTAL",
        },
        {
          key: "batch.batch_number",
          sortable: true,
          label: "BATCH",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      selected1: null,
      totalRows1: 1,

      currentPage1: 1,
      perPage1: 1000,
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
          key: "identification",
          sortable: true,
          label: "DESCRIPTION",
        },
        {
          key: "inCart",
          sortable: true,
          label: "ITEMS",
        },
        {
          key: "dateAdded",
          sortable: true,
          label: "DATE",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingItem: [],
      unitsArr: [],
      unitName:'',
      customerdata:{
        contact: "",
        name:""
      },

      // Value from user input for adding position
      data: {
        quantity_taken: 1,
        product: "",
        unit: "",
        quantity: "",        
        type: "",
        saletype: 1,
        description: "",        
        batch: "",        
      },

      labels:{
        instock: "",
        price: "",
      },
      add: {
        id: "",
        quantity_taken: "",
        product: "",
        unit: "",
        quantity: "",
        instock: "",
        type: "",
        saletype: 1,
      },
      hold: {
        identification: "",
      },
      totals: {
        paid: "",
        total: "",
        customer: "",
        mode: "",
        discount: 0,
        sale_date: null,
        change:"",
        picking_date: "",
      },
      // edit item
      editdata: {
        id: "",
        name: "",
        type: "",
        product: "",
        base_qty: "",
        instock: "",
        quantity: "",
        quantity_taken: "",
        unit_price: "",
      },
      // delete item
      deletedata: {
        name: "",
        id: "",
      },
      unhold: {
        id: "",
      },
      deleteholddata: {
        id: "",
      },
      nameError: "",
      contactError: "",
      modeError: "",
      customerError: "",
      paidError: "",
      itemError: "",
      quantityError: "",
      qtyError: "",
      unitError: "",
      unit_priceError: "",
      quantity1Error: "",
      identificationError: "",

      FBatches: [],
    };
    
  },

  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapState("sales", ["total_cart", "saleId"]),
    ...mapGetters("sales", ["Products"]),
    ...mapGetters("sales", [
      "SaleSaveStatus",
      "Cart",
      "TotalCart",
      "SaleHeld"
    ]),
    ...mapGetters("setting", ["PaymentOptions","GeneralSettings","ReceiptSettings"]),
    ...mapGetters("customers", ["Customers","SaveStatus"]),
    ...mapGetters("auth", ["name","username","MyUserPerm","business","branch"]), 
    ...mapGetters("products", ["Batches"]), 

    /**
     * Total no. of records
     */

    rows() {
      return this.Cart ? this.Cart.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    if(this.GeneralSettings.set_total_as_paid == 1){
      this.totals.paid = this.TotalCart;
    }
    
    this.totals.total = this.TotalCart;
    this.totalRows = this.Cart ? this.Cart.length : 1; 
    this.focusProduct();
  },
  watch: {
    show(newValue) {
      if (newValue) {
        this.closeModal(this.modal_id);
        if (this.type == "success") {
          Swal.fire("Success!", this.message.msg, "success");
        } else {
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },

    total_cart(newValue) {
      if (newValue) {
        if(this.GeneralSettings.set_total_as_paid == 1){
        this.totals.paid = this.thousandSeprator(this.TotalCart);
        }
      }
    },
    PaymentOptions(newValue) {
      if (newValue) {
        this.setDefaultMode();
      }
    },
    saleId(newValue) {
      if (newValue && this.ReceiptSettings.print_receipt_after_sale == 1) {
        
        if(this.ReceiptSettings.receipt_type == 1){
          window.open(
            `https://${window.location.host}/receipts/salesreceipt.php?id=${btoa(newValue)}`
          );
        }else if(this.ReceiptSettings.receipt_type == 2){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv2.php?id=${btoa(newValue)}`
          );
        }else if(this.ReceiptSettings.receipt_type == 3){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv3.php?id=${btoa(newValue)}`
          );
        }
        else if(this.ReceiptSettings.receipt_type == 4){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv4.php?id=${btoa(newValue)}`
          );
        }else{
          window.open(
            `https://${window.location.host}/receipts/salesreceipt.php?id=${btoa(newValue)}`
          );
        }      
      }
    },

    GeneralSettings(newValue){
      if (newValue.show_batch_selling == 1) {
        this.FetchBatches();
      }
    }
  },
  methods: {
    ...mapActions({
      FetchCart: "sales/fetchCart",
      FetchHold: "sales/fetchHeld",
      FetchProducts: "sales/fetchItems",
      FetchCustomers: "customers/fetchCustomers",
      AddCart: "sales/addToCart",
      AddCart1: "sales/addToCart1",
      DeleteCartItem: "sales/deleteCart",
      FetchTotal: "sales/fetchTotCart",
      EditCartItems: "sales/editCart",
      SaveSalePrint: "sales/saveSalePrint",
      SaveSaleExit: "sales/saveSaleExit",
      Hold: "sales/holdSale",
      UnHold: "sales/unHoldSale",
      AddOrder: "sales/addHold",
      DeleteHold: "sales/deleteHold",
      FetchModes: "setting/fetchPaymentOptions",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      fetchReceiptSettings: "setting/fetchReceiptSettings",
      AddCustomer: "customers/addCustomer",
      LogOut: "auth/logout",
      FetchBatches: "products/fetchBatches",
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
    updateQtyTkn(){
        this.data.quantity_taken = this.data.quantity;
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
    assignArr(){
        let productID = this.data.product.split(' ')[0];
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == productID || product.code == this.data.product;
        })
        this.unitsArr = selectedPdt[0].units;
        this.unitName = selectedPdt[0].unitname;
        this.data.type = selectedPdt[0].type;
        this.data.unit = this.unitsArr[0].id;
        this.labels.instock = selectedPdt[0].instock;
        this.labels.price = this.data.saletype == 1?selectedPdt[0].selling:selectedPdt[0].wholesale_unitprice;
    },

    FetchBatch(){
      this.FBatches = this.Batches.filter((batch)=>{
        return batch.product_id == this.data.product.split(' ')[0];
      })
    },

    assignArr2(){
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.add.product || product.code == this.add.product;
        })
        this.unitsArr = selectedPdt[0].units;
        this.add.type = selectedPdt[0].type;
        this.add.unit = this.unitsArr[0].id;
        this.add.instock = selectedPdt[0].instock;
    },
    validateQty(){      
        if(this.data.product && this.data.quantity && this.data.unit && this.data.type==1){
            let tot = this.unitsArr.filter((unit)=>{
                return unit.id == this.data.unit;
            });
            let totEqv = tot[0].base_qty*this.data.quantity;
            if(this.GeneralSettings.allow_negative_stock==0 && totEqv > this.labels.instock){
                this.quantityError = "Quantity Out Of Range";
                return;
            }
            else{
                this.quantityError = "";
            }
            if(this.data.quantity_taken>this.data.quantity){
              this.quantityError = "Quantity Taken Out Of Range";
                return;
            }
            else{
                this.quantityError = "";
            }

        }
    },

    validateQty2(){
        if(this.add.product && this.add.quantity && this.add.unit && this.add.type==1){
            let tot = this.unitsArr.filter((unit)=>{
                return unit.id == this.add.unit;
            });
            let totEqv = tot[0].base_qty*this.add.quantity;
            if(this.GeneralSettings.allow_negative_stock == 0 && totEqv > this.add.instock){
                this.qtyError = "Quantity Out Of Range";
                return;
            }
            else{
                this.qtyError = "";
            }
        }
    },

    validateQty1(){
        if(this.editdata.product && this.editdata.quantity && this.editdata.type==1){
            let tot = this.editdata.quantity*this.editdata.base_qty;
            // if(this.GeneralSettings.allow_negative_stock == 0 && this.editdata.quantity_taken>this.editdata.quantity){
            //   this.quantity1Error = "Quantity Taken Out Of Range";
            //     return;
            // }
            // else 
            if(this.GeneralSettings.allow_negative_stock == 0 && tot > this.editdata.instock){
                this.quantity1Error = "Quantity Out Of Range";
                return;
            }
            else{
              this.quantity1Error = "";
            }
        }
    },
    checkItem() {
      if (this.data.product != "" && this.data.unit != "") {
        let productID = this.data.product.split(' ')[0];
        let batchID = this.data.batch?this.data.batch.split(' ')[0]:'';
        this.existingItem = this.Cart.filter((item) => {
          if(batchID){
            return item.product_id == productID && item.unit_id == this.data.unit && item.batch?item.batch.id == batchID:'' || item.code == this.data.product && item.unit_id == this.data.unit && item.batch?item.batch.id == batchID:'' ;
          }else{
            return item.product_id == productID && item.unit_id == this.data.unit || item.code == this.data.product && item.unit_id == this.data.unit;
          }
        });
        if (this.existingItem.length) {
          this.itemError = "This Item already exists";
          return;
        } else {
          this.itemError = "";
        }
      } else {
        this.existingItem = [];
      }
    },
    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.totals.mode = defaultop[0]?defaultop[0].id:0;
    },
    resetData() {
      this.data.quantity_taken = 1;
      this.data.product = "";
      this.data.unit = "";
      this.data.quantity = "";      
      this.data.type = "";      
      this.data.description = "";
      this.data.batch = "";
      this.labels.instock = "";
      this.labels.price = "";
    },
    resetDeleteItem() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetEdit() {
      this.resetErrors();
      this.editdata.name = "";
      this.editdata.id = "";
      this.editdata.product = "";
      this.editdata.type = 1;
      this.editdata.quantity = "";
      this.editdata.instock = "";
      this.editdata.base_qty = "";
      this.editdata.quantity_taken = "";
      this.editdata.unit_price = "";
      this.modal_id = "";
    },
    resetHold(){
      this.hold.identification = "";
    },

    resetErrors() {
      this.nameError = "";
      this.contactError = "";
      this.qtyError = "";
      this.itemError = "";
      this.unitError = "";
      this.quantityError = "";
      this.quantity1Error = "";
      this.unit_priceError = "";
      this.modeError = "";
      this.paidError = "";
      this.identificationError = "";
      this.customerError = "";
    },

  resetTotals(){
    this.totals.total = 0;
    this.setDefaultMode();
    this.totals.paid = '';
    this.totals.discount = 0;
    this.totals.customer = "";
    this.totals.change = "";
    this.totals.picking_date = "";
  },
  resetCustomer(){
    this.customerdata.contact = "";
    this.customerdata.name = "";
  },
  resetAdd(){
    this.add.id = "";
    this.add.product = "";
    this.add.quantity = "";
    this.add.unit = "";
  },
    showEditModal(data) {
      this.editdata.product = data.product_id;
      this.editdata.name = data.name;
      this.editdata.id = data.id;
      this.editdata.type = data.type;
      this.editdata.base_qty = data.baseQty;
      this.editdata.instock = data.instock;
      this.editdata.quantity = data.quantity;
      this.editdata.quantity_taken = data.quantity_taken;
      this.editdata.unit_price = this.thousandSeprator(data.selling);
    },

    showDeleteModal(data) {
      this.deletedata.name = data.name;
      this.deletedata.id = data.id;
    },
    showAddModal(data) {
      this.add.id = data.id;
    },
    showUnHoldModal(data) {
      this.unhold.id = data.id;
    },
    showDeleteHoldModal(data) {
      this.deleteholddata.id = data.id;
    },
    editItem(modalId) {
      this.editdata.quantity_taken =this.editdata.quantity;
      this.modal_id = modalId;
      if (this.editdata.quantity=="" || this.editdata.quantity==0) {
        this.quantityError = "Quantity Sold is required";
        return
      }
      if (this.editdata.unit_price=="" || this.editdata.unit_price==0) {
        this.unit_priceError = "Selling Price is required";
        return
      }
      this.validateQty1();
      if(this.quantity1Error){
        return;
      }
      this.EditCartItems(this.editdata);
      this.closeModal(modalId);
    },

    deleteItem(modalId) {
      this.modal_id = modalId;
      this.DeleteCartItem(this.deletedata);
      this.closeModal(modalId);
      this.resetTotals();
    },

    saveSalePrint() {      
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }
      let paid1 = this.totals.paid?this.totals.paid.replaceAll(/,/g, ''):0;
      let discount1 = this.totals.discount != 0?this.totals.discount.replaceAll(/,/g, ''):0;
      let tt = parseFloat(paid1)+parseFloat(discount1);
      if (this.totals.customer == "" && (tt < this.totals.total)) {
        this.customerError = "Customer is required";
        return;
      } else {
        this.customerError = "";
      }
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      }
      this.SaveSalePrint(this.totals);
      this.resetTotals()
    },

    // Save and Exit
    saveSaleExit() {
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }      
      let paid1 = this.totals.paid?this.totals.paid.replaceAll(/,/g, ''):0;
      let discount1 = this.totals.discount?this.totals.discount.replaceAll(/,/g, ''):0;
      let tt = parseFloat(paid1)+parseFloat(discount1);
      if (this.totals.customer == "" && (tt < this.totals.total)) {
        this.customerError = "Customer is required";
        return;
      } else {
        this.customerError = "";
      }
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      }
      this.SaveSaleExit(this.totals);
      this.resetTotals();
      this.focusProduct();
    },

    showBalance(){
      let total1 = this.totals.total;
      let paid1 = this.totals.paid.replaceAll(/,/g, '');
      let discount1 = this.totals.discount != 0?this.totals.discount.replaceAll(/,/g, ''):0;
      let ttp = parseFloat(paid1)+parseFloat(discount1);
      if(this.totals.paid && total1 >= ttp){
        this.totals.change ="Balance : "+this.thousandSeprator(total1-ttp);
      }else if(this.totals.paid && total1 < ttp){
        this.totals.change ="Change : "+this.thousandSeprator(ttp-total1);

      } 
      if(paid1 ==''){
        this.totals.change ="Balance : "+this.thousandSeprator(total1);
        
      }
    },

    addToCart() {  
      this.data.quantity_taken = this.data.quantity;
      if (this.data.product == "") {
        this.itemError = "Product is required";
        return;
      } else {
        this.itemError = "";
      }
      if (this.data.quantity == "" || this.data.quantity <= 0) {
        this.quantityError = "Quantity is required";
        return;
      } else {
        this.quantityError = "";
      }      
      this.validateQty();
      if(this.quantityError.length){
        return this.quantityError;
      }else{
        this.quantityError = "";
      } 
      this.checkItem(); 
      this.data.product = this.data.product.split(' ')[0];      
      this.data.batch = this.data.batch.split(' ')[0];      
      this.AddCart(this.data);
      this.resetData();
      this.focusProduct();
    },

    addToCart1(model) {
      this.add.quantity_taken =this.add.quantity;
      this.modal_id = model;
      if (this.add.product == "") {
        this.itemError = "Product is required";
        return;
      } else {
        this.itemError = "";
      }
      if (this.add.quantity == "" || this.add.quantity <= 0) {
        this.qtyError = "Quantity is required";
        return;
      } else {
        this.qtyError = "";
      }
      this.add.saletype = this.data.saletype;
      this.validateQty2();
      this.AddCart1(this.add);
      this.focusProduct();

    },

    UnHoldOrder(mod){
      this.modal_id = mod;
      this.UnHold(this.unhold);
      this.focusProduct();
    },

    deleteHeldItem(mod){
      this.modal_id = mod;
      this.DeleteHold(this.deleteholddata);
      this.focusProduct();
    },

    redirect(value) {
      this.$router.push(
        `/hold/print/${btoa(value)}`
      );
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
      this.$bvModal.hide(modalId);
      this.resetData();
      this.resetAdd();
      this.resetCustomer();
      this.resetErrors();
      this.resetEdit();
      this.resetDeleteItem();
      });
    },

    resetModal() {      
    },

    validateCustomer(){
      if (this.customerdata.name !="" && this.customerdata.contact!=''){
        let selCust = this.Customers.filter((item)=>{
          return  item.phone ==  parseInt(this.customerdata.contact) ;
        })
        let custCount =selCust.length;
        if (custCount>0){
          this.contactError="Customer with contact "+this.customerdata.contact+"  already Exists";
        }
      }else if(this.customerdata.name !="" && this.customerdata.contact ==''){
        let selCust = this.Customers.filter((item)=>{
          return  (item.name).toLowerCase() ==  (this.customerdata.name).toLowerCase() ;
        })
        let custCount =selCust.length;
        if (custCount>0){
          this.contactError="Customer with these details already Exists";
        }
      }
    },

    addCustomer(modalId) {
      this.modal_id = modalId;

      if (this.customerdata.name === "") {
        this.nameError = "Name field is required";
        return;
      } else {
        this.nameError = "";
      }

      this.validateCustomer()
      if (this.contactError!=""){
        return
      }

      this.AddCustomer(this.customerdata);
      this.totals.customer = this.Customers[0].name - this.Customers[0].contact;
      this.closeModal(modalId);      
    },

    nextPlease() {
      document.getElementById('input2').focus();
    },

    nextBatch(){
      document.getElementById('input3').focus();
    },

    logoutUser() {
      this.LogOut();
    },

    focusProduct() {
      this.$refs.cartProduct.focus();
    },
    focusCartPaid() {
      this.$refs.cartPaid.focus();
    },

    CheckBalance(){
      if(this.GeneralSettings.deposit_balances == 1){
        let Balance = this.Customers.filter((bal)=>{
            return bal.id == this.totals.customer;
        })
        if(Balance[0]){
          this.totals.change = "Balance : "+(Balance[0].balance).toLocaleString();
        }else{
          this.totals.change = "Balance : 0";
        }       
      }else{
        return "";
      }
    }

  },
  created() { 
    this.FetchCustomers();
    this.FetchProducts();
    this.FetchCart();
    this.FetchHold();
    this.FetchTotal();
    this.FetchModes();
    this.FetchGeneralSettings();
    this.setDefaultMode();
    this.fetchReceiptSettings();
    this.focusProduct();       

  },
  updated() {
    this.totals.total = this.TotalCart;
  },
};
</script>

<template>
  <Layout>
    <div class="row py-3 text-center">
      <h4><span class="text-primary">{{business}}</span><span v-if="GeneralSettings.show_branchname == 1 && branch"> - {{branch}}</span></h4>
    </div>
   
    <PageHeader :title="title" :items="items" class="px-5 mt-2"/>
    
    <div class="row px-5">
      <div class="col-md-8 mb-5">
        <div class="card">
          <div class="card-body">
              <div class="row">
                  <div class="col-sm-7">
                      <div class="form-group mb-3">
                        <label style="font-size: 18px;">Product <span class="text-danger">*
                          <em class="text-primary h5" v-if="data.product != ''">   Price: {{ labels.price?labels.price.toLocaleString():0 }}</em>
                        </span></label>                        
                        <input
                        id="input1"
                        class="form-control"
                        style="font-size: 15px;"
                        v-model="data.product"
                        ref="cartProduct"
                        list="products"
                        placeholder="Select Service"
                        @input="resetErrors(),assignArr()"
                         @keyup.enter="nextPlease()"
                         @keyup.esc="focusCartPaid()"                       

                        />
                        <datalist id="products">
                        <option
                            v-for="(pdt, p) in Products"
                            :value="pdt.name1"
                            :key="p"
                        >
                        </option>
                        </datalist>
                        <div class="text-primary h5" v-if="labels.instock>0 || labels.instock == '0'">
                          <em>Quantity in stock: {{ labels.instock }}{{ unitName }}</em>
                        </div>
                        <div class="text-danger" v-if="itemError">
                        <em>{{ itemError }}</em>
                        </div>
                      </div>
                  </div>                
                  <div class="col-sm-3">
                      <div class="form-group mb-3">
                        <label style="font-size: 18px;">Qty <span class="text-danger">*</span></label>
                        <input
                        id="input2"
                        type="number"
                        class="form-control"
                        step="any"
                        v-model="data.quantity"
                        @input="resetErrors(),updateQtyTkn()"
                        @keyup.enter="addToCart()"
                        @keyup.esc="focusCartPaid()"
                        placeholder="Enter Qty"
                        />
                        <div class="text-danger" v-if="quantityError">
                        <em>{{ quantityError }}</em>
                        </div>
                      </div>
                  </div>
                  <div class="col-sm-2">
                      <div class="form-group mb-3">
                        <br/>
                        <button class="btn btn-info  mt-2" @click="addToCart()">
                              <i class="fa fa-plus"></i> Add </button>
                        
                      </div>
              </div>
              </div> 
             
              <div class="row" v-if="GeneralSettings.sale_description == 1">
                  <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea @keyup.enter="addToCart()" type="text" class="form-control" v-model="data.description" cols="30" rows="5"></textarea>
                  </div>
              </div> 
            <!-- Table -->
            <div class="table-responsive mb-0">
              <b-table
                class="datatables"
                style="font-size: 16px;"
                :items="Cart ? Cart : []"
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
                <template #cell(total_price)="data">
                  {{
                    data.item.selling
                      ? thousandSeprator(data.item.selling * data.item.quantity)
                      : 0
                  }}
                </template>

                <template #cell(unit_price)="data">
                  {{ data.item.selling ? thousandSeprator(data.item.selling) : 0 }}
                </template>
                <template #cell(quantity_field)="data">
                  {{ data.item ? data.item.quantity : 0 }} {{ data.item ? data.item.unit : 0 }}
                </template>
                 

                <template #cell(actions)="data">
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-item-modal
                    class="m-2"
                    @click="showEditModal(data.item)"
                    ><i class="fa fa-edit"></i></b-button
                  >
                  <b-button
                    v-b-modal.delete-item-modal
                    variant="danger"
                    size="sm"
                    @click="showDeleteModal(data.item)"
                    ><i class="fa fa-trash"></i></b-button
                  >
                </template>
              </b-table>

            </div>
            <Loader v-if="!Cart" />
          </div>
        </div>
        <!-- Start hold -->
        <div class="card" v-if="GeneralSettings.sale_holding == 1 && SaleHeld != ''">
          <div class="card-body">
            <!-- Table -->
            <div class="table-responsive mb-0">
              <b-table
                class="datatables"
                :items="SaleHeld ? SaleHeld : []"
                :fields="fields1"
                responsive="sm"
                :per-page="perPage1"
                :current-page="currentPage1"
                :filter-included-fields="filterOn1"
              >
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>
                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    v-b-modal.unhold-item-modal
                    variant="success"
                    size="sm"
                    @click="showUnHoldModal(data.item)"
                    ><i class="fa fa-undo"></i>UnHold</b-button
                  >
                  <b-button
                    v-b-modal.add-item-modal
                    variant="info"
                    size="sm"
                    @click="showAddModal(data.item)"
                    ><i class="fa fa-plus"></i>Add</b-button
                  >
                  <b-button
                    size="sm"
                    v-if="MyUserPerm.print_heldsales == 1"
                    variant="primary"
                    @click="redirect(data.item.id)"
                    ><i class="fa fa-print"></i> Print</b-button
                  >
                  <b-button
                    v-b-modal.delete-hold-item-modal
                    variant="danger"
                    size="sm"
                    @click="showDeleteHoldModal(data.item)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                  </b-button-group>
                </template>
              </b-table>
            </div>
            <div align="right">
                <router-link to="/sales/held" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i> View All
                </router-link>
            </div>
            <Loader v-if="!Cart" />
          </div>
        </div>
        <!-- End hold -->
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <form action="">
                <div class="row" v-if="TotalCart > 0">
                <div class="col-12 text-center">
                    <h4 style="font-size: 30px;">
                    <strong
                        >TOTAL:
                        {{
                        TotalCart ? thousandSeprator(TotalCart) : 0
                        }}/=</strong
                    >
                    </h4>
                </div>
                </div>
                <div class="form-group mb-2">
                    <label style="font-size: 20px;">Amount Paid</label>
                    <input
                      type="text"
                      ref="cartPaid" 
                      style="font-size: 20px;"  
                      class="form-control"
                      v-model="totals.paid"                      
                      @input="paidCommas(),resetErrors(),showBalance()"
                      @keyup.esc="focusProduct()"
                      @keyup.page-down="saveSalePrint()"
                      @keyup.page-up="saveSaleExit()"
                      min="0"
                    />
                  </div>
                  <div class="text-primary h5" v-if="totals.change">
                          <em>{{ totals.change }}</em>
                    </div>
              <div class="form-group mb-2">
                <label style="font-size: 20px;">Payment Method <span class="text-danger">*</span></label>
                <model-list-select
                  :list="PaymentOptions ? PaymentOptions : []"
                  v-model="totals.mode"
                  style="font-size: 15px;"
                  option-value="id"
                  option-text="mode"
                  placeholder="Select Method"
                  @input="resetErrors()"
                >
                </model-list-select>
              </div>
              <div class="text-danger" v-if="modeError">
                <em>{{ modeError }}</em>
              </div>
              <div class="form-group mb-2" v-if="GeneralSettings.sale_discount == 1">
                <label style="font-size: 20px;">Discount Received</label>
                <input
                  type="text"
                  style="font-size: 20px;"
                  class="form-control"
                  v-model="totals.discount"
                  @input="discountCommas(),resetErrors(),showBalance()"
                  min="0"
                />
              </div>
              <div class="form-group mb-2" v-if="GeneralSettings.back_date_sales == 1">
                <label style="font-size: 20px;">Sale Date <span class="text-danger">*</span></label>
                <input
                  type="date"
                  style="font-size: 20px;"
                  class="form-control"
                  v-model="totals.sale_date"
                  @input="resetErrors()"                  
                />
              </div>
              <div class="form-group" v-if="GeneralSettings.track_customers == 1">
                    <label style="font-size: 20px;">Customer <span class="text-danger">*</span>
                      <span v-if="MyUserPerm.add_customers == 1">
                        <b-button
                          size="sm"
                          style="font-size: 20px;"
                          variant="link"
                          v-b-modal.add-customer-modal
                        >
                        New
                      </b-button>
                      </span>
                    </label>
                    <model-list-select
                      :list="Customers ? Customers : []"
                      v-model="totals.customer"
                      option-value="id"
                      option-text="name1"
                      placeholder="Select Customer"
                      @input="resetErrors(), CheckBalance()"
                      @keyup.enter="saveSale()"
                    >
                    </model-list-select>
                  </div>
                  <div class="text-danger" v-if="customerError">
                    <em>{{ customerError }}</em>
                  </div>
                  <div class="form-group mb-2" v-if="GeneralSettings.sale_description == 1">
                    <label>Picking Date</label>
                    <input type="date" class="form-control" v-model="totals.picking_date" />
                  </div>
              <div class="form-group text-center">
                <div class="row mt-3">
                 
                  <div class="mb-3" v-if="MyUserPerm.new_sale == 1">
                    <b-button
                        @click="saveSaleExit()"
                        variant="primary"
                        
                        ><i class="fa fa-check"></i> Save
                        <b-spinner
                          v-if="SaleSaveStatus"
                          variant="light"
                          small
                          label="Spinning"
                        ></b-spinner>
                    </b-button>
                  </div>              
                
                  
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    

    <!-- Customer modal starts -->
    <b-modal id="add-customer-modal" hide-footer @hidden="resetCustomer">
      <template #modal-title> Add Customer </template>

      <div class="form-group mb-3">
        <label>Customer Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
              type="text"
              placeholder="Enter Name "
              class="form-control"
              @input="resetErrors()"
              v-model="customerdata.name"
          />
        </div>
        <div class="text-danger" v-if="nameError">
          <em>{{ nameError }}</em>
        </div>
      </div>

      <div class="form-group mb-3">
        <label>Customer Contact</label>
        <div class="input-group">
          <input
              type="number"
              placeholder="Enter Phone Number"
              class="form-control"
              v-model="customerdata.contact"
              @input="resetErrors()"

          />
        </div>
        <div class="text-danger" v-if="contactError">
          <em>{{ contactError }}</em>
        </div>
      </div>

      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-danger"
            data-dismiss="modal"
            @click="closeModal('add-customer-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-primary"
            @click="addCustomer('add-customer-modal')"
        > <i class="fa fa-check"></i>
          Submit
          <b-spinner
              v-if="SaveStatus"
              variant="light"
              small
              label="Spinning"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->

 <!-- modal mark as expired starts -->
    <b-modal id="hold-modal" hide-footer @hidden="resetModal()">
      <template #modal-title>
        <h4 class="text-warning">Sale Holding</h4></template
      >
        <label>Identification <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            v-model="hold.identification"
            placeholder="Identify Order"
            @input="resetErrors()"
          />
        </div>
        <div class="text-danger" v-if="identificationError">
          <em>{{ identificationError }}</em>
        </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('hold-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="holdSale('hold-modal')">
          <i class="fa fa-ban"></i> Confirm Hold
          <b-spinner
            v-if="SaleSaveStatus"
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
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
          @input="resetErrors(),validateQty1()"
        />
      </div>
      <div class="text-danger" v-if="quantity1Error">
        <em>{{ quantity1Error }}</em>
      </div>
      <!-- <div class="form-group mb-3" v-if="GeneralSettings.track_untake_orders == 1">
        <label>Quantity Taken</label>
        <input
          type="number"
          class="form-control"
          step="any"
          v-model="editdata.quantity_taken"
        />
      </div> -->
      <div class="form-group mb-3" v-if="MyUserPerm.edit_selling_price == 1">
        <label>Unit Price</label>
        <input type="text" class="form-control" step="any" v-model="editdata.unit_price" @input="editBuyingCommas()"/>
      </div>
      <div class="text-danger" v-if="unit_priceError">
        <em>{{ unit_priceError }}</em>
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
            v-if="SaleSaveStatus"
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
            v-if="SaleSaveStatus"
            variant="light"
            small
            label="Deleting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
        <!-- modal delete expense starts -->
    <b-modal id="unhold-item-modal" hide-footer>
      <template #modal-title class="text-danger">UnHold Order</template>
      <div class="form-group mb-3">
        <div class="input-group">
          <h5>Are you sure you want to UnHold this Order?.</h5>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('unhold-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-success"
          @click="UnHoldOrder('unhold-item-modal')"
        >
          <i class="fa fa-check"></i> UnHold
          <b-spinner
            v-if="SaleSaveStatus"
            variant="light"
            small
            label="UnHolding"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
        <!-- modal delete expense starts -->
    <b-modal id="delete-hold-item-modal" hide-footer>
      <template #modal-title class="text-danger"> Delete Held Order</template>
      <div class="form-group mb-3">
        <div class="input-group">
          <h5>Do you want to this Order? Click Proceed to continue.</h5>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('delete-hold-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="deleteHeldItem('delete-hold-item-modal')"
        >
          <i class="fa fa-trash"></i> Delete
          <b-spinner
            v-if="SaleSaveStatus"
            variant="light"
            small
            label="Deleting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
    <!-- modal for adding items -->
    <b-modal id="add-item-modal" hide-footer @hidden="resetModal()">
      <template #modal-title class="text-primary">
        Add Item to this Order</template
      >
      <div class="form-group mb-3">
          <label>Product <span class="text-danger">*</span></label>
          <model-list-select
          :list="Products ? Products : []"
          v-model="add.product"
          option-value="id"
          option-text="name2"
          placeholder="Select Product"
          @input="resetErrors(),assignArr2()"
          >
          </model-list-select>
          <div class="text-danger" v-if="itemError">
          <em>{{ itemError }}</em>
          </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
              <label>Quantity</label>
              <input
                type="number"
                class="form-control"
                step="any"
                v-model="add.quantity"
                @keyup.enter="addToCart1()"
                @input="resetErrors(),validateQty2()"
              />
            </div>
            <div class="text-danger" v-if="qtyError">
              <em>{{ qtyError }}</em>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Unit Measure <span class="text-danger">*</span></label>
                <model-list-select
                :list="unitsArr ? unitsArr : []"
                v-model="add.unit"
                option-value="id"
                option-text="unitsym"
                placeholder="Unit"
                @input="resetErrors()"
                @keyup.enter="addToCart1()"
                >
                </model-list-select>
                <div class="text-danger" v-if="unitError">
                  <em>{{ unitError }}</em>
                </div>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="closeModal('add-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="addToCart1('add-item-modal')"
        >
          <i class="fa fa-plus"></i> Add Item
          <b-spinner
            v-if="SaleSaveStatus"
            variant="light"
            small
            label="Adding"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!--  end row -->
  </Layout>
</template>
<style>
.btn-primary, .page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>