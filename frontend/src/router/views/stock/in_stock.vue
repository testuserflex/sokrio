<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions } from "vuex";
import moment from "moment";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Instock Products",
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
      current_date: moment(new Date()).format("YYYY-MM-DD"),
      title: "Instock Products",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Instock Products",
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
          key: "product_category",
          sortable: true,
          label: "Category",
        },
        {
          key: "qty_field",
          sortable: true,
          label: "IN STOCK",
        }
      ],

      json_fields: {
        PRODUCT: "name",
        CATEGROY: "product_category",
        QUANTITY: "instock",
        UNITS: "baseunit.symbol",
      },
      json_meta: [
        [
          {
            key: "charset",
            value: "utf-8",
          },
        ],
      ],

    };
  },
  computed: {
    ...mapGetters("products", ["InStock"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.InStock ? this.InStock.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.InStock ? this.InStock.length : 1;
  },
  methods: {
    ...mapActions({
        FetchInStock: "products/fetchInStock",
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
  },
  created() {
    this.FetchInStock();
  },
};
</script>

<template>
  <Layout>
    <h4 class="text-center text-primary">{{business}} - {{branch}}</h4>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-3">
                      <h4 class="card-title">Products in stock</h4>
                  </div>
                  <div class="col-md-7"></div>
                  <div class="col-md-2">
                    <download-excel
                      class="btn btn-info"
                      :data="InStock"
                      :fields="json_fields"
                      :worksheet="'IN STOCK ITEMS ' + current_date"
                      type="csv"
                      :name="'Instock_items ' + current_date + '.xls'"
                    >
                      Export <i class="fa fa-download"></i>
                    </download-excel>
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
                :items="InStock?InStock:[]"
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
                <template #cell(qty_field)="data">
                  {{ data.item.instock }} {{ data.item.baseunit.symbol }}
                </template>
              </b-table>
            </div>
            <Loader  v-if="!InStock"/>
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
