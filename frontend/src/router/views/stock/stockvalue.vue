<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import {mapGetters, mapActions, mapState} from "vuex"
import Swal from "sweetalert2";
import { ModelListSelect } from "vue-search-select";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Stock",
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
      title: "Stock",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Stock",
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
          key: "buying_p",
          sortable: true,
          label: "BUYING PRICE",
        },
        {
          key: "selling_p",
          sortable: true,
          label: 'SELLING PRICE',
        },
      ],

      totals: {
        totalInSelling: 0,
        totalInBuying: 0,
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
    ...mapGetters("products", ["SaveStatus","FilterStatus","Products","Categories"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","branchid","Branches"]),
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
      if (newValue == true) {
        if (this.type == "success") {
          this.closeModal(this.modal_id);
          Swal.fire("Success!", this.message.msg, "success");
        } else {
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
  },
  methods: {
    ...mapActions({
      FetchProducts: "products/fetchProducts",
      EditBPrice: "stock/editBPrice",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      FetchCategories: "products/fetchPCategories",
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
    // COMMA SEPERATORS
   
    FilterProducts(){
      this.FetchProducts(this.filterProducts);    
    },

    thousandSeperator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        let xx = m.toString().split(".");
        let x1=xx[0]
            .toString()
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if (!xx[1]){
            if(m.toString().charAt(m.length - 1) == '.'){
            return x1+".";
             
            }else{
                return x1;

            }
        }else{
          return x1+"."+xx[1];
        }
      } else {
        return m;
      }
    },
    
  },
  created() {
    this.FetchProducts(this.filterProducts);
    this.FetchGeneralSettings();
    this.FetchCategories();
    this.FetchBranches();
  },

  updated(){
    let total_selling = 0;
    let total_buying = 0;
    // let total_paid = 0;
    if(this.Products){
    this.Products.forEach((item)=>{
        total_buying = total_buying+parseInt(item.stock.buying_price*item.instock);
        total_selling = total_selling+parseInt(item.selling*item.instock);
    });
    this.totals.totalInSelling = parseInt(total_selling);
    this.totals.totalInBuying = parseInt(total_buying);
    }
  }
};
</script>

<template>
  <Layout>
    <div class="row py-3 text-center">
      <h4><span class="text-primary">{{business}}</span><span v-if="GeneralSettings.show_branchname == 1 && branch"> - {{branch}}</span></h4>
    </div>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-9">
                <h4 class="card-title">Stock Level with total value</h4>
              </div>

            </div>
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
            <div class="row py-3">
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
                <template #cell(buying_p)="data">
                    {{ data.item.stock.buying_price?thousandSeperator(data.item.stock.buying_price):0 }}
                </template>

                <template #cell(selling_p)="data">
                    {{ data.item.selling?thousandSeperator(data.item.selling):0 }}
                </template>
                <template #cell(details)="data">
                  <table class="table">
                    <thead>
                    <tr width="100%">
                      <th>IN STOCK</th>
                      <th>SELLING PRICE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                      <td>{{ Math.floor(data.item.instock/det.base_qty)  }} {{ det.unit.name }}</td>
                      <td>{{ thousandSeperator(det.selling) }}</td>
                    </tr>
                    </tbody>
                  </table>
                </template>
              </b-table>
              <div class="row">
                  <div class="col-md-6 text-center">
                    <h5>Total With Selling Price: <span class="text-danger">{{thousandSeperator(totals.totalInSelling)}}</span></h5>
                  </div>
                  <div class="col-md-6 text-center">
                    <h5>Total With Buying Price: <span class="text-danger">{{thousandSeperator(totals.totalInBuying)}}</span></h5>
                  </div>
                </div>
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
    


  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
