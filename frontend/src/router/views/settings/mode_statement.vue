<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions } from "vuex"
import axios from 'axios';
import moment from "moment";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Statement",
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
      title: "Payment Methods",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Payment Methods",
          active: true,
        },
      ],
      filterPayments:{
        datefrom: moment(new Date()).format("YYYY-MM-DD"),
        dateto: moment(new Date()).format("YYYY-MM-DD"),
        id: atob(this.$route.params.id),
      },
      totalRows: 1,
      totals:{
        totalin: 0,
        totalout: 0,
      },
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
          key: "amountin",
          sortable: true,
          label: "AMOUNT IN",
        },
        {
          key: "amountout",
          sortable: true,
          label: "AMOUNT OUT",
        },
        {
          key: "date",
          sortable: true,
          label: "DATE",
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
          key: "cat",
          sortable: true,
          label: "CATEGORY",
        },
      ],
      modeName: "",
    };
  },
  computed: {
    ...mapGetters("setting", ["filterStatus","Statement", "SaveStatus"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Statement ? this.Statement.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Statement ? this.Statement.length : 1;
  },
  watch: {
    Statement(newValue) {
      if (newValue){
        this.assignMode();
      }
    }
  },
  methods: {
    ...mapActions({
      FetchStatement: "setting/fetchStatement",
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
    assignMode(){
      if(this.Statement){
        this.modeName = this.Statement?this.Statement[0].modex.name:"---";
      }
    },
    SalesPaymentsData() {
      this.FetchStatement(this.filterPayments);
    },

    
    Excel_export() {
      axios
        .post("export_account_statement", this.filterPayments,{
          responseType: "arraybuffer",
        })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", "account_statement.xlsx");
          document.body.appendChild(fileLink);
          fileLink.click();
          this.spinner = false;
        })
        .catch(() => {
        });
    },
  },
  updated() {
    let totalin = 0;
    let totalout = 0;
      // let total_paid = 0;
      if(this.Statement){
        this.Statement.forEach((item)=>{
          totalin = totalin+parseInt(item.amount_in);
          totalout = totalout+parseInt(item.amount_out);
        });
        this.totals.totalin = parseInt(totalin);
        this.totals.totalout = parseInt(totalout);
      }
  },
  created() {
    this.SalesPaymentsData(this.filterPayments);
    this.assignMode();
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
                  <div class="col-md-9">
                      <h4 class="card-title">Statement For {{ modeName?modeName:"Payment Method " }} from {{filterPayments.datefrom}} to {{filterPayments.dateto}}</h4>
                  </div>
                  <div class="col-md-3" align="right">
                      <b-button @click="$router.go(-1)" variant="primary"><i class="fa fa-undo"></i> Back</b-button>
                  </div>
              </div>           
                <div class="row">
                  <div class="col-md-2 mt-4">
                    <b-button variant="info" @click="Excel_export()"
                      ><i class="fa fa-download"></i>Export</b-button
                    >
                  </div>
                  <div class="col-md-4 form-group mb-2">
                    <label>FROM</label>
                    <input
                      type="date"
                      v-model="filterPayments.datefrom"
                      class="form-control"
                    />
                  </div>
                  <div class="col-md-4 form-group">
                    <label>TO</label>
                    <input
                      v-model="filterPayments.dateto"
                      type="date"
                      class="form-control"
                    />
                  </div>
                  <div class="col-md-2 mt-4">
                  <button class="btn btn-primary" type="button" @click="SalesPaymentsData()">
                    <span class="fa fa-filter"></span> Filter
                    <b-spinner
                      v-show="filterStatus"
                      variant="light"
                      small
                      role="status"
                    ></b-spinner>
                  </button>
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
                :items="Statement?Statement:[]"
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
                <template #cell(amountin)="data">
                  {{ thousandSeperator(data.item?data.item.amount_in:0) }}
                </template>
                <template #cell(amountout)="data">
                  {{ thousandSeperator(data.item?data.item.amount_out:0) }}
                </template>
                <template #cell(mode_field)="data">
                  {{ data.item?data.item.modex.name:"---" }} - {{ data.item?data.item.modex.type_name:"---" }}({{ data.item?data.item.modex.account_number:"---" }})
                </template>
                <template #cell(cat)="data">
                  <span v-if="data.item.category==1">Sale</span>
                  <span v-else-if="data.item.category==2">Expense</span>
                  <span v-else-if="data.item.category==3">Purchase</span>
                  <span v-else-if="data.item.category==4">Sale Return</span>
                  <span v-else-if="data.item.category==5">Purchase Return</span>
                  <span v-else-if="data.item.category==6">CashOut</span>
                  <span v-else-if="data.item.category==7">CashIn</span>
                  <span v-else-if="data.item.category==8">Money Transfer</span>
                  <span v-else-if="data.item.category==9">Customer Deposit</span>
                  <span v-else-if="data.item.category==10">Opening Balance</span>
                  <span v-else-if="data.item.category==11">LPO Payment</span>
                </template>
              </b-table>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                  <h5>Total Amount in: <span class="text-danger">{{thousandSeperator(totals.totalin)}}</span></h5>
                </div>
                <div class="col-md-4">
                  <h5>Total Amount out: <span class="text-danger">{{thousandSeperator(totals.totalout)}}</span></h5>
                </div>
              </div>
            </div>
            <Loader v-if="!Statement" />
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
.btn-xs {
  padding: .25rem .4rem;
  font-size: .875rem;
  line-height: .5;
  border-radius: .2rem;
}
</style>   
