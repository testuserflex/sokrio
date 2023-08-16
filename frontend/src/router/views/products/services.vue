<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Services",
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
      title: "Services",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Services",
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
          key: "name",
          sortable: true,
          label: "NAME",
        }, 
        {
          key: "price",
          sortable: true,
          label: "PRICE",
        },
        {
          key: "costp",
          sortable: true,
          label: "COST PRICE",
        },
        {
          key: "user",
          sortable: true,
          label: "ADDED BY",
        },
        {
          key: "added_on",
          sortable: true,
          label: "ADDED ON",
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
          key: "price",
          sortable: true,
          label: "PRICE",
        },
        {
          key: "costp",
          sortable: true,
          label: "COST PRICE",
        },
        {
          key: "user",
          sortable: true,
          label: "ADDED BY",
        },
        {
          key: "added_on",
          sortable: true,
          label: "ADDED ON",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
        
      ],
      existingProduct: [],
      nameError: "",
      productError: "",
      sellingPriceError: "",

      // Value from user input for adding product
      data: {
        name: "",
        product: "",
        selling_price: "",
        cost_price: 0,
      },
      // edit product
      editdata: {
        name: "",
        selling_price: "",
        cost_price: 0,
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
    ...mapGetters("products", ["SaveStatus", "BProducts","Services"]),
    ...mapGetters("auth", ["branchid","Branches"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Services ? this.Services.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Services ? this.Services.length : 1;
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
      FetchServices: "products/fetchServices",
      AddService: "products/addService",
      EditService: "products/editService",
      DeleteService: "products/deleteService",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
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

    checkName() {
      //when creating a new one
      if (this.data.name != "") {
        this.existingProduct = this.Services.filter((product) => {
          return product.name.toUpperCase() == this.data.name.toUpperCase();
        });
        if (this.existingProduct.length) {
          this.nameError = this.data.name + " exists already";
        } else {
          this.nameError = "";
        }
      } else {
        this.existingProduct = [];
      }
    },

    assignName(){
        this.data.name = this.data.product;
    },

    addCommas(){
        this.data.selling_price = this.thousandSeperator(this.data.selling_price);
    },
    addCommas2(){
        this.data.cost_price = this.thousandSeperator(this.data.cost_price);
    },
    addCommasx(){
        this.editdata.selling_price = this.thousandSeperator(this.editdata.selling_price);
    },
    addCommas2x(){
        this.editdata.cost_price = this.thousandSeperator(this.editdata.cost_price);
    },

    addService(modalId) {
      this.modal_id = modalId;

      if (this.data.name === "") {
        this.nameError = "Service name field is required";
        return;
      } else {
        this.nameError = "";
      }
      if (this.data.product === "") {
        this.productError = "Please Map this Service";
        return;
      } else {
        this.productError = "";
      }
      if (this.existingProduct.length) {
        this.nameError = this.data.name + " exists already please";
        return;
      } else {
        this.nameError = "";
      }
      if (this.data.selling_price === "") {
        this.sellingPriceError = "Service Price is required";
        return;
      } else {
        this.sellingPriceError = "";
      }

      this.AddService(this.data);
    },

    resetAdd() {
      this.data.name = "";
      this.data.product = "";
      this.data.selling_price = "";
      this.data.cost_price = 0;
      this.modal_id = "";
    },
    resetEdit() {
      this.editdata.name = "";
      this.editdata.selling_price = "";
      this.editdata.cost_price = 0;
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.nameError = "";
      this.productError = "";
      this.sellingPriceError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.selling_price = this.thousandSeperator(data.item.selling);
      this.editdata.cost_price = this.thousandSeperator(data.item.cost);
      this.editdata.id = data.item.id;
    },
    editService(modalId) {
      this.modal_id = modalId;
      this.EditService(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.name;
      this.deletedata.id = data.item.id;
    },
    deleteService(modalId) {
      this.modal_id = modalId;
      this.DeleteService(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
        this.resetError();
        this.resetAdd();
        this.resetEdit();
        this.resetDelete();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchBProducts();
    this.FetchServices();
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
                      <h4 class="card-title">All Services</h4>
                  </div>
                  <div class="col-md-3" align="right" v-if="BranchData.BranchId != 0">
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
                :items="Services?Services:[]"
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
                <template #cell(price)="data">
                  {{ thousandSeperator(data.item.selling) }}
                </template>
                <template #cell(costp)="data">
                  {{ thousandSeperator(data.item.cost) }}
                </template>

                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-product-modal
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
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
            <Loader  v-if="!Services"/>
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
    <template #modal-title> Add New Service </template>
    <div class="row">
            <div class="form-group mb-3">
            <label>Select Global Service <span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="data.product"
                list="products"
                placeholder="Choose Service"
                @input="resetError(), assignName()"
                @blur="checkName()"
                autocomplete="off"
                />
                <datalist id="products">
                <option
                    v-for="(pdt, p) in BProducts"
                    :data-value="pdt.name"
                    :key="p"
                >
                    {{ pdt.name }}
                </option>
                </datalist>
            <div class="text-danger" v-if="productError">
            <em>productError }}</em>
            </div>
        </div>
        
            <div class="form-group mb-3">
                <label>Service Name (<small>The Name you want to use</small>)<span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Service Name"
                    class="form-control"
                    @keyup="checkName"
                    @input="resetError()"
                    v-model="data.name"
                />
                </div>
                <div class="text-danger" v-if="nameError">
                    <em>{{ nameError }}</em>
                </div>
            </div>
    
            <div class="form-group mb-3">
                <label>Service Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Service Price"
                    class="form-control"
                    v-model="data.selling_price"
                    @input="addCommas()"
                />
                </div>
                <div class="text-danger" v-if="sellingPriceError">
                    <em>{{ sellingPriceError }}</em>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Service Cost Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Service Cost Price"
                    class="form-control"
                    v-model="data.cost_price"
                    @input="addCommas2()"
                />
                </div>
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
        @click="addService('add-product-modal')"
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
            <b-modal id="edit-product-modal" hide-footer @hidden="resetEdit">
              <template #modal-title> Edit Service {{ editdata.name }}</template>
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
                <div class="text-danger" v-if="nameError">
                    <em>{{ nameError }}</em>
                </div>
            </div>

       <div class="form-group mb-3">
              <label>Service Price <span class="text-danger">*</span></label>
              <div class="input-group">
              <input
                  type="text"
                  placeholder="Enter Service Price"
                  class="form-control"
                  v-model="editdata.selling_price"
                  @input="addCommasx()"
              />
              </div>
              <div class="text-danger" v-if="sellingPriceError">
                  <em>{{ sellingPriceError }}</em>
              </div>
          </div>
          <div class="form-group mb-3">
                <label>Service Cost Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Service Cost Price"
                    class="form-control"
                    v-model="editdata.cost_price"
                    @input="addCommas2x()"
                />
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
                  @click="editService('edit-product-modal')"
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
                  @click="deleteService('delete-product-modal')"
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
