<script>

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
    title: "Short Expiry Report",
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
      title: "Short Expiry Report",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Short Expiries",
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
          key: "product.name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "category",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "days_field",
          sortable: true,
          label: "DAYS TO EXPIRE",
        },
        {
          key: "expiry",
          sortable: true,
          label: "EXPIRY DATE",
        },
      ],
      fields1: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "product.name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "category",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "days_field",
          sortable: true,
          label: "DAYS TO EXPIRE",
        },
        {
          key: "expiry",
          sortable: true,
          label: "EXPIRY DATE",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
      ],
    };
  },
  computed: {
    ...mapGetters("stock", ["ShortExpiries"]),
    ...mapGetters("auth", ["branchid","Branches"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.ShortExpiries ? this.ShortExpiries.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ShortExpiries ? this.ShortExpiries.length : 1;
  },

  methods: {
    ...mapActions({
      FetchShortExpiries: "stock/fetchShortExpiries",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
  created() {
    this.FetchShortExpiries();
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
              <div class="col-8">
                <h5 class="text-warning">Items that are about to expire</h5>
              </div>
              <div class="col-4">
                <div>
                  <router-link
                    :to="{
                      name: 'ExpiredStock',
                    }"
                  >
                    <b-button class="float-end" variant="primary"
                      ><i class="fa fa-ban"></i> Expired Stock</b-button
                    >
                  </router-link>
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
                :items="ShortExpiries ? ShortExpiries : []"
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
                <template #cell(days_field)="data"> {{ data.item.days }} days </template>
              </b-table>
            </div>
            <center>
                <Loader v-if="!ShortExpiries" />
              </center>
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
