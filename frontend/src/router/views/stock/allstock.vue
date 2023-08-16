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
    title: "All Stock",
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
      title: "All Stock",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "All Stock",
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
          key: "details",
          sortable: true,
          label: "",
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
          label: "PRODUCT",
        },
        {
          key: "buying_p",
          sortable: true,
          label: "BUYING PRICE",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
        {
          key: "details",
          sortable: true,
          label: "",
        },
      ],
      edit: {
        id: "",
        name: "",
        buying: "",

      },
      editData: {
        id: "",
      },

      filterProducts:{
        branch_id:0,
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
    ...mapGetters("products", ["SaveStatus","FilterStatus","ProductsData","Categories"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","branchid","Branches"]),
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
      if (newValue == true) {
        if (this.type == "success") {
          this.closeModal(this.modal_id);
          Swal.fire("Success!", this.message.msg, "success");
        } else {
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    }
  },
  methods: {
    ...mapActions({
      FetchProducts: "products/fetchProductsData",
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
    addCommas() {
      this.edit.buying = this.thousandSeperator(this.edit.buying);
    },
    showEditModal(data) {
      this.editData.id = data.item.id;
    },
    ShowEdit(data) {
      this.edit.id = data.id;
      this.edit.name = data.name;
      this.edit.buying = data.stock.buying_price?this.thousandSeperator(data.stock.buying_price):'';
    },
    editAmt(modalId){
      this.modal_id = modalId;
      if(this.edit.buying==""){

        return
      }
      this.EditBPrice(this.edit);
    },
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },


  },
  created() {
    this.FetchProducts(this.filterProducts);
    this.FetchGeneralSettings();
    this.FetchCategories();
    this.FetchBranches();
  },
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
                <h4 class="card-title">Stock Level In Different Units</h4>
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
            <div class="row mt-3">
              <div class="col-sm-12 col-md-4">
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

                <div class="col-md-4">
                  <b-button       
                  size="sm"
                  variant="primary"
                  @click="$router.go(-1)"
                  ><i class="fa fa-undo"></i> Back</b-button
                  >                  
                </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-4">
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
                <template #cell(buying_p)="data">
                  <span class="text-warning"
                        v-b-modal.editbuying-modal
                        @click="ShowEdit(data.item)">
                    {{ data.item.stock.buying_price?thousandSeperator(data.item.stock.buying_price):0 }}
                  </span>

                </template>
                <template #cell(details)="data">
                  <table v-if="GeneralSettings.enable_wholeselling == 1" class="table">
                    <thead>
                    <tr width="100%">
                      <th>IN STOCK</th>
                      <th>SELLING PRICE</th>
                      <th>RESERVE PRICE</th>
                      <th>WHOLESALE PRICE</th>
                      <th>WHOLESALE RESERVE PRICE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                      <td>{{ Math.floor(data.item.instock/det.base_qty)  }} {{ det.unit.name }}</td>
                      <td>{{ thousandSeperator(det.selling) }}</td>
                      <td>{{ thousandSeperator(det.reserve) }}</td>
                      <td>{{ thousandSeperator(det.wholesale_unitprice) }}</td>
                      <td>{{ thousandSeperator(det.wholesale_reserveprice) }}</td>
                    </tr>
                    </tbody>
                  </table>
                  <table v-if="GeneralSettings.enable_wholeselling == 0" class="table">
                    <thead>
                    <tr width="100%">
                      <th>IN STOCK</th>
                      <th>SELLING PRICE</th>
                      <th>RESERVE PRICE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                      <td>{{ Math.floor(data.item.instock/det.base_qty)  }} {{ det.unit.name }}</td>
                      <td>{{ thousandSeperator(det.selling) }}</td>
                      <td>{{ thousandSeperator(det.reserve) }}</td>
                    </tr>
                    </tbody>
                  </table>
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
    <!-- modal mark as expired starts -->
    <b-modal id="editbuying-modal" hide-footer>
      <template #modal-title>
        <h4 class="text-warning">Edit Buying Price of {{ edit.name }}</h4></template
      >
      <label>Buying Price</label>
      <div class="input-group">
        <input
            type="text"
            class="form-control"
            v-model="edit.buying"
            placeholder="Buying Price"
            @input="addCommas()"
        />
      </div>


      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="closeModal('editbuying-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="editAmt('editbuying-modal')">
          <i class="fa fa-minus"></i> Confirm
          <b-spinner
              v-if="SaveStatus"
              variant="light"
              small
              label="Submiting"
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
