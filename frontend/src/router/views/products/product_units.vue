<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import { ModelListSelect } from "vue-search-select";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";

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
    ModelListSelect,
    Loader,
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
      fields: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "name",
          sortable: true,
          label: "PRODUCT",
        },
        {
          key: "details",
          sortable: true,
          label: "",
        },
      ],
      unitError: "",
      productError: "",
      qtyError: "",
      sellingPriceError: "",

      // Value from user input for adding product
      data: {
        product: "",
        code: "",
        selling_price: "",
        wholesale_unitprice: "",
        base_quantity: 0,
        reserve_price: "",
        wholesale_reserveprice: "",
        unit: "",
      },
      // edit product
      editdata: {
        product: "",
        base_quantity: "",
        code: "",
        unit: "",
        selling_price: "",
        reserve_price: "",
        wholesalereserve_price: "",
        wholesale_price: "",
        id: "",
      },
      // delete product
      deletedata: {
        name: "",
        product: "",
        id: "",
      },
      defaultdata: {
        name: "",
        product: "",
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
    ...mapGetters("products", ["SaveStatus","Units","Products"]),
    ...mapGetters("auth", ["MyUserPerm"]),
    ...mapGetters("setting", ["GeneralSettings"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Products ? this.Products.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Products ? this.Products.length : 1;
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
      FetchProducts: "products/fetchProducts",
      FetchUnits: "products/fetchUnits",
      AddProductUnit: "products/addProductUnit",
      EditProductUnit: "products/editProductUnit",
      DeleteProductUnit: "products/deleteProductUnit",
      DefaultProductUnit: "products/changeDefaultUnit",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
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

    addProductUnit(modalId) {
      this.modal_id = modalId;

      if (this.data.product === "") {
        this.productError = "Please select a product";
        return;
      } else {
        this.productError = "";
      }
      if (this.data.unit === "") {
        this.unitError = "Product unit is required";
        return;
      } else {
        this.unitError = "";
      }
      if (this.data.base_quantity === "") {
        this.qtyError = "Unit Base Quantity is required";
        return;
      } else {
        this.qtyError = "";
      }
      if (this.data.selling_price == "") {
        this.sellingPriceError = "Product Unit selling price is required";
        return;
      } else {
        this.sellingPriceError = "";
      }

      this.AddProductUnit(this.data);
    },

    resetAdd() {
      this.data.product = "";
      this.data.code = "";
      this.data.selling_price = "";
      this.data.wholesale_unitprice = "";
      this.data.base_quantity = 0;
      this.data.reserve_price = "";
      this.data.wholesale_reserveprice = "";
      this.data.unit = "";
      this.modal_id = "";
    },
    resetEdit() {
      this.editdata.product = "";
      this.editdata.code = "";
      this.editdata.selling_price = "";
      this.editdata.reserve_price = "";
      this.editdata.wholesale_price = "";
      this.editdata.wholesalereserve_price = "";
      this.editdata.base_quantity = "";
      this.editdata.is_base = "";
      this.editdata.id = "";
      this.modal_id = "";
    },
    resetDefault() {
      this.defaultdata.name = "";
      this.defaultdata.product = "";
      this.defaultdata.id = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.product = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.qtyError = "";
      this.unitError = "";
      this.productError = "";
      this.sellingPriceError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.unit.name;
      this.editdata.reserve_price = data.reserve;
      this.editdata.selling_price = data.selling;
      this.editdata.wholesale_price = data.wholesale_unitprice;
      this.editdata.wholesalereserve_price = data.wholesale_reserveprice;
      this.editdata.product = data.product;
      this.editdata.code = data.product_code;
      this.editdata.base_quantity = data.base_qty;
      this.editdata.is_base = data.is_base;
      this.editdata.unit = data.unit.name;
      this.editdata.id = data.id;
    },
    editProductUnit(modalId) {
      this.modal_id = modalId;
      this.EditProductUnit(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.unit.name;
      this.deletedata.product = data.product;
      this.deletedata.id = data.id;
    },
    showDefaultModal(data) {
      this.defaultdata.name = data.unit.name;
      this.defaultdata.product = data.product;
      this.defaultdata.id = data.id;
    },
    deleteProductUnit(modalId) {
      this.modal_id = modalId;
      this.DeleteProductUnit(this.deletedata);
    },
    changeDefault(modelId){
        this.modal_id = modelId;
        this.DefaultProductUnit(this.defaultdata);
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
    this.FetchProducts();
    this.FetchUnits();
    this.FetchGeneralSettings();
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
                      <h4 class="card-title">All Product And Their Units</h4>
                  </div>
                  <div v-if="MyUserPerm.add_product_units == 1 && GeneralSettings.allow_multiple_units == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
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
                :items="Products?Products:[]"
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
                <template #cell(details)="data">
                  <table  class="table">
                      <thead>
                            <tr width="100%">
                              <th>UNIT</th>
                              <th>BASE QTY</th>
                              <th>SELLING PRICE</th>
                              <th>RESERVE PRICE</th>
                              <th v-if="GeneralSettings.enable_wholeselling == 1">WHOLE SELLING PRICE</th>
                              <th v-if="GeneralSettings.enable_wholeselling == 1">WHOLESALE RESERVE PRICE</th>
                              <th>ACTION</th>
                            </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                              <td>{{ det.unit.name }}</td>
                              <td>{{ det.base_qty }}</td>
                              <td>{{ det.selling?(det.selling).toLocaleString():0 }}</td>
                              <td>{{ det.reserve?(det.reserve).toLocaleString():0 }}</td>
                              <td v-if="GeneralSettings.enable_wholeselling == 1">{{ det.wholesale_unitprice?(det.wholesale_unitprice).toLocaleString():0 }}</td>
                              <td v-if="GeneralSettings.enable_wholeselling == 1">{{ det.wholesale_reserveprice?(det.wholesale_reserveprice).toLocaleString():0 }}</td>
                              <td>
                                  <b-button
                                        v-if="MyUserPerm.edit_product_units == 1"
                                        size="sm"
                                        variant="primary"
                                        v-b-modal.edit-product-modal
                                        class="m-2"
                                        @click="showEditModal(det)"
                                        ><i class="fa fa-edit"></i> Edit</b-button
                                    >
                                    <b-button size="sm"
                                        v-if="!det.is_base && MyUserPerm.delete_product_units == 1"
                                        variant="danger"
                                        v-b-modal.delete-product-modal
                                        @click="showDeleteModal(det)"
                                        ><i class="fa fa-trash"></i> Delete</b-button>
                                        <b-button size="sm"
                                        v-if="!det.is_base"
                                        class="bg-soft"
                                        v-b-modal.default-unit-modal
                                        @click="showDefaultModal(det)"
                                        ><i class="fa fa-cog"></i> Set as Default</b-button>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                </template>
              </b-table>
            </div>
            <Loader  v-if="!Products"/>
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
    <template #modal-title> Add New Product </template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
            <label>Select Product <span class="text-danger">*</span></label>
                <model-list-select
                  :list="Products ? Products : []"
                  v-model="data.product"
                  option-value="id"
                  option-text="name"
                  class="mb-3"
                  placeholder="Select Product"
                  @input="resetError()"
                >
                </model-list-select>
            <div class="text-danger" v-if="productError">
            <em>productError }}</em>
            </div>
        </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Product Unit <span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="data.unit"
                list="units"
                placeholder="Enter Product unit"
                autocomplete="off"
                />
                <datalist id="units">
                <option
                    v-for="(cat, c) in Units"
                    :data-value="cat.name"
                    :key="c"
                >
                    {{ cat.symbol }}
                </option>
                </datalist>
            <div class="text-danger" v-if="unitError">
            <em>{{ unitError }}</em>
            </div>
        </div>
        </div>
         <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Product Code</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Product Unit Code"
                    class="form-control"
                    v-model="data.code"
                />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Base Quantity</label>
                <div class="input-group">
                <input
                    type="number"
                    placeholder="Base Quantity"
                    class="form-control"
                    v-model="data.base_quantity"
                />
                </div>
                <div class="text-danger" v-if="qtyError">
            <em>{{ qtyError }}</em>
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
                    v-model="data.selling_price"
                />
                </div>
                <div class="text-danger" v-if="sellingPriceError">
                    <em>{{ sellingPriceError }}</em>
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
                    v-model="data.reserve_price"
                />
                </div>
            </div>
    
        </div>
        <div class="col-md-6" v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Unit Price"
                    class="form-control"
                    v-model="data.wholesale_unitprice"
                />
                </div>
            </div>
    
        </div>
         <div class="col-md-6" v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
            <div class="form-group mb-3">
                <label>Wholesale reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Reserve Price"
                    class="form-control"
                    v-model="data.wholesale_reserveprice"
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
        @click="closeModal('add-product-modal')"
        >
        <i class="fa fa-times"></i> Close
        </button>
        <button
        type="button"
        class="btn btn-primary"
        @click="addProductUnit('add-product-modal')"
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
              <template #modal-title> Edit Unit <small class="warning">{{ editdata.name }}</small> for Product <small class="warning">{{ editdata.product}}</small></template>
              <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Product Unit <span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="editdata.unit"
                list="units"
                placeholder="Enter Product unit"
                autocomplete="off"
                />
                <datalist id="units">
                <option
                    v-for="(cat, c) in Units"
                    :data-value="cat.name"
                    :key="c"
                >
                    {{ cat.symbol }}
                </option>
                </datalist>
            <div class="text-danger" v-if="unitError">
            <em>{{ unitError }}</em>
            </div>
        </div>
        </div>
         <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Product Code</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Product Unit Code"
                    class="form-control"
                    v-model="editdata.code"
                />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Base Quantity</label>
                <div class="input-group">
                <input v-if="editdata.is_base==1" disabled
                    type="number"
                    placeholder="Base Quantity"
                    class="form-control"
                    v-model="editdata.base_quantity"
                />
                <input v-else
                    type="number"
                    placeholder="Base Quantity"
                    class="form-control"
                    v-model="editdata.base_quantity"
                />
                </div>
                <div class="text-danger" v-if="qtyError">
            <em>{{ qtyError }}</em>
            </div>
            </div>
        </div>
        <div class="col-md-12">
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
                <div class="text-danger" v-if="sellingPriceError">
                    <em>{{ sellingPriceError }}</em>
                </div>
            </div>
    
        </div>
         <div v-if="GeneralSettings.allow_reserve_price == 1" class="col-md-12">
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
        <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Price <span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Unit Selling Price"
                    class="form-control"
                    v-model="editdata.wholesale_price"
                />
                </div>
            </div>
    
        </div>
         <div class="col-md-12"  v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
            <div class="form-group mb-3">
                <label>Wholesale reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Reserve Price"
                    class="form-control"
                    v-model="editdata.wholesalereserve_price"
                />
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
                  @click="editProductUnit('edit-product-modal')"
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
              <template #modal-title> Delete Unit <small><em>{{ deletedata.name }}</em></small> from this Product</template>
              <div class="form-group mb-3">
                <div class="input-group">
                  <h5>
                    Do you want to delete Unit <small class="text-danger"><em>{{ deletedata.name }}</em></small> from Product <small class="text-danger"><em>{{ deletedata.product }}</em></small>? Click Proceed Button to delete.
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
                  @click="deleteProductUnit('delete-product-modal')"
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

             <!-- modal change default unit starts -->
            <b-modal
              header-bg-variant="warning"
              header-text-variant="light"
              body-text-variant="warning"
              footer-bg-variant="warning"
              footer-text-variant="warning"
              id="default-unit-modal"
              hide-footer
              @hidden="resetDefault()"
            >
              <template #modal-title> Set Product Unit <small><em>{{ defaultdata.name }}</em></small> as Base Unit</template>
              <div class="form-group mb-2">
                <div class="input-group">
                  <h5>
                    Do you want to set Unit <small class="text-warning"><em>{{ defaultdata.name }}</em></small> as Base Unit for Product <small class="text-warning"><em>{{ defaultdata.product }}</em></small>? Click Proceed Button to continue.
                  </h5>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('default-unit-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="changeDefault('default-unit-modal')"
                >
                  <i class="fa fa-cog"></i> Proceed
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Changing"
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
