<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
import axios from "axios";
import { ModelListSelect } from "vue-search-select";
import moment from "moment";

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
    ModelListSelect,
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
      json_fields: {
        CATEGORY: "category.name",
        PRODUCT_NAME: "name",
        UNITS: "unitname",
        QUANTITY: "instock",
        MINIMUM_QUANTITY: "minimum",
        SELLING_PRICE: "selling",
        RESERVE_PRICE: "reserve",
        CODE: "code",
        VAT: "vat",
        IS_PRODUCT: "type",
      },
      json_meta: [
        [
          {
            key: "charset",
            value: "utf-8",
          },
        ],
      ],
      is_uploading: false,
      existingProduct: [],
      nameError: "",
      unitError: "",
      categoryError: "",
      productError: "",
      sellingPriceError: "",
      branchError: "",
      UnitProductunitError: "",
      UnitqtyError: "",
      UnitsellingPriceError: "",

      // Value from user input for adding product
      data: {
        name: "",
        product: "",
        category: "",
        code: "",
        minimum: 10,
        selling_price: "",
        buying_price: "",
        quantity: 0,
        reserve_price: "",
        vat: 0,
        unit: "",
        otherunits: [],
        wholesale_reserveprice: "",
        wholesale_unitprice: "",
      },
      transfer: {
        branch: "",
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

      // product units data
      units1: {
        unit: "",
        code: "",
        selling_price: "",
        base_quantity: 0,
        reserve_price: "",
        wholesale_reserveprice: "",
        wholesale_unitprice: "",
      },
      units2: {
        unit: "",
        code: "",
        selling_price: "",
        base_quantity: 0,
        reserve_price: "",
        wholesale_reserveprice: "",
        wholesale_unitprice: "",
      },

      filterProducts:{
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
    ...mapGetters("products", ["SaveStatus", "FilterStatus", "BProducts", "Units", "Categories","ProductsData"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","branchid","Branches"]),
    ...mapGetters("general", ["OtherBranches"]),
    ...mapGetters("setting", ["GeneralSettings"]),


    /**
     * Total no. of records
     */

    rows() {
      return this.ProductsData ? this.ProductsData.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ProductsData ? this.ProductsData.length : 1;
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
      FetchProducts: "products/fetchProductsData",
      FetchUnits: "products/fetchUnits",
      AddProduct: "products/addProduct",
      EditProduct: "products/editProduct",
      DeleteProduct: "products/deleteProduct",
      UploadExcel: "products/productImports",
      FetchCategories: "products/fetchPCategories",
      FetchOtherBranches: "general/fetchBranches",
      FetchBranches: "auth/fetchBranches",
      SendProducts: "products/TransferProducts",
      AddProductUnit: "products/addProductUnit",
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

    checkName() {
      //when creating a new one
      if (this.data.name != "") {
        this.existingProduct = this.ProductsData.filter((product) => {
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

    assignName(){
        this.data.name = this.data.product;
    },
    addBuyCommas(){
        this.data.buying_price = this.thousandSeperator(this.data.buying_price);
    },
    addSellCommas(){
        this.data.selling_price = this.thousandSeperator(this.data.selling_price);
    },
    addReserveCommas(){
        this.data.reserve_price = this.thousandSeperator(this.data.reserve_price);
    },
    addUnitSellCommas1(){
        this.units1.selling_price = this.thousandSeperator(this.units1.selling_price);
    },
    addUnitReserveCommas1(){
        this.units1.reserve_price = this.thousandSeperator(this.units1.reserve_price);
    },
    addUnitSellCommas2(){
        this.units2.selling_price = this.thousandSeperator(this.units2.selling_price);
    },
    addUnitReserveCommas2(){
        this.units2.reserve_price = this.thousandSeperator(this.units2.reserve_price);
    },

    addProduct(modalId) {
      this.modal_id = modalId;
      if (this.data.category === "") {
        this.categoryError = "Category field is required";
        return;
      } else {
        this.categoryError = "";
      }
      if (this.data.name === "") {
        this.nameError = "Product name field is required";
        return;
      } else {
        this.nameError = "";
      }
      if (this.existingProduct.length) {
        this.nameError = this.data.name + " exists already please";
        return;
      } else {
        this.nameError = "";
      }
      if (this.data.unit === "") {
        this.unitError = "Product unit is required";
        return;
      } else {
        this.unitError = "";
      }

      this.AddProduct(this.data);
    },

    resetAdd() {
      this.data.name = "";
      this.data.product = "";
      this.data.category = "";
      this.data.code = "";
      this.data.minimum = 10;
      this.data.selling_price = "";
      this.data.buying_price = "";
      this.data.quantity = 0;
      this.data.reserve_price = "";
      this.data.wholesale_reserveprice = "";
      this.data.wholesale_unitprice = "";
      this.data.vat = 0;
      this.data.unit = "";
      this.units1.unit = "";
      this.units1.code = "";
      this.units1.selling_price = "";
      this.units1.base_quantity = "";
      this.units1.reserve_price = "";
      this.units1.wholesale_reserveprice = "";
      this.units1.wholesale_unitprice = "";
      this.units2.unit = "";
      this.units2.code = "";
      this.units2.selling_price = "";
      this.units2.base_quantity = "";
      this.units2.reserve_price = "";
      this.units2.wholesale_reserveprice = "";
      this.units2.wholesale_unitprice = "";
      this.data.otherunits = [];      
      this.modal_id = "";
      this.unitmodal_id = "";

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

    resetError() {
      this.nameError = "";
      this.categoryError = "";
      this.unitError = "";
      this.productError = "";
      this.sellingPriceError = "";
      this.UnitProductunitError= "";
      this.UnitqtyError= "";
      this.UnitsellingPriceError= "";
    },

    resetUnitError() {
      this.UnitProductunitError= "";
      this.UnitqtyError= "";
      this.UnitsellingPriceError= "";
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
      this.EditProduct(this.editdata);
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
        this.resetError();
        this.resetAdd();
        this.resetEdit();
        this.resetDelete();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
    //== close modal
    closeUnitModal(modalId) {
        this.resetUnitError();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
    handleUpload() {
      this.file = this.$refs.file.files[0];
    },
    importFile(modelId) {
      this.is_uploading = true;
      let formData = new FormData();
      formData.append("file", this.file);

      const headers = { 'Content-Type': 'multipart/form-data' };
      axios.post('import_products', formData, { headers }).then((res) => {
        this.is_uploading = false;
        this.closeModal(modelId);
        if (res.status == 200) {
          let message = { 'msg': 'Success upload'}
          this.FetchProducts(this.filterProducts);
          this.$store.dispatch('notification/success',message);
        } else {
          let message = { 'msg': 'Something went wrong and upload failed' }
          this.$store.dispatch('notification/error',message);
        }
      }).catch(()=>{
        this.is_uploading = false;
        this.closeModal(modelId);
        let message = { 'msg': 'Something went wrong and upload failed' }
        this.$store.dispatch('notification/error',message);
      });
    },
    resetUploadModal() {},
    resetTransferModal(){},
    resetBranchError() {
      this.branchError = "";
    },

    // Transfer products
    TransferPdts(modalId) {
      if (this.transfer.branch == "") {
        this.branchError = "Destination Branch is required";
        return;
      } else {
        this.branchError = "";
      }
      this.SendProducts(this.transfer);
      this.closeModal(modalId)
    },

    // Fetch Pdts
    FilterProducts(){
      this.FetchProducts(this.filterProducts);
    },

    // Add product units
    addProductUnit(modalId) {
      if (this.units1.unit === "") {
        this.UnitProductunitError = "Unit Name is required";
        return;
      } else {
        this.UnitProductunitError = "";
      }
      if (this.units1.base_quantity === "") {
        this.UnitqtyError = "Unit Base Quantity is required";
        return;
      } else {
        this.UnitqtyError = "";
      }
      if (this.units1.selling_price == "") {
        this.UnitsellingPriceError = "Unit selling price is required";
        return;
      } else {
        this.UnitsellingPriceError = "";
      }
      this.data.otherunits.push(this.units1, this.units2);
      
      this.closeUnitModal(modalId);
    },
  },
  created() {
    this.FetchBProducts();
    this.FetchProducts(this.filterProducts);
    this.FetchUnits();
    this.FetchCategories();
    this.FetchBranches();
    this.FetchOtherBranches();
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
              <div class="row">
                  <div class="col-md-8">
                      <h4 class="card-title">All Products</h4>
                  </div>
                  <div class="col-md-2" align="right">
                    <download-excel
                      class="btn btn-info"
                      :data="ProductsData"
                      :fields="json_fields"
                      :worksheet="'PRODUCT STOCK LIST AS OF' + currentdate"
                      type="csv"
                      :name="'Product_stock_list' + currentdate + '.xls'"
                    >
                      Export Excel <i class="fa fa-download"></i>
                    </download-excel>
                  </div>
                  <div v-if="MyUserPerm.add_products == 1 && BranchData.BranchId != 0" class="col-md-2" align="right">
                      <b-button v-b-modal.add-product-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
                  </div>
              </div>
              <div class="row mt-3">
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
                :items="ProductsData?ProductsData:[]"
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
                <template #cell(vat_filed)="data">
                  <span v-if="data.item.vat==1">Yes</span>
                  <span v-else>No</span>
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
            <Loader  v-if="!ProductsData"/>
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
    <b-modal id="add-product-modal" size="lg" hide-footer @hidden="resetAdd">
    <template #modal-title> Add New Product </template>
    <div class="row">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group mb-3">
            <label>Select Business Category <span class="text-danger">*</span></label>
            <input
            class="form-control"
            v-model="data.category"
            list="categories"
            placeholder="Select Business Category"
            @input="resetError()"
            />
            <datalist id="categories">
            <option
                v-for="(cat, c) in Categories"
                :data-value="cat.catName"
                :key="c"
            >
                {{ cat.catName }}
            </option>
            </datalist>
            <div class="text-danger" v-if="categoryError">
            <em>{{ categoryError }}</em>
            </div>
          </div>
        </div>
    </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
            <label>Select Global Product <span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="data.product"
                list="products"
                placeholder="Choose Product"
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
            <em>{{ productError }}</em>
            </div>
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Product Name (<small>The Name you want to use</small>)<span class="text-danger">*</span></label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Name"
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
    
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Has VAT <span class="text-danger">*</span></label>
                <div class="input-group">
                <select class="form-control" v-model="data.vat">
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
                    v-model="data.minimum"
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
                    @keyup="checkName()"
                    v-model="data.code"
                />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Quantity In Stock</label>
                <div class="input-group">
                <input
                    type="number"
                    placeholder="InStock"
                    class="form-control"
                    v-model="data.quantity"
                />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Product Unit <span class="text-danger">*</span>
                  <span v-if="MyUserPerm.add_product_units == 1 && GeneralSettings.allow_multiple_units == 1">
                    <b-button
                      size="sm"
                      variant="link"
                      v-b-modal.add-productunit-modal
                    >
                    More Units
                  </b-button>
                  </span>
                </label>
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

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Buying Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Unit Buying Price"
                    class="form-control"
                    v-model="data.buying_price"
                    @input="addBuyCommas()"
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
                    v-model="data.selling_price"
                    @input="addSellCommas()"
                />
                </div>
                <div class="text-danger" v-if="sellingPriceError">
                    <em>{{ sellingPriceError }}</em>
                </div>
            </div>
    
        </div>
        <div class="col-md-6" v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Product Unit Selling Price"
                    class="form-control"
                    v-model="data.wholesale_unitprice"
                />
                </div>
            </div>
    
        </div>
         
        <div class="row">
          <div v-if="GeneralSettings.allow_reserve_price == 1" class="col-md-6">
            <div class="form-group mb-3">
                <label>Reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Reserve Price"
                    class="form-control"
                    v-model="data.reserve_price"
                    @input="addReserveCommas()"
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
                    placeholder="Enter Reserve Price"
                    class="form-control"
                    v-model="data.wholesale_reserveprice"
                />
                </div>
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
        @click="addProduct('add-product-modal')"
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

    <!--Product units modal starts -->
    <b-modal id="add-productunit-modal" size="lg" hide-footer>
    <template #modal-title> Add New Product Unit </template>
    <div class="row">
      <div class="col-md-6">
        <label><h5>Unit 1</h5></label>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Unit Name<span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="units1.unit"
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
            <div class="text-danger" v-if="UnitProductunitError">
            <em>{{ UnitProductunitError }}</em>
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
                    v-model="units1.code"
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
                    v-model="units1.base_quantity"
                />
                </div>
                <div class="text-danger" v-if="UnitqtyError">
            <em>{{ UnitqtyError }}</em>
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
                    v-model="units1.selling_price"
                    @input="addUnitSellCommas1()"
                />
                </div>
                <div class="text-danger" v-if="sellingPriceError">
                    <em>{{ UnitsellingPriceError }}</em>
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
                    v-model="units1.reserve_price"
                    @input="addUnitReserveCommas1()"
                />
                </div>
            </div>
    
        </div>
        <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Unit Selling Price"
                    class="form-control"
                    v-model="units1.wholesale_unitprice"
                />
                </div>
            </div>
    
        </div>
         <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
            <div class="form-group mb-3">
                <label>Wholesale reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Reserve Price"
                    class="form-control"
                    v-model="units1.wholesale_reserveprice"
                />
                </div>
            </div>
    
        </div>
      </div>
      <div class="col-md-6">
        <label><h5>Unit 2</h5></label>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Unit Name<span class="text-danger">*</span></label>
                <input
                class="form-control"
                v-model="units2.unit"
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
                    v-model="units2.code"
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
                    v-model="units2.base_quantity"
                />
                </div>
                <div class="text-danger" v-if="UnitqtyError">
            <em>{{ UnitqtyError }}</em>
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
                    v-model="units2.selling_price"
                    @input="addUnitSellCommas2()"
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
                    v-model="units2.reserve_price"
                    @input="addUnitReserveCommas2()"
                />
                </div>
            </div>
    
        </div>
        <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1">
            <div class="form-group mb-3">
                <label>Wholesale Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Unit Selling Price"
                    class="form-control"
                    v-model="units2.wholesale_unitprice"
                />
                </div>
            </div>
    
        </div>
         <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
            <div class="form-group mb-3">
                <label>Wholesale reserve Price</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Wholesale Reserve Price"
                    class="form-control"
                    v-model="units2.wholesale_reserveprice"
                />
                </div>
            </div>
    
        </div>
      </div>
    </div>
    
    <div class="modal-footer">
        <button
        type="button"
        class="btn btn-danger"
        data-dismiss="modal"
        @click="closeUnitModal('add-productunit-modal')"
        >
        <i class="fa fa-times"></i> Close
        </button>
        <button
        type="button"
        class="btn btn-primary"
        @click="addProductUnit('add-productunit-modal')"
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
                <div class="text-danger" v-if="nameError">
                    <em>{{ nameError }}</em>
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
    <!--PRODUCT EXCEL UPLOAD MODAL-->
                  <b-modal
                    id="import-products-modal"
                    ref="modal"
                    hide-footer
                    title="Upload an excel file of products"
                    @hidden="resetUploadModal"
                  >
                      <div class="mb-2">
                        <label class="col-form-label" for="upload-file"
                          >Select file to upload:</label
                        >
                        <input
                          id="upload-file"
                          class="form-control"
                          type="file"
                          ref="file"
                          @change="handleUpload"
                        />
                      </div>
                    <div class="modal-footer">
                      <button
                        class="btn btn-secondary"
                        @click="closeModal('import-products-modal')"
                        type="button"
                      >
                        Close
                      </button>
                      <button class="btn btn-primary" type="button" @click="importFile('import-products-modal')">
                        Import Items
                        <b-spinner
                            v-if="is_uploading"
                            variant="light"
                            small
                            role="status"
                        ></b-spinner>
                      </button>
                    </div>
                  </b-modal>
    <!-- PRODUCT EXCEL UPLOAD END -->
    <!--TRANSFER PDTS TO ANOTHER BRANCH MODAL-->
    <b-modal
                    id="transfer-products-modal"
                    ref="modal"
                    hide-footer
                    title="Transfer products to another branch"
                    @hidden="resetTransferModal"
                  >
                      <div class="mb-2">
                        <label class="col-form-label" for="upload-file"
                          >Select branch</label
                        >
                        <model-list-select
                          :list="OtherBranches ? OtherBranches : []"
                          v-model="transfer.branch"
                          option-value="id"
                          option-text="name"
                          placeholder="Select Branch"
                          @input="resetBranchError()"
                        >
                        </model-list-select>
                        <div class="text-danger" v-if="branchError">
                          <em>{{ branchError }}</em>
                        </div>
                      </div>
                    <div class="modal-footer">
                      <button
                        class="btn btn-secondary"
                        @click="closeModal('transfer-products-modal')"
                        type="button"
                      >
                        Close
                      </button>
                      <button class="btn btn-primary" type="button" @click="TransferPdts('transfer-products-modal')">
                        Transfer
                        <b-spinner
                            v-if="is_uploading"
                            variant="light"
                            small
                            role="status"
                        ></b-spinner>
                      </button>
                    </div>
                  </b-modal>
    <!-- TRANSFER PDTS TO ANOTHER BRANCH MODAL END -->
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>   