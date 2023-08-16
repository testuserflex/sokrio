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
    title: "Expense Report",
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
      title: "Expense Report",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Expense Report",
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
          key: "category.name",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "amount_field",
          sortable: true,
          label: "AMOUNT",
        },
        {
          key: "mode_field",
          sortable: true,
          label: "MODE",
        },
        {
          key: "description",
          sortable: true,
          label: "DESCRIPTION",
        },
        {
          key: "user.names",
          sortable: true,
          label: "MADE BY",
        },
        {
          key: "date",
          sortable: true,
          label: "DATE",
        },
        {
          key: "dateAdded",
          sortable: true,
          label: "DATE/TIME",
        },
      ],
      fields1: [
        {
          key: "index",
          sortable: true,
          label: "S/N",
        },
        {
          key: "category.name",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "amount_field",
          sortable: true,
          label: "AMOUNT",
        },
        {
          key: "mode_field",
          sortable: true,
          label: "MODE",
        },
        {
          key: "description",
          sortable: true,
          label: "DESCRIPTION",
        },
        {
          key: "user.names",
          sortable: true,
          label: "MADE BY",
        },
        {
          key: "date",
          sortable: true,
          label: "DATE",
        },
        {
          key: "dateAdded",
          sortable: true,
          label: "DATE/TIME",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
      ],
      data: {
        dto: moment(new Date()).format("YYYY-MM-DD"),
        dfrom: "",
        category: "",
      },
      totals: {
        totalAmount: 0,
        totalBalance: 0,
      },
      filterTitle: "",
      filteredReport: [],
    };
  },
  computed: {
    ...mapGetters("expenses", ["Expenses", "ExpenseCategories"]),
    ...mapState("expenses", ["expenses"]),
    ...mapGetters("auth", ["branchid","Branches"]),


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
    expenses(newValue) {
      if (newValue.data.length > 0) {
        this.assiginData();
      }
    },
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Expenses ? this.Expenses.length : 1;
  },
  methods: {
    ...mapActions({
      FetchExpenses: "expenses/fetchExpenses",
      FetchExpenseCategories: "expenses/fetchExpenseCategories",
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
      this.filteredReport = this.Expenses;

      let total = 0;
      this.Expenses?this.Expenses.forEach((value) => {
        let amt = parseInt(value.amount);
        total = total + amt;
      }):[];
      this.totals.totalAmount = total;
    },

    filterReport() {
      this.totals.totalAmount = 0;
      let date2 = this.data.dto;
      let date1 = this.data.dfrom;
      let total = 0;
      this.filteredReport = this.Expenses.filter((element) => {
        if (this.data.category != "" && this.data.dfrom == "" && this.data.dto != "") {
          if (element.category.id == this.data.category && element.date <= date2) {
            let amt = parseInt(element.amount);
            total = total + amt;
            this.totals.totalAmount = total;
          }
          this.ExpenseCategories.filter((element) => {
            if (element.id == this.data.category) {
              let expcat = element.name;
              this.filterTitle =
                "Expense Report For " + expcat + " Up To " + this.data.dto;
            }
          });

          return element.category.id == this.data.category && element.date <= date2;
        } else if (
          this.data.category == "" &&
          this.data.dfrom != "" &&
          this.data.dto != ""
        ) {
          this.filterTitle =
            "Expense Report From " + this.data.dfrom + " To " + this.data.dto;
          if (element.date >= date1 && element.date <= date2) {
            let amt = parseInt(element.amount);
            total = total + amt;
            this.totals.totalAmount = total;
          }

          return element.date >= date1 && element.date <= date2;
        } else if (
          this.data.category != "" &&
          this.data.dfrom != "" &&
          this.data.dto != ""
        ) {
          if (
            element.category.id == this.data.category &&
            element.date >= date1 &&
            element.date <= date2
          ) {
            let amt = parseInt(element.amount);
            total = total + amt;
            this.totals.totalAmount = total;
          }

          this.ExpenseCategories.filter((element) => {
            if (element.id == this.data.category) {
              let expcatx = element.name;
              this.filterTitle =
                "Expense Report For " +
                expcatx +
                " From " +
                this.data.dfrom +
                " To " +
                this.data.dto;
            }
          });

          return (
            element.category.id == this.data.category &&
            element.date >= date1 &&
            element.date <= date2
          );
        }
      });
    },
  },
  created() {
    this.FetchExpenses();
    this.FetchExpenseCategories();
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
            <div class="row">
              <div v-if="filterTitle">
                <h4>
                  <center>{{ filterTitle }}</center>
                </h4>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>Expense Category</label>
                  <model-list-select
                    :list="ExpenseCategories ? ExpenseCategories : []"
                    v-model="data.category"
                    option-value="id"
                    option-text="name"
                    class="mb-3"
                    placeholder="Select Expense Category"
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
                <template #cell(balance_field)="data">
                  {{ thousandSeprator(data.item.balance) }}
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
                <template #foot(balance_field)="">
                  {{ thousandSeprator(totals.totalBalance) }}
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
                <template #foot(branch)="">
                  <span class="text-danger"></span>
                </template>
                <template #foot(category)="">
                  <span class="text-danger"></span>
                </template>
              </b-table>
            </div>
            <Loader v-if="!Expenses"/>
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
