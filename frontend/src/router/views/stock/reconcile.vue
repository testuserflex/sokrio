<script>
// import axios from "axios";

import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapGetters, mapActions, mapState} from "vuex";
import { ModelListSelect } from "vue-search-select";
/**
 * Advanced table component
 */
export default {
  page: {
    title: "Reconcile Stock",
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
      title: "Stock Taking",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Stock Take",
          active: true,
        },
      ],
      totalRows: 1,
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
          key: "instock",
          sortable: true,
          label: "QUANTITY",
        },
        {
          key: "stock.actual_quantity",
          sortable: true,
          label: "ACTUAL QUANTITY",
        },
        {
          key: "units",
          sortable: true,
          label: "UNIT QTY RECONCILE",
        }
      ],

      // Value from user input for adding position
      inputs: {
        id:"",
        added_qty: "",
        unit_id: "",
        type: "",
      },

      filterProducts:{
        product: '',
        category:''
      },

      stock_ar: [],
    };
  },
  computed: {
          // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("products", ["ViewProducts", "FilterStatus", "SaveStatus","Categories"]),
    ...mapState("products", ["branch_products"]),
    ...mapGetters("auth", ["MyUserPerm"]),
  },

  // watch: {
  //   branch_products(newValue) {
  //     if (newValue.data.length > 0) {
  //       this.assiginData();
  //     }
  //   },
  // },
  methods: {
    ...mapActions({
      FetchToReconcile: "products/viewProducts",
      ReconcileStock: "products/addQty",
      SaveReconcile: "products/SaveReconcile",
      FetchCategories: "products/fetchPCategories",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    // assiginData() {
    //   this.stock_ar = this.ViewProducts;
    // },

    FilterProducts(){
      this.FetchToReconcile(this.filterProducts);    
    },

    saveReconcile(event, unitobj, index) {
      this.inputs.id = unitobj.id;
      this.inputs.added_qty = event;
      this.inputs.type=0;
      this.inputs.unit_id = unitobj.unit.id;
      if (this.inputs.added_qty != "") {
        this.ReconcileStock(this.inputs).then(() => {
          this.FetchToReconcile(this.filterProducts);  
          this.$refs[index][0]?this.$refs[index][0].value = '':'';        
          this.$refs[index][1]?this.$refs[index][1].value = '':'';        
          this.$refs[index][2]?this.$refs[index][2].value = '':'';  
          this.$refs[index][3]?this.$refs[index][3].value = '':'';        
        });
      }
    },
    StockReconcile() {
      this.SaveReconcile(this.filterProducts);
    },
  },
  created() {
    this.FetchToReconcile(this.filterProducts);
    this.FetchCategories();
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
            <!-- <div class="row">
              <div class="col-12">
                <div>
                   
                </div>
              </div>
            </div> -->
            <div class="row py-3">              
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
              <div class="col-md-4"></div>
              <!-- Search -->
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
                :items="ViewProducts ? ViewProducts : []"
                :fields="fields"
                responsive="sm"
                :sort-desc.sync="sortDesc"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
              >
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>
                <template #cell(instock)="data">
                  {{ data.item.instock }} {{ data.item.baseunit.name }}
                </template>
                <template #cell(stock.actual_quantity)="data">
                  {{ data.item.stock.actual_quantity}} {{data.item.stock.actual_quantity?data.item.baseunit.name:''}}
                </template>
                <template #cell(units)="data">
                  <tr v-for="(itemunit,u) in data.item.units" :key="u">
                    <td>{{itemunit.unitsym}}</td>
                    <td>
                      <input
                       :ref="data.index"
                      type="number"
                      class="custom"
                      @blur="saveReconcile($event.target.value, itemunit, data.index)"
                      placeholder="Enter Actual Value"
                    />
                    </td>
                  </tr>
                  </template>
              </b-table>
            </div>
            <center>
                <Loader v-if="!ViewProducts" />
              </center>
            <div class="row">
              <div v-if="MyUserPerm.savereconcile_shop_stock == 1" class="col-12 mr-3">
                <button @click="StockReconcile()" class="btn btn-primary float-end bt">
                  <i class="fa fa-check"></i> Save Changes
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Saving"
                  ></b-spinner>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.custom {
  width: 150px;
  height: 30px;
  margin-right: 2px;
  box-sizing: border-box;
  padding: 12px 5px;
}
.bt {
  margin-right: 120px;
  margin-top: 40px;
}
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
