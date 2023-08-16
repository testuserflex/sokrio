<script>
    import Layout from "../../layouts/main";
    import PageHeader from "@/components/page-header";
    import appConfig from "@/app.config";
    import Loader from "@/components/widgets/preloader";
    import { mapGetters,mapActions,mapState } from "vuex";
    import moment from "moment";
    import { ModelListSelect } from "vue-search-select";
    
    /**
     * Advanced table component
     */
    export default {
      page: {
        title: "Credit Payments",
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
        ModelListSelect,
      },
      data() {
        return {
          total_amount: 0,
          total_paid: 0,
          filterPayments:{
            customer: '',
            datefrom: moment(new Date()).format("YYYY-MM-DD"),
            dateto: moment(new Date()).format("YYYY-MM-DD"),
          },
          currentdate: moment(new Date()).format("YYYY-MM-DD"),
          title: "Credit Payments",
          items: [
            {
              text: "Home",
              href: "/",
            },
            {
              text: "Credit Payments",
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
              key: "customer",
              sortable: true,
              label: 'CUSTOMER',
            },
            {
              key: "amount",
              sortable: true,
              label: 'AMOUNT',
            },
            {
              key: "date",
              sortable: true,
              label: 'DATE',
            },
            {
              key: "mode",
              sortable: true,
              label: 'PAYMENT MODE',
            },
            {
              key: "receipt",
              sortable: true,
              label: "RECEIPT_NO",
            },
            {
              key: "recordedby",
              sortable: true,
              label: "RECEIVED_BY",
            },
            {
              key: "recordedon",
              sortable: true,
              label: "RECORDED_ON",
            }
          ],
        };
      },
      computed: {
             // MAP STATE
        ...mapState("notification", [
          "type",
          "message",
          "show",
          ]),
        ...mapGetters("sales", ["filterStatus","SaleCreditPayments"]),
        ...mapGetters("customers", ["Customers"]),
    
        /**
         * Total no. of records
         */
    
        rows() {
          return this.SaleCreditPayments ? this.SaleCreditPayments.length : 1;
        },
    
        /**
         * Todo list of records
         */
      },
      mounted() {
        // Set the initial number of items
        this.totalRows = this.SaleCreditPayments ? this.SaleCreditPayments.length : 1;
      },  
      methods: {
        ...mapActions({
          FetchPayments: "sales/fetchCreditPayments",
          FetchCustomers: "customers/fetchCustomers",
    
        }),
    
        // COMMA SEPERATORS
        thousandSeperator(m) {
          if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
            return m
                .toString()
                .replace(/[^0-9.]+/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          } else {
            return m;
          }
        },
    
        SalesPaymentsData() {
          this.FetchPayments(this.filterPayments);
        },
        /**
         * Search the table data with search input
         */
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },        
      },
      updated() {
        let total_amount = 0;
          // let total_paid = 0;
          if(this.SaleCreditPayments){
            this.SaleCreditPayments.forEach((item)=>{
              total_amount = total_amount+parseInt(item.amount);
            });
            this.total_amount = parseInt(total_amount);
          }
      },
      // watch: {
      //   SaleCreditPayments(newValue){
      //     if(newValue == true){
      //       this.SalesPaymentsData();
      //     }
      //   }
      // },
      created() {
        this.FetchPayments(this.filterPayments);
        this.FetchCustomers();
        this.SalesPaymentsData();
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
                    <h3 class="card-title mb-2 text-center">Credit Payments Report for {{currentdate}}</h3>
                  </div>
                </div>            
                <div class="row">
                  <div class="col-md-3 form-group mb-2">
                    <label>FROM</label>
                    <input
                      type="date"
                      v-model="filterPayments.datefrom"
                      class="form-control"
                      @change="SalesPaymentsData()"
                    />
                  </div>
                  <div class="col-md-3 form-group">
                    <label>TO</label>
                    <input
                      v-model="filterPayments.dateto"
                      type="date"
                      class="form-control"
                      @change="SalesPaymentsData()"
                    />
                  </div>
                  <div class="col-md-3">
                    <label>CUSTOMERS</label>
                    
                    <model-list-select
                      :list="Customers ? Customers : []"
                      v-model="filterPayments.customer"
                      option-value="id"
                      option-text="name"
                      placeholder="Select Customer"
                      @input="SalesPaymentsData()"
                    >
                    </model-list-select>
                  </div>
                  <div class="col-md-3 mt-4">
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
                <div class="row mt-2">
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
                      :items="SaleCreditPayments?SaleCreditPayments:[]"
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
                    <template #cell(amount)="data">
                      {{(data.item.amount).toLocaleString() }}
                    </template>           
                  </b-table>
                  <div class="row">
                      <div class="col-md-4"></div>
                      <div class="col-md-4">
                        <h5>Total Amount: <span class="text-danger">{{(total_amount).toLocaleString()}}</span></h5>
                      </div>
                      <!-- <div class="col-md-4">
                        <h5>Total Paid: <span class="text-danger">{{thousandSeperator(total_paid)}}</span></h5>
                      </div> -->
                    </div>
                </div>
                <Loader  v-if="!SaleCreditPayments"/>
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
    