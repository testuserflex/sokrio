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
    title: "Stock Transfers",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: { Layout, PageHeader, ModelListSelect,Loader, },
  data() {
    return {
      title: "Direct Stock Transfer",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Direct Stock Transfer",
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
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingItem: [],
      unitsArr: [],

      // Value from user input for adding position
      data: {
        source_branch: 0,
        product: "",
        unit: "",
        quantity: "",
      },
      itemError: "",
      quantityError: "",
      unitError: "",
      totals: {
        destination: "",
        source: "",
        date: moment(new Date()).format("YYYY-MM-DD"),
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
      paidError: "",
      branchError: "",
    };
  },

  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("products", ["BranchProducts"]),
    ...mapGetters("general", ["OtherBranches","BusinessSettings"]),
    ...mapGetters("stocktransfer", [
      "SaveStatus",
      "DirectTransferCart",
    ]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.DirectTransferCart ? this.DirectTransferCart.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    this.totalRows = this.DirectTransferCart ? this.DirectTransferCart.length : 1;
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
    DirectTransferCart(newVal){
        if(newVal){
            if (this.DirectTransferCart.length>0){
            // alert(this.DirectTransferCart[0].source_branch);
                this.data.source_branch = this.DirectTransferCart[0].source_branch;
                this.getProducts();
            }
        }
    },
  },
  methods: {
    ...mapActions({
      FetchCart: "stocktransfer/fetchDirectCart",
      FetchItems: "products/fetchBranchProducts",
      FetchBranches: "general/fetchBranches",
      FetchSettings: "general/fetchBusinessSettings",
      AddCart: "stocktransfer/addToDirectCart",
      DeleteCartItem: "stocktransfer/deleteDirectCart",
      EditCartItems: "stocktransfer/editDirectCart",
      SendItems: "stocktransfer/sendDirect",
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
    discountCommas(){
      this.totals.discount = this.thousandSeprator(this.totals.discount);
    },
    
    assignArr(){
        let selectedPdt = this.BranchProducts.filter((product)=>{
            return product.id == this.data.product;
        })
        this.unitsArr = selectedPdt[0].units;
        this.data.unit = this.unitsArr[0].id;
    },

    checkItem() {
      if (this.data.product != "") {
        this.existingItem = this.DirectTransferCart.filter((item) => {
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
      this.data.quantity = "";
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
      this.modal_id = "";
    },

    resetErrors() {
      this.itemError = "";
      this.unitError = "";
      this.quantityError = "";
    },

    showEditModal(data) {
      this.editdata.product = data.product.id;
      this.editdata.name = data.product.product_name;
      this.editdata.id = data.id;
      this.editdata.quantity = data.quantity;
    },

    showDeleteModal(data) {
      this.deletedata.name = data.product.product_name;
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
    send() {
      
      this.SendItems(this.totals);
    },
    resetmodeError() {
      this.modeError = "";
    },
    resetBranchError() {
      this.branchError = "";
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
      if (this.existingItem.length) {
        this.itemError = "This Item exists already please";
        return;
      } else {
        this.itemError = "";
      }
      this.AddCart(this.data);
    },
    getProducts(){
        this.data.product= "",
        this.data.unit= "",
        this.data.quantity= "",
        this.unitsArr=[];
        this.FetchItems(this.data);

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
    this.FetchCart();
    this.FetchBranches();
    this.FetchSettings();
    this.FetchItems(this.data);
  },
  updated() {
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
                :items="DirectTransferCart ? DirectTransferCart : []"
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
                  {{ data.item ? data.item.quantity : 0 }} {{ data.item ? data.item.unit : "---" }}
                </template>

                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-item-modal
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
                  </b-button-group>
                </template>
              </b-table>
                
            </div>
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
  <Loader v-if="!DirectTransferCart" />
            
            <b-form v-if="DirectTransferCart?DirectTransferCart.length > 0:false" class="mt-3">
              <div class="row">
                <h3>Transferring Items from {{DirectTransferCart[0].source?DirectTransferCart[0].source:""}}</h3>
                
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label>Transfer Date</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="totals.date"
                    />
                  </div>
                </div>
                <div v-if="MyUserPerm.transfer_stock == 1" class="col-md-4 mt-4">
                  <b-button v-if="SaveStatus" variant="primary" class="float-end"
                    disabled><i class="fa fa-arrow-right"></i> Send Items
                    <b-spinner
                      variant="light"
                      small
                      label="Spinning"
                    ></b-spinner>
                  </b-button>
                  <b-button v-else variant="primary" class="float-end" @click="send()"
                    ><i class="fa fa-arrow-right"></i> Send Items
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
                <div class="form-group mb-3" v-if="DirectTransferCart?DirectTransferCart.length==0:false">
                    <label>Source Branch <span class="text-danger" >*</span></label>
                    <model-list-select
                      :list="OtherBranches ? OtherBranches : []"
                      v-model="data.source_branch"
                      option-value="id"
                      option-text="name"
                      placeholder="Select Branch"
                      @input="resetBranchError(),getProducts();"
                    >
                    </model-list-select>
                    <div class="text-danger" v-if="branchError">
                    <em>{{ branchError }}</em>
                  </div>
                  </div>
              <div class="form-group mb-3" v-if="data.source_branch > 0">
                <label>Product <span class="text-danger">*</span></label>
                <model-list-select
                  :list="BranchProducts ? BranchProducts : []"
                  v-model="data.product"
                  option-value="id"
                  option-text="name2"
                  placeholder="Select Product"
                  @input="resetErrors(),assignArr(), checkItem()"
                >
                </model-list-select>
                <div class="text-danger" v-if="itemError">
                  <em>{{ itemError }}</em>
                </div>
              </div>
             
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label>Quantity <span class="text-danger">*</span></label>
                        <input
                        type="number"
                        class="form-control"
                        step="any"
                        v-model="data.quantity"
                        @input="resetErrors()"
                        @keyup.enter="addToCart()"
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
                        @input="resetErrors()"
                        >
                        </model-list-select>
                        <div class="text-danger" v-if="unitError">
                        <em>{{ unitError }}</em>
                        </div>
                    </div>
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