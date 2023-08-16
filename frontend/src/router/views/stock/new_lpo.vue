<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/cartloader";
import { ModelListSelect } from "vue-search-select";
import { mapActions, mapGetters, mapState } from "vuex";
import Swal from "sweetalert2";
/**
 * Form Layouts component
 */
export default {
  page: {
    title: "Purchase Order",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: { Layout, PageHeader, ModelListSelect,Loader, },
  data() {
    return {
      title: "New Purchase Order",
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
          key: "product",
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
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingItem: [],
      unitsArr: [],
      // Value from user input for adding position
      data: {
        product: "",
        unit: "",
        quantity: "",
        unit_price: "",        
      },
      itemError: "",
      quantityError: "",
      unitError: "",
      total:0,
      totals: {
        paid: 0,
        supplier: "",
        mode: "",
        total: 0,
      },
      // edit item
      editdata: {
        id: "",
        name: "",
        quantity: "",
      },
      // delete item
      deletedata: {
        name: "",
        id: "",
      },
      modeError: "",
      supError: "",
      unitpriceError: "",      
    };
  },

  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("products", ["Products"]),
    ...mapGetters("stock", [
      "SaveStatus",
      "Suppliers",
      "LPOCart",
    ]),
    ...mapGetters("setting", ["PaymentOptions"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.LPOCart ? this.LPOCart.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.LPOCart ? this.LPOCart.length : 1;
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue == true) {
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
    PaymentOptions(newValue) {
      if (newValue) {
        this.setDefaultMode();
      }
    },
  },
  methods: {
    ...mapActions({
      FetchCart: "stock/fetchLPOCart",
      FetchItems: "products/fetchProducts",
      FetchSuppliers: "stock/fetchSuppliers",
      AddCart: "stock/addToCartLPO",
      DeleteCartItem: "stock/deleteCartLPO",
      EditCartItems: "stock/editCartLPO",
      SavePurchaseOrder: "stock/savePurchaseOrder",
      FetchModes: "setting/fetchPaymentOptions",
    }),

    // COMMA SEPERATORS
    thousandSeprator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
          .toString()
          .replace(/\D/g, "")
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
    assignArr(){
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product.split(' ')[0];
        });
        this.unitsArr = selectedPdt[0].units;
        this.data.unit = this.unitsArr[0].id;
        this.data.unit_price = this.thousandSeprator(selectedPdt[0].stock.buying_price); 
    },

    assignUnitPrices(){
        let selectedUnit = this.unitsArr.filter((unit)=>{
            return unit.id == this.data.unit;
        });
        let selectedPdt = this.Products.filter((product)=>{
            return product.id == this.data.product.split(' ')[0];
        });      
        this.data.unit_price = this.thousandSeprator(selectedPdt[0].stock.buying_price*selectedUnit[0].base_qty);
    },

    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.totals.mode = defaultop[0]?defaultop[0].id:0;
    },

    checkItem() {
      if (this.data.product != "") {
        this.existingItem = this.LPOCart.filter((item) => {
          return item.product_id == this.data.product.split(' ')[0];
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

    focusProduct() {
      this.$refs.cartProduct.focus();
    },

    FocusQty(){
      document.getElementById('input1').focus();
    },

    resetData() {
      this.data.product = "";
      this.data.unit = "";
      this.data.quantity = "";
      this.data.unit_price = "";
    },

    resetDeleteItem() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetEdit() {
      this.editdata.name = "";
      this.editdata.id = "";
      this.editdata.quantity = "";
      this.modal_id = "";
    },

    resetErrors() {
      this.itemError = "";
      this.unitError = "";
      this.quantityError = "";
      this.unitpriceError = "";
    },

    buyingCommas(){
      this.data.unit_price = this.thousandSeprator(this.data.unit_price);
    },

    showEditModal(data) {
      this.editdata.name = data.product;
      this.editdata.id = data.id;
      this.editdata.quantity = data.quantity;
    },

    showDeleteModal(data) {
      this.deletedata.name = data.product;
      this.deletedata.id = data.id;
    },
    editItem(modalId) {
      this.modal_id = modalId;
      this.EditCartItems(this.editdata);
    },

    deleteItem(modalId) {
      this.modal_id = modalId;
      this.DeleteCartItem(this.deletedata);
    },
    savePurchaseOrder() {
      if (this.totals.mode == "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }
      if (this.totals.supplier == "") {
        this.supError = "Supplier is required";
        return;
      } else {
        this.supError = "";
      }
      if (this.totals.paid == "") {
        this.totals.paid = 0;
      }
      this.totals.total = this.total;
      this.SavePurchaseOrder(this.totals);
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
        this.quantityError = "Quantity Ordered is required";
        return;
      } else {
        this.quantityError = "";
      }
      if (this.data.unit_price == "") {
        this.unitpriceError = "Unit price is required";
        return;
      } else {
        this.unitpriceError = "";
      }
      if (this.existingItem.length) {
        this.itemError = "This Item exists already please";
        return;
      } else {
        this.itemError = "";
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
  },
  created() {
    this.FetchSuppliers();
    this.FetchItems();
    this.FetchCart();
    this.FetchModes();
    setTimeout(()=> {
         this.setDefaultMode();
    }, 3000);
  },
  updated() {
    let total = 0;
    if(this.LPOCart){
        this.LPOCart.forEach((item)=>{
          total = total+parseInt(item.unit_price.replaceAll(/,/g, '')*item.quantity);
        });
        this.total = total;
      }
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
            <div class="row">
               <div class="col-md-9">
                <h4 class="card-title mb-4">Items in LPO Cart</h4>
               </div> 
               <div class="col-md-3" align="right">
                   <router-link to="/lpo"><b-button variant="primary" size="sm"><i class="fa fa-undo"></i> Back</b-button></router-link>
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
                :items="LPOCart ? LPOCart : []"
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
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    v-b-modal.delete-item-modal
                    variant="danger"
                    size="sm"
                    @click="showDeleteModal(data.item)"
                    ><i class="fa fa-trash"></i> Delete</b-button
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
            <Loader v-if="!LPOCart" />
            <div class="row" v-if="total > 0">
              <div class="col-12 text-center mt-4">
                <h4>
                  <strong
                    >TOTAL:
                    {{
                      total ? thousandSeprator(total) : 0
                    }}/=</strong
                  >
                </h4>
              </div>
            </div>
            <b-form class="mt-3" v-if="LPOCart?LPOCart.length>0:false">
              <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
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
                <div class="col-md-6">
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
                <div class="col-md-6 mt-4">
                  <b-button variant="primary" class="float-end" @click="savePurchaseOrder()"
                    ><i class="fa fa-check"></i> Save Purchase Order
                    <b-spinner
                      v-if="SaveStatus"
                      variant="light"
                      small
                      label="Spinning"
                    ></b-spinner>
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
                style="font-size: 15px;"
                v-model="data.product"
                ref="cartProduct"
                list="products"
                placeholder="Select Product"
                @input="resetErrors()"
                @keyup.enter="FocusQty()"
                @change="assignArr(), checkItem()"
                />
                <datalist id="products">
                <option
                    v-for="(pdt, p) in Products"
                    :value="pdt.name1"
                    :key="p"
                >
                </option>
                </datalist>
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
                id="input1"
                v-model="data.quantity"
                @input="resetErrors()"
                @keyup.enter="addToCart()"
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
                @input="resetErrors(),assignUnitPrices()"
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
                  @keyup.enter="addToCart()"
                  @input="resetErrors(),buyingCommas()"
                  placeholder="Enter Unit Price"
                />
                <div class="text-danger" v-if="unitpriceError">
                  <em>{{ unitpriceError }}</em>
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