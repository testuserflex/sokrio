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

      editstock: {
        id: "",
        name: "",
        unit_name: "",
        unit_id: "",
        selling_price: "",
        reserve_price: "",
        wholesale_unitprice: "",
        wholesale_reserveprice: "",

      },

      SellingError:'',

      filterProducts:{
        product: '',
        category:'',
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
    ...mapGetters("products", ["FilterStatus","ViewProducts","Categories"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","branchid","Branches"]),
    ...mapGetters("setting", ["GeneralSettings"]),
    ...mapGetters("stock", ["SaveStatus"]),

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
      FetchProducts: "products/viewProducts",
      EditBPrice: "stock/editBPrice",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      FetchCategories: "products/fetchPCategories",
      FetchBranches: "auth/fetchBranches",
      Editstock:"stock/editStock"

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
      this.edit.buying = this.edit.buying?this.thousandSeperator(this.edit.buying):'';
      this.editstock.selling_price = this.editstock.selling_price?this.thousandSeperator(this.editstock.selling_price):'';
      this.editstock.reserve_price = this.editstock.reserve_price?this.thousandSeperator(this.editstock.reserve_price):'';
      this.editstock.wholesale_unitprice = this.editstock.wholesale_unitprice?this.thousandSeperator(this.editstock.wholesale_unitprice):'';
      this.editstock.wholesale_reserveprice = this.editstock.wholesale_reserveprice?this.thousandSeperator(this.editstock.wholesale_reserveprice):'';
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

    showEditStockModal(data){
        this.editstock.id = data.id,
        this.editstock.name = data.product,
        this.editstock.unit_name = data.unitsym,
        this.editstock.selling_price = data.selling,
        this.editstock.reserve_price = data.reserve,
        this.editstock.wholesale_unitprice = data.wholesale_unitprice,
        this.editstock.wholesale_reserveprice = data.wholesale_reserveprice
    },

    editStock(modalId){
      if(this.editstock.selling_price == ''){
        this.SellingError = "Selling Price field is required";
        return;
      }else{
        this.SellingError = '';
      }
      this.Editstock(this.editstock);
      this.closeModal(modalId)
    }

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
            <div class="row py-3">
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
                      <th v-if="GeneralSettings.allow_reserve_price == 1">RESERVE PRICE</th>
                      <th>WHOLESALE PRICE</th>
                      <th v-if="GeneralSettings.allow_reserve_price == 1">WHOLESALE RESERVE PRICE</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                      <td>{{ Math.floor(data.item.instock/det.base_qty)  }} {{ det.unit.name }}</td>
                      <td>{{ det.selling?(det.selling).toLocaleString():0 }}</td>
                      <td v-if="GeneralSettings.allow_reserve_price == 1">{{ det.reserve?(det.reserve).toLocaleString():0 }}</td>
                      <td>{{ det.wholesale_unitprice?(det.wholesale_unitprice).toLocaleString():0 }}</td>
                      <td v-if="GeneralSettings.allow_reserve_price == 1">{{ det.wholesale_reserveprice?(det.wholesale_reserveprice).toLocaleString():0 }}</td>
                      <td>
                        <b-button-group>
                          <b-button
                            v-if="MyUserPerm.edit_products == 1"
                            size="sm"
                            variant="primary"
                            v-b-modal.editstock-modal
                            @click="showEditStockModal(det)"
                            ><i class="fa fa-edit"></i> Edit
                          </b-button>
                        </b-button-group>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <table v-if="GeneralSettings.enable_wholeselling == 0" class="table">
                    <thead>
                    <tr width="100%">
                      <th>IN STOCK</th>
                      <th>SELLING PRICE</th>
                      <th v-if="GeneralSettings.allow_reserve_price == 1">RESERVE PRICE</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(det,d) in data.item.units" :key="d" width="100%">
                      <td>{{ Math.floor(data.item.instock/det.base_qty)  }} {{ det.unit.name }}</td>
                      <td>{{ det.selling?(det.selling).toLocaleString():0 }}</td>
                      <td v-if="GeneralSettings.allow_reserve_price == 1">{{ det.reserve?(det.reserve).toLocaleString():0 }}</td>
                      <td>
                        <b-button-group>
                          <b-button
                            v-if="MyUserPerm.edit_products == 1"
                            size="sm"
                            variant="primary"
                            v-b-modal.editstock-modal
                            @click="showEditStockModal(det)"
                            ><i class="fa fa-edit"></i> Edit
                          </b-button>
                        </b-button-group>
                      </td>
                    </tr>
                    </tbody>
                  </table>
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
                  <router-link :to="'/stock/allstock'">View all</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Edit buying price modal starts -->
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


    <!-- Edit stock details modal starts -->
    <b-modal id="editstock-modal" hide-footer>
      <template #modal-title>
        <h4 class="text-warning">Edit stock details for {{ editstock.name }}</h4></template
      >

      <div class="form-group">
        <label>Selling Price</label>
        <div class="input-group">
          <input
              type="text"
              class="form-control"
              v-model="editstock.selling_price"
              placeholder="Selling Price"
              @input="addCommas()"
          />
        </div>
      </div>
      <div class="form-group" v-if="GeneralSettings.allow_reserve_price == 1">
        <label>Reserve Price</label>
        <div class="input-group">
          <input
              type="text"
              class="form-control"
              v-model="editstock.reserve_price"
              placeholder="Reserve Price"
              @input="addCommas()"
          />
        </div>
      </div>
      <div class="form-group" v-if="GeneralSettings.enable_wholeselling == 1">
        <label>Wholesale Unit Price</label>
        <div class="input-group">
          <input
              type="text"
              class="form-control"
              v-model="editstock.wholesale_unitprice"
              placeholder="Wholesale Price"
              @input="addCommas()"
          />
        </div>
      </div>
      <div class="form-group" v-if="GeneralSettings.enable_wholeselling == 1 && GeneralSettings.allow_reserve_price == 1">
        <label>Wholesale Reserve Price</label>
        <div class="input-group">
          <input
              type="text"
              class="form-control"
              v-model="editstock.wholesale_reserveprice"
              placeholder="Wholesale Reserve Price"
              @input="addCommas()"
          />
        </div>
      </div>

      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="closeModal('editstock-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="editStock('editstock-modal')">
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
