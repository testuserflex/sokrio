<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
import { ModelListSelect } from "vue-search-select";


/**
 * Advanced table component
 */
export default {
  page: {
    title: "Spoilt Stock",
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
    ModelListSelect,
  },
  data() {
    return {
      title: "Spoilt Stock ",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Spoilt Stock",
          active: true,
        },
      ],
      totalRows: 1,
      dateError:'',
      quantityError:'',
      productError:'',

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
          key: "date",
          sortable: true,
          label: "Date",
        },
        {
          key: "product.product_name",
          sortable: true,
          label: "PRODUCT",
        },

        {
          key: "qty",
          sortable: true,
          label: "QTY",
        },

        {
          key: "buyingprice",
          sortable: true,
          label: "BUYING PRICE",
        },

        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "reason",
          sortable: true,
          label: "REASON",
        },
        {
          key: "user.name",
          sortable: true,
          label: "ADDED By",
        },
        // {
        //   key: "dateAdded",
        //   sortable: true,
        //   label: "DATE ADDED",
        // },
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
          key: "date",
          sortable: true,
          label: "Date",
        },
        {
          key: "product.product_name",
          sortable: true,
          label: "PRODUCT",
        },

        {
          key: "qty",
          sortable: true,
          label: "QTY",
        },

        {
          key: "buyingprice",
          sortable: true,
          label: "BUYING PRICE",
        },

        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "reason",
          sortable: true,
          label: "REASON",
        },
        {
          key: "user.name",
          sortable: true,
          label: "ADDED By",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
      ],
      FBatches: [],

      // Value from user input for adding unit
      data: {
        product: "",
        batch: "",
        quantity: "",
        date: "",
        reason: "",
      },
      // edit unit
      editdata: {
        name: "",
        category: "",
        id: "",
      },
      // delete unit
      deletedata: {
        name: "",
        id: "",
      },

      filterSpoilt:{
        branch_id:0
      }


    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("stock", ["SaveStatus", "Spoilt"]),
    ...mapGetters("products", ["Products","Batches"]),
    ...mapGetters('auth',["MyUserPerm","branchid","Branches"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Spoilt ? this.Spoilt.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Spoilt ? this.Spoilt.length : 1;
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue == true) {
        this.closeModal(this.modal_id);
        if (this.type == "success") {
          Swal.fire("Success!", this.message.msg, "success");
          // this.$toast.success(this.message.msg);
        } else {
          // this.$toast.error(this.message.msg);
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
  },
  methods: {
    ...mapActions({
      FetchProducts: "products/fetchProducts",
      FetchBatches: "products/fetchBatches",
      FetchSpoilt: "stock/fetchSpoilt",
      AddSpoilt: "stock/addSpoilt",
      DeleteSpoilt: "stock/deleteSpoilt",
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
    addSpoilt(modalId) {
      this.modal_id = modalId;

      if (this.data.product === "") {
        this.productError = "Product  field is required";
        return;
      } else {
        this.productError = "";
      }

      if (this.data.date === "") {
        this.dateError = "Date field is required";
        return;
      } else {
        this.dateError = "";
      }

      if (this.data.quantity === "") {
        this.quantityError = "Quantity field is required";
        return;
      } else {
        this.quantityError = "";
      }
      this.validateQuantity()
      if (this.quantityError!=""){
        return
      }
      this.AddSpoilt(this.data);
    },
    getBatches(){
      this.FBatches = this.Batches.filter((batch)=>{
        return batch.product_id == this.data.product;
      })

    },
    validateQuantity(){
      if (this.data.product !="" && this.data.quantity!=''){
        let selectedPdt = this.Products.filter((item)=>{
          return item.id == this.data.product;
        })
        let instock =selectedPdt[0].instock;
        if (instock<this.data.quantity){
          this.quantityError="Quantity Removed "+this.data.quantity+" cannot be greater than  Stock "+instock;
        }
      }
    },
    resetAdd() {
      this.data.product = "";
      this.data.date = "";
      this.data.quantity = "";
      this.data.batch = "";
      this.data.reason = "";
      this.modal_id = "";
    },
    resetEdit() {
      this.data.product = "";
      this.data.date = "";
      this.data.quantity = "";
      this.data.batch = "";
      this.data.reason = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.productError = "";
      this.dateError = "";
      this.quantityError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.category = data.item.category.catName;
      this.editdata.id = data.item.id;
    },
    editProduct(modalId) {
      this.modal_id = modalId;
      this.EditProduct(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.product.product_name;
      this.deletedata.id = data.item.id;
    },
    deleteSpoilt(modalId) {
      this.modal_id = modalId;
      this.DeleteSpoilt(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchSpoilt();
    this.FetchProducts();
    this.FetchBatches();
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
                  v-model="filterSpoilt.branch_id"
                  option-value="id"
                  option-text="name"
                  placeholder="Select Branch"
                  @input="FilterProducts()"
                >
                </model-list-select>
              </div>
            </div> 
            <div class="row">
              <div class="col-md-3">
                <h4 class="card-title">Spoilt Stock Report</h4>
              </div>
              <div class="col-md-3">
                <router-link :to="'/stock/pendingspoilt'">
                  <b-button variant="primary">Pending Spoilt Stock</b-button>
                </router-link>
              </div>
              <div class="col-md-3">
                <router-link :to="'/stock/rejectedspoilt'">
                  <b-button variant="primary">Rejected Spoilt Stock</b-button>
                </router-link>
              </div>
              <div v-if="MyUserPerm.record_spoilt_stock == 1 && branchid !=0" class="col-md-3" align="right">
                <b-button v-b-modal.add-product-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
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
                  :items="Spoilt?Spoilt:[]"
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

                <template #cell(actions)="data">
<!--                  <b-button-->
<!--                      size="sm"-->
<!--                      variant="primary"-->
<!--                      v-b-modal.edit-product-modal-->
<!--                      class="m-2"-->
<!--                      @click="showEditModal(data)"-->
<!--                  ><i class="fa fa-edit"></i></b-button-->
<!--                  >-->
                  <b-button v-if="data.item.expired!=1 && MyUserPerm.delete_spoilt_stock == 1"
                      size="sm"
                      variant="danger"
                      v-b-modal.delete-product-modal
                      @click="showDeleteModal(data)"
                  ><i class="fa fa-trash"></i></b-button
                  >
                </template>
              </b-table>
            </div>
            <Loader  v-if="!Spoilt"/>
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
          </div>
        </div>
      </div>
    </div>
    <!-- modal starts -->
    <b-modal id="add-product-modal" hide-footer @hidden="resetAdd">
      <template #modal-title> Add spoilt stock </template>
        <div class="form-group mb-3">
          <label>Select Product <span class="text-danger">*</span></label>
          <model-list-select
              :list="Products ? Products : []"
              v-model="data.product"
              option-value="id"
              option-text="name"
              class="mb-3"
              placeholder="Select Product"
              @input="resetError(),getBatches()"
          >
          </model-list-select>
          <div class="text-danger" v-if="productError">
            <em>{{productError }}</em>
          </div>
        </div>
      <div class="form-group mb-3">
        <label>Batch <span class="text-danger"></span></label>
        <model-list-select
            :list="FBatches ? FBatches : []"
            v-model="data.batch"
            option-value="id"
            option-text="batch_number"
            class="mb-3"
            placeholder="Select Batch"
            @input="resetError()"
        >
        </model-list-select>

      </div>

      <div class="form-group mb-3">
        <label>Quantity Removed <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
              type="number"
              step="any"
              placeholder="Enter Quantity "
              class="form-control"
              @input="resetError(),validateQuantity()"
              v-model="data.quantity"
          />
        </div>
        <div class="text-danger" v-if="quantityError">
          <em>{{ quantityError }}</em>
        </div>
      </div>

      <div class="form-group mb-3">
        <label>Date Removed <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
              type="date"
              placeholder="Enter Product Name"
              class="form-control"
              v-model="data.date"
              @input="resetError()"

          />
        </div>
        <div class="text-danger" v-if="dateError">
          <em>{{ dateError }}</em>
        </div>
      </div>
      <div class="form-group mb-3">
        <label>Reason  <span class="text-danger"></span></label>
        <div class="input-group">
          <textarea
              placeholder="Enter Product Name"
              class="form-control"
              @input="resetError()"
              v-model="data.reason"
          />
        </div>

      </div>

      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-danger"
            data-dismiss="modal"
            @click="closeModal('add-product-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-primary"
            @click="addSpoilt('add-product-modal')"
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
    <!-- edit modal starts -->
<!--    <b-modal id="edit-product-modal" hide-footer @hidden="resetEdit">-->
<!--      <template #modal-title> Edit Product {{ editdata.name }}</template>-->
<!--      <div class="form-group mb-3">-->
<!--        <label>Product Name <span class="text-danger">*</span></label>-->
<!--        <div class="input-group">-->
<!--          <input-->
<!--              type="text"-->
<!--              class="form-control"-->
<!--              @input="resetError()"-->
<!--              v-model="editdata.name"-->
<!--          />-->
<!--        </div>-->
<!--        <div class="text-danger" v-if="nameError">-->
<!--          <em>{{ nameError }}</em>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="form-group mb-3">-->
<!--        <label>Product Category <span class="text-danger">*</span></label>-->
<!--        <input-->
<!--            class="form-control"-->
<!--            v-model="editdata.category"-->
<!--            list="categories"-->
<!--            placeholder="Enter Product Category"-->
<!--            autocomplete="off"-->
<!--        />-->
<!--        <datalist id="categories">-->
<!--          <option-->
<!--              v-for="(cat, c) in Categories"-->
<!--              :data-value="cat.catName"-->
<!--              :key="c"-->
<!--          >-->
<!--            {{ cat.catName }}-->
<!--          </option>-->
<!--        </datalist>-->
<!--        <div class="text-danger" v-if="categoryError">-->
<!--          <em>{{ categoryError }}</em>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="modal-footer">-->
<!--        <button-->
<!--            type="button"-->
<!--            class="btn btn-danger"-->
<!--            data-dismiss="modal"-->
<!--            @click="closeModal('edit-product-modal')"-->
<!--        >-->
<!--          <i class="fa fa-times"></i> Close-->
<!--        </button>-->
<!--        <button-->
<!--            type="button"-->
<!--            class="btn btn-primary"-->
<!--            @click="editProduct('edit-product-modal')"-->
<!--        > <i class="fa fa-check"></i>-->
<!--          Save Changes-->
<!--          <b-spinner-->
<!--              v-if="SaveStatus"-->
<!--              variant="light"-->
<!--              small-->
<!--              label="Saving"-->
<!--          ></b-spinner>-->
<!--        </button>-->
<!--      </div>-->
<!--    </b-modal>-->
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
            Do you want to delete this record? Click Proceed Button to delete.
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
            @click="deleteSpoilt('delete-product-modal')"
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
.btn-primary { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
