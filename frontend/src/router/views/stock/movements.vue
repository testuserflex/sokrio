<script>
// import axios from "axios";

import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import { ModelListSelect } from "vue-search-select";
import Loader from "@/components/widgets/preloader";
import appConfig from "@/app.config";
import { mapGetters, mapActions, mapState } from "vuex";
import moment from "moment";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Stock Movement",
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
      title: "Stock Movement Report",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Stock Movement",
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
      fields: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "product",
          sortable: true,
          label: "PRODUCT",
        },
        {
          key: "from_quantity",
          sortable: true,
          label: "OLD QUANTITY",
        },
        {
          key: "qty_in",
          sortable: true,
          label: "QTY IN",
        },
        {
          key: "qty_out",
          sortable: true,
          label: "QTY OUT",
        },
        {
          key: "type_field",
          sortable: true,
          label: "TYPE",
        },
        {
          key: "user",
          sortable: true,
          label: "MADE BY",
        },
        {
          key: "date",
          sortable: true,
          label: "DATE",
        },
        {
          key: "date_recorded",
          sortable: true,
          label: "DATE/TIME",
        },
      ],
      id: atob(this.$route.params.id),
      data: {
        dto: moment(new Date()).format("YYYY-MM-DD"),
        dfrom: "",
        type: 10,
      },
      totals: {
        totalAmount: 0,
        totalBalance: 0,
      },
      filterTitle: "",
      filteredReport: [],
      types: [
        {name: "All Types",id: 10},
        {name: "Adding Product",id: 0},
        {name: "Sale",id: 1},
        {name: "Sale Return",id: 2},
        {name: "Purchase",id: 3},
        {name: "Purchase Return",id: 4},
        {name: "Spoilt Stock",id: 5},
        {name: "Expired",id: 6},
        {name: "Reconciled",id: 7},
        {name: "Stock Transfer",id: 8},
      ],
    };
  },
  computed: {
    ...mapState("stock", ["stock_movements"]),
    ...mapGetters("stock", ["StockMovement"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.filteredReport ? this.filteredReport.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  watch: {
    stock_movements(newValue) {
      if (newValue.data.length > 0) {
        this.assiginData();
      }
    },
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.StockMovement ? this.StockMovement.length : 1;
  },
  methods: {
    ...mapActions({
      FetchMovements: "stock/fetchStockMovements",
    }),
    // COMMA SEPERATORS
    thousandSeprator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
          .toString()
          .replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        return m;
      }
    },
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    assiginData() {
      this.filteredReport = this.StockMovement;
    },

    filterReport() {
      let date2 = this.data.dto;
      let date1 = this.data.dfrom;
      this.filteredReport = this.StockMovement.filter((element) => {
        if (this.data.type != "10" && this.data.dfrom == "" && this.data.dto != "") {

          return element.type == this.data.type && element.date <= date2;
        } else if (
          this.data.type == "10" &&
          this.data.dfrom != "" &&
          this.data.dto != ""
        ) {

          return element.date >= date1 && element.date <= date2;
        }
        else if (
          this.data.type == 10 &&
          this.data.dfrom == "" &&
          this.data.dto != ""
        ) {

          return element.date <= date2;
        } else if (
          this.data.type != "10" &&
          this.data.dfrom != "" &&
          this.data.dto != ""
        ) {

          return (
            element.type== this.data.type &&
            element.date >= date1 &&
            element.date <= date2
          );
        }
      });
    },
  },
  created() {
    this.FetchMovements(atob(this.$route.params.id));
    this.assiginData();
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
              <div align="right">
                  <button @click="$router.go(-1)" class="btn btn-primary btn-sm mb-2" >
                <i class="fas fa-arrow-circle-left"></i> Go Back
            </button>
              </div>
              
            <div class="row">
              <div v-if="filterTitle">
                <h4>
                  <center>{{ filterTitle }}</center>
                </h4>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>Movement Type</label>
                  <model-list-select
                    :list="types ? types : []"
                    v-model="data.type"
                    option-value="id"
                    option-text="name"
                    class="mb-3"
                    placeholder="Select Type"
                    @input="filterReport()"
                  >
                  </model-list-select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>From</label>
                  <input
                    @change="filterReport()"
                    type="date"
                    v-model="data.dfrom"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>To</label>
                  <input
                    @change="filterReport()"
                    type="date"
                    v-model="data.dto"
                    class="form-control"
                  />
                </div>
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
                <div id="tickets-table_filter" class="dataTables_filter text-md-end">
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
                foot-clone
                :items="filteredReport ? filteredReport : []"
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
                <template #cell(type_field)="data">
                  <span v-if="data.item.type==1">Sale</span>
                  <span v-if="data.item.type==0">Product</span>
                  <span v-if="data.item.type==2">Sale Return</span>
                  <span v-if="data.item.type==3">Purchase</span>
                  <span v-if="data.item.type==4">Purchase Return</span>
                  <span v-if="data.item.type==7">Reconciliation</span>
                  <span v-if="data.item.type==8">Stock Transfer</span>
                  <span v-if="data.item.type==5">Spoilt Stock</span>
                  <span v-if="data.item.type==6">Expired</span>
                </template>
                <template #cell(amount_field)="data">
                  {{ thousandSeprator(data.item.amount) }}
                </template>
                <template #cell(mode_field)="data">
                  {{ data.item.mode.name }} ({{ data.item.mode.type_name }}-{{ data.item.mode.account_number }})
                </template>
                <template #foot(index)="">
                  <span class="text-danger"></span>
                </template>
                <template #foot(amount_field)="">
                  {{ thousandSeprator(totals.totalAmount) }}
                </template>
                <template #foot(type_field)="">
                     <span class="text-danger"></span>
                </template>
                <template #foot(payee_field)="">
                  <span class="text-danger"></span>
                </template>
                <template #foot(addedBy)="">
                  <span class="text-danger"></span>
                </template>
                <template #foot(dateCreated)="">
                  <span class="text-danger"></span>
                </template>
                <template #foot(type)="">
                  <span class="text-danger"></span>
                </template>
              </b-table>
            </div>
            <Loader v-if="!StockMovement"/>
            <div class="row">
              <div class="col">
                <div class="dataTables_paginate paging_simple_numbers float-end">
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
