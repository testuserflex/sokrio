<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
import moment from "moment";
import { ModelListSelect } from "vue-search-select";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Products",
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
    ModelListSelect
  },
  data() {
    return {
      title: "Products",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Products",
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
      unitmodal_id: "",
      currentdate: moment(new Date()).format("YYYY-MM-DD"),
      fields: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "product_category",
          sortable: true,
          label: "Category",
        },
        {
          key: "instock",
          sortable: true,
          label: "IN STOCK",
        },
        {
          key: "baseunit.symbol",
          sortable: true,
          label: "UNITS",
        },
        {
          key: "minimum",
          sortable: true,
          label: "MINIMUM QTY",
        },
        {
          key: "type_field",
          sortable: true,
          label: "TYPE",
        },
        {
          key: "vat_filed",
          sortable: true,
          label: "VAT",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      fields1: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "product_category",
          sortable: true,
          label: "Category",
        },
        {
          key: "instock",
          sortable: true,
          label: "IN STOCK",
        },
        {
          key: "baseunit.symbol",
          sortable: true,
          label: "UNITS",
        },
        {
          key: "minimum",
          sortable: true,
          label: "MINIMUM QTY",
        },
        {
          key: "type_field",
          sortable: true,
          label: "TYPE",
        },
        {
          key: "vat_filed",
          sortable: true,
          label: "VAT",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
      ],

      filterProducts:{
        product: '',
        branch_id:0,
      },

      // edit product
      editdata: {
        name: "",
        code: "",
        minimum: 10,
        selling_price: "",
        reserve_price: "",
        wholesale_unitprice:"",
        wholesale_reserveprice:"",
        vat: 0,
        id: "",
      },
      // delete product
      deletedata: {
        name: "",
        id: "",
      },

    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("products", ["SaveStatus", "FilterStatus", "BProducts", "Units", "Categories","ViewProducts"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","branchid","Branches"]),
    ...mapGetters("general", ["OtherBranches"]),
    ...mapGetters("setting", ["GeneralSettings"]),


    /**
     * Total no. of records
     */

    rows() {
      return this.ViewProducts ? this.ViewProducts.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ViewProducts ? this.ViewProducts.length : 1;
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue == true) {
        if (this.type == "success") {
          Swal.fire("Success!", this.message.msg, "success");
          this.closeModal(this.modal_id);
        } else {
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
  },
  methods: {
    ...mapActions({
      FetchBProducts: "products/fetchBProducts",
      FetchProducts: "products/viewProducts",
      FetchUnits: "products/fetchUnits",
      EditProduct: "products/editProduct",
      DeleteProduct: "products/deleteProduct",
      FetchCategories: "products/fetchPCategories",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      FetchBranches: "auth/fetchBranches",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    FilterProducts(){
      this.FetchProducts(this.filterProducts);    
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
    resetEdit() {
      this.editdata.name = "";
      this.editdata.code = "";
      this.editdata.minimum = 10;
      this.editdata.selling_price = "";
      this.editdata.selling_price = "";
      this.editdata.wholesale_unitprice = "";
      this.editdata.wholesale_reserveprice = "";
      this.editdata.vat = 0;
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.reserve_price = data.item.reserve;
      this.editdata.wholesale_reserveprice = data.item.wholesale_reserveprice;
      this.editdata.selling_price = data.item.selling;
      this.editdata.wholesale_unitprice = data.item.wholesale_unitprice;
      this.editdata.vat = data.item.vat;
      this.editdata.code = data.item.code;
      this.editdata.minimum = data.item.minimum;
      this.editdata.id = data.item.id;
    },
    editProduct(modalId) {
      this.modal_id = modalId;
      this.EditProduct(this.editdata).then(() => {
        this.FetchProducts(this.filterProducts);
      });
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.name;
      this.deletedata.id = data.item.id;
    },
    deleteProduct(modalId) {
      this.modal_id = modalId;
      this.DeleteProduct(this.deletedata);
    },

    redirect(value) {
      this.$router.push(
        `/stock/movement/${btoa(value)}`
      );
    },
    //== close modal
    closeModal(modalId) {
        this.resetEdit();
        this.resetDelete();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchBProducts();
    this.FetchProducts(this.filterProducts);
    this.FetchUnits();
    this.FetchCategories();
    this.FetchGeneralSettings();
    this.FetchBranches();
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
            <div class="row" v-if="branchid == 0">
              <div class="col-md-8"></div>
              <div class="col-md-4 mb-3">
                <label>FILTER BY BRANCHES</label>
                
                <model-list-select
                  :list="Branches ? Branches : []"
                  v-model="filterProducts.branch_id"
                  option-value="id"
                  option-text="name"
                  placeholder="Select Branch"
                  @input="FilterProducts()"
                >
                </model-list-select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-4">
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
              <div class="col-md-4 form-group">
                <label>SEARCH BY CATEGORY</label>
                
                <model-list-select
                  :list="Categories ? Categories : []"
                  v-model="filterProducts.category"
                  option-value="id"
                  option-text="catName"
                  placeholder="Select Category"
                  @input="FilterProducts()"
                >
                </model-list-select>
              </div>
              <div class="col-md-4 form-group">
                Search Product
                <div class="input-group">
                <input
                  v-model="filterProducts.product"
                  type="text"
                  class="form-control"
                  @keyup.enter="FilterProducts()"
                /> 
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button" @click="FilterProducts()">
                    
                    <b-spinner
                      v-if="FilterStatus"
                      variant="light"
                      small
                      label="Spinning"
                  ></b-spinner>

                  <span>Search</span>
                  
                  </button>
                </div>
                
              </div>              
              </div>
              <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive mb-0">
              <b-table
                class="datatables"
                :items="ViewProducts?ViewProducts:[]"
                :fields="branchid==0?fields1:fields"
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
                <template #cell(vat_filed)="data">
                  <span v-if="data.item.vat==1">Yes</span>
                  <span v-else>No</span>
                </template>
                <template #cell(type_field)="data">
                  <span v-if="data.item.type==1">Product</span>
                  <span v-else>Service</span>
                </template>
                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    v-if="MyUserPerm.edit_products == 1"
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-product-modal
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    size="sm"
                    variant="success"
                    @click="redirect(data.item.id)"
                    ><i class="fa fa-retweet"></i> Movements</b-button
                  >
                  <b-button
                    v-if="MyUserPerm.delete_products == 1"
                    size="sm"
                    variant="danger"
                    v-b-modal.delete-product-modal
                    @click="showDeleteModal(data)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                  </b-button-group>
                </template>
              </b-table>
            </div>
            <Loader  v-if="!ViewProducts"/>
            <div class="row">
              <div class="col">
                <div
                  class="dataTables_paginate paging_simple_numbers float-end"
                >
                  <ul class="pagination pagination-rounded mb-2">
                    <!-- pagination -->
                    <b-pagination
                      v-model="currentPage"
                      :total-rows="rows"
                      :per-page="perPage"
                    ></b-pagination>
                  </ul>
                  <router-link :to="'/products/view'">View all</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal ends -->


    <!-- edit modal starts -->
            <b-modal id="edit-product-modal" hide-footer @hidden="resetEdit">
              <template #modal-title> Edit Product {{ editdata.name }}</template>
              <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Product Name<span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    @input="resetError()"
                    v-model="editdata.name"
                />
                </div>
            </div>
    
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Has VAT <span class="text-danger">*</span></label>
                <div class="input-group">
                <select class="form-control" v-model="editdata.vat">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                </div>
            </div>
    
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Minimum Quantity (<small>Warn Quantity</small>)</label>
                <div class="input-group">
                <input
                    type="number"
                    placeholder="Warn Quantity"
                    class="form-control"
                    v-model="editdata.minimum"
                />
                </div>
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Product Code</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Code"
                    class="form-control"
                    v-model="editdata.code"
                />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Selling Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Unit Selling Price"
                    class="form-control"
                    v-model="editdata.selling_price"
                />
                </div>
            </div>
    
        </div>
         <div v-if="GeneralSettings.allow_reserve_price == 1" class="col-md-6">
            <div class="form-group mb-3">
                <label>Reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Reserve Price"
                    class="form-control"
                    v-model="editdata.reserve_price"
                />
                </div>
            </div>
    
        </div>
        <div class="col-md-6"  v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Unit Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter wholesale Unit Price"
                    class="form-control"
                    v-model="editdata.wholesale_unitprice"
                />
                </div>
            </div>
    
        </div>
        <div class="col-md-6"  v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
            <div class="form-group mb-3">
                <label>Wholesale Reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter wholesale Reserve Price"
                    class="form-control"
                    v-model="editdata.wholesale_reserveprice"
                />
                </div>
            </div>
    
        </div>
    </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                  @click="closeModal('edit-product-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="editProduct('edit-product-modal')"
                > <i class="fa fa-check"></i>
                  Save Changes
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Saving"
                  ></b-spinner>
                </button>
              </div>
            </b-modal>
            <!-- modal ends -->

            <!-- modal delete product starts -->
            <b-modal
              header-bg-variant="danger"
              header-text-variant="light"
              body-text-variant="danger"
              footer-bg-variant="danger"
              footer-text-variant="danger"
              id="delete-product-modal"
              hide-footer
              @hidden="resetDelete"
            >
              <template #modal-title> Delete Product {{ deletedata.name }}</template>
              <div class="form-group mb-3">
                <div class="input-group">
                  <h5>
                    Do you want to delete this Product? Click Proceed Button to delete.
                  </h5>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('delete-product-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-danger"
                  @click="deleteProduct('delete-product-modal')"
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