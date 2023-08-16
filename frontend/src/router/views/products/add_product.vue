<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
// import { ModelListSelect } from "vue-search-select";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Add Products",
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
    // ModelListSelect,
  },
  data() {
    return {
      title: "Add Products",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "add Products",
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
      is_uploading: false,
      existingProduct: [],
      nameError: "",
      unitError: "",
      categoryError: "",
      productError: "",
      sellingPriceError: "",
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

    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("products", ["SaveStatus", "BProducts", "Units", "Categories","Products"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch"]),
    ...mapGetters("general", ["Branches"]),
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
      FetchBProducts: "products/fetchBProducts",
      FetchProducts: "products/fetchProducts",
      FetchUnits: "products/fetchUnits",
      AddProduct: "products/addProduct",
      FetchCategories: "products/fetchPCategories",
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
        this.existingProduct = this.Products.filter((product) => {
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
    this.FetchProducts();
    this.FetchUnits();
    this.FetchCategories();
    this.FetchGeneralSettings();
  },
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <div class="card">
        <div class="card-body">
            <div class="row px-5">
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
                <div class="mb-3" align="right">
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

            </div>
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
         <div class="col-md-12">
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
         <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1">
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
         <div class="col-md-12">
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
         <div class="col-md-12" v-if="GeneralSettings.enable_wholeselling == 1">
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
        </div>
    </div>
     
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>   
