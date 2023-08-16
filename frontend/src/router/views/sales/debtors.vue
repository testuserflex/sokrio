<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import { ModelListSelect } from "vue-search-select";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions,mapState } from "vuex"
import Swal from "sweetalert2";
import moment from "moment";


/**
 * Advanced table component
 */
export default {
  page: {
    title: "Debtors",
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
      title: "Credit Sales",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Debtors",
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
          key: "cust",
          sortable: true,
          label: "CUSTOMER",
        },
        {
          key: "details",
          sortable: true,
          label: "PRODUCTS",
        },
        {
          key: "tt_amt",
          sortable: true,
          label: "TOTAL",
        },
        {
          key: "amt_paid",
          sortable: true,
          label: "PAID",
        },
        {
          key: "discount_field",
          sortable: true,
          label: "DISCOUNT",
        },
        {
          key: "balance",
          sortable: true,
          label: "BALANCE",
        },        
        {
          key: "receipt",
          sortable: true,
          label: "RECEIPT",
        },
        {
          key: "date",
          sortable: true,
          label: "DATE",
        },
        {
          key: "actions",
          sortable: false,
          label: "ACTION",
        },
      ],

      
      clear: {
          id: "",
          date: moment(new Date()).format("YYYY-MM-DD"),
          paid: "",
          mode: "",
          totals: "",
      },

      data: {
        dateto: moment(new Date()).format("YYYY-MM-DD"),
        datefrom: moment(new Date()).format("YYYY-MM-DD"),
        customer: "",
        branch_id:0
      },
      amountError: "",
      modeError: "",

      SaveStatus: this.SaveStatus,

    };
  },
  computed: {
         // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
      ]),
    ...mapGetters("saledetails", ["SaveStatus","Debtors","filterStatus"]),
    ...mapGetters("setting", ["PaymentOptions","GeneralSettings","ReceiptSettings"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","Branches","branchid"]),
    ...mapGetters("customers", ["Customers"]),
    ...mapState("saledetails", ["saleId"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Debtors ? this.Debtors.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Debtors ? this.Debtors.length : 1;
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
    saleId(newValue) {
      if (newValue && this.ReceiptSettings.print_receipt_after_sale == 1) {
        
        if(this.ReceiptSettings.receipt_type == 1){
          window.open(
            `https://${window.location.host}/receipts/salesreceipt.php?id=${btoa(newValue)}`
          );
        }else if(this.ReceiptSettings.receipt_type == 2){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv2.php?id=${btoa(newValue)}`
          );
        }else if(this.ReceiptSettings.receipt_type == 3){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv3.php?id=${btoa(newValue)}`
          );
        }
        else if(this.ReceiptSettings.receipt_type == 4){
          window.open(
            `https://${window.location.host}/receipts/salesreceiptv4.php?id=${btoa(newValue)}`
          );
        }else{
          window.open(
            `https://${window.location.host}/receipts/salesreceipt.php?id=${btoa(newValue)}`
          );
        }      
      }
    },
    PaymentOptions(newValue) {
      if (newValue) {
        this.setDefaultMode();
      }
    },
    
  },
  methods: {
    ...mapActions({
      FetchDebtors: "saledetails/fetchDebtors",
      ClearBalance: "saledetails/ClearDebt",
      ClearBalancePrint: "saledetails/ClearDebtPrint",
      FetchModes: "setting/fetchPaymentOptions",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      FetchReceiptSettings: "setting/fetchReceiptSettings",
      FetchCustomers: "customers/fetchCustomers",
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
    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.clear.mode = defaultop[0]?defaultop[0].id:0;
    },
    resetModal() {
      this.clear.id = "";
      this.clear.paid = "";
      this.setDefaultMode();
      this.clear.total = "";
    },
    resetError(){
        this.amountError = "";
        this.modeError = "";
    },
    addCommas() {
        this.clear.paid = this.thousandSeperator(this.clear.paid);
    },
    ShowClear(data) {
        this.clear.id = data.id;
        this.clear.paid = (data.totalAmt-(data.totalPaid-data.discount)).toLocaleString();
        this.clear.total = data.totalAmt;
    },
    checkAmt(){
      if(this.clear.paid.replace(/,/g, '') > this.clear.total && this.GeneralSettings.deposit_balances == 0){
          this.amountError = "Amount Paid is greater than balance";
          return
      }
      else{
          this.amountError = "";
      }
    },
    clearAmt(modalId){
        this.modal_id = modalId;
        if(this.clear.paid==""){
            this.amountError = "Amount Paid is required";
            return
        }
        else{
            this.amountError = "";
        }
        if(this.clear.paid.replace(/,/g, '') > this.clear.total && this.GeneralSettings.deposit_balances == 0){
            this.amountError = "Amount Paid is greater than balance";
            return
        }
        else{
            this.amountError = "";
        }
        if(this.clear.mode==""){
            this.modeError = "Payment Method is required";
            return
        }
        else{
            this.modeError = "";
        }
        this.SaveStatus = true;
        this.ClearBalance(this.clear).then(() => {
          this.SaveStatus = false;
          this.FetchDebtors(this.data);
          this.closeModal(modalId);
        });
    },

    clearAmtPrint(modalId){
        this.modal_id = modalId;
        if(this.clear.paid==""){
            this.amountError = "Amount Paid is required";
            return
        }
        else{
            this.amountError = "";
        }
        if(this.clear.paid.replace(/,/g, '') > this.clear.total && this.GeneralSettings.deposit_balances == 0){
            this.amountError = "Amount Paid is greater than balance";
            return
        }
        else{
            this.amountError = "";
        }
        if(this.clear.mode==""){
            this.modeError = "Payment Method is required";
            return
        }
        else{
            this.modeError = "";
        }
        this.SaveStatus = true;
        this.ClearBalancePrint(this.clear).then(() => {
          this.SaveStatus = false;
          this.FetchDebtors(this.data);
          this.closeModal(modalId);
        });
    },

    closeModal(modalId) {
      this.resetError();
      this.resetModal();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
    filterReport(){
      this.FetchDebtors(this.data);
      this.setDefaultMode();
    },
    
    handleUpload() {
      this.file = this.$refs.file.files[0];
    },
  },
  created() {
    this.FetchBranches();
    this.FetchDebtors(this.data);
    this.FetchGeneralSettings();
    this.FetchReceiptSettings();
    this.FetchCustomers();
    this.FetchModes()
    this.setDefaultMode();
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
                <h4 class="card-title text-center">Items Sold On Credit from {{ data.datefrom }} to {{ data.dateto }}</h4>
            </div>

            <div class="row">
              <div class="col-md-4"></div>              
              <div class="col-md-4 mb-3" v-if="branchid == 0">
                <label>FILTER BY BRANCHES</label>
                
                <model-list-select
                  :list="Branches ? Branches : []"
                  v-model="data.branch_id"
                  option-value="id"
                  option-text="name"
                  placeholder="Select Branch"
                  @input="filterReport()"
                >
                </model-list-select>
              </div>
              <div v-else class="col-md-8" align="right">
                <router-link to="/sales/imported_debtors"><b-button variant="info">Imported Debtors</b-button></router-link>
              </div> 
            </div> 

            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label>From</label>
                  <input
                    @change="filterReport()"
                    type="date"
                    v-model="data.datefrom"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label>To</label>
                  <input
                    @change="filterReport()"
                    type="date"
                    v-model="data.dateto"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-md-4 form-group">
                <label>CUSTOMERS</label>
                
                <model-list-select
                  :list="Customers ? Customers : []"
                  v-model="data.customer"
                  option-value="id"
                  option-text="name"
                  placeholder="Select Customer"
                  @input="filterReport()"
                >
                </model-list-select>
              </div>
              <div class="col-md-2 mt-4">
                <button class="btn btn-primary" type="button" @click="filterReport()">
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
                  :items="Debtors?Debtors:[]"
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
                <template #cell(tt_amt)="data">
                  {{ (data.item.totalAmt).toLocaleString() }}
                </template>
                <template #cell(cust)="data">
                    {{ data.item.customer }}-{{ data.item.customerPhone }}
                </template>
                <template #cell(amt_paid)="data">
                    {{ (data.item.totalPaid).toLocaleString() }}
                </template>
                <template #cell(discount_field)="data">
                  {{ (data.item.discount).toLocaleString() }}
                </template>
                <template #cell(balance)="data">
                  {{ data.item.balance?(data.item.balance).toLocaleString():0 }}
                </template>
                <template v-if="MyUserPerm.clear_debtors == 1" #cell(actions)="data">
                  <button @click="ShowClear(data.item)" class="btn btn-primary btn-sm" v-b-modal.clear-modal><i class="fa fa-check"></i> Clear</button>
                </template>
                <template #cell(details)="data">
                  <table  class="table">
                    <tbody>
                    <tr v-for="(det,d) in data.item.items" :key="d" width="100%">
                      <td>{{ det.product }}</td>
                      <td>{{ det.qty }}{{ det.usymbol }}</td>
                    </tr>
                    </tbody>
                  </table>
                </template>
              </b-table>
            </div>
            <Loader  v-if="!Debtors"/>
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
    <b-modal id="clear-modal" hide-footer @hidden="resetModal()">
      <template #modal-title>
        <h4 class="text-warning">Clear Credit of {{ (clear.paid).toLocaleString() }}</h4></template
      >
        <label>Amount Paid <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            v-model="clear.paid"
            placeholder="Amount Paid"
            @input="resetError(),addCommas(),checkAmt()"
          />
        </div>
        <div class="text-danger" v-if="amountError">
          <em>{{ amountError }}</em>
        </div>
        <div class="form-group">
            <label>Payment Method <span class="text-danger">*</span></label>
            <model-list-select
                :list="PaymentOptions ? PaymentOptions : []"
                v-model="clear.mode"
                option-value="id"
                option-text="mode"
                placeholder="Select Method"
                @input="resetError()"
            >
            </model-list-select>
        </div>
        <div class="text-danger" v-if="modeError">
            <em>{{ modeError }}</em>
        </div>
        <label>Payment Date</label>
        <div class="input-group">
          <input
            type="date"
            class="form-control"
            v-model="clear.date"
          />
        </div>
      <div>
        <div class="row mt-3">
          <div class="col-md-4">
            <button v-if="SaveStatus" type="button" class="btn btn-warning" disabled>
              Confirm & Print
              <b-spinner
                variant="light"
                small
                label="Submiting"
              ></b-spinner>
            </button>
            <button v-else type="button" class="btn btn-warning" @click="clearAmtPrint('clear-modal')">
              Confirm & Print
            </button>
          </div> 
          <div class="col-md-4"></div>                        
          <div class="col-md-4">          
          <button v-if="SaveStatus" type="button" class="btn btn-warning" disabled>
            Confirm & Exit
            <b-spinner
              variant="light"
              small
              label="Submiting"
            ></b-spinner>
          </button>
          <button v-else type="button" class="btn btn-warning" @click="clearAmt('clear-modal')">
            Confirm & Exit
          </button>
          </div>  
        </div>       
      </div>
    </b-modal>

    
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
