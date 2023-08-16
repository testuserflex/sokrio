<script>
// import axios from "axios";

import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import Loader from "@/components/widgets/preloader";
import appConfig from "@/app.config";
import { mapGetters, mapActions } from "vuex";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Reconciliation Reports",
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
      title: "Stock Reconciliation Reports",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Reconciliation Reports",
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
          key: "branch",
          sortable: true,
          label: "BRANCH",
        },
        {
          key: "date",
          sortable: true,
          label: "Date Performed",
        },
        {
          key: "user",
          sortable: true,
          label: "Performed By",
        },
        {
          key: "type",
          sortable: true,
          label: "Category",
        },
        {
          key: "num_field",
          sortable: true,
          label: "Products Affected",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
    };
  },
  computed: {
    ...mapGetters("stock", ["ReconciliationReport"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.ReconciliationReport ? this.ReconciliationReport.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ReconciliationReport ? this.ReconciliationReport.length : 1;
  },

  methods: {
    ...mapActions({
      FetchReports: "stock/fetchReconciliationReport",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    redirect(value) {
      this.$router.push(
        `/reconciliation/details/${btoa(value)}`
      );
    },
  },
  created() {
    this.FetchReports();
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
                :items="ReconciliationReport ? ReconciliationReport : []"
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
                <template #cell(num_field)="data">
                  <strong> {{ data.item.products }}</strong> products
                </template>

                <template #cell(actions)="data">
                  <button class="btn btn-primary btn-sm"  @click="redirect(data.item.id)">
                    <i class="fa fa-eye text-light"></i> Details
                  </button>
                </template>
              </b-table>
            </div>
            <Loader v-if="!ReconciliationReport" />
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
