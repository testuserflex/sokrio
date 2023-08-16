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
    title: "Creditors",
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
      title: "Credit Purchases",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Creditors",
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
          key: "supplier",
          sortable: true,
          label: "SUPPLIER",
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
      amountError: "",
      modeError: "",

    };
  },
  computed: {
         // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
      ]),
    ...mapGetters("purchase", ["SaveStatus","Creditors"]),
    ...mapGetters("setting", ["PaymentOptions"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Creditors ? this.Creditors.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Creditors ? this.Creditors.length : 1;
  },
  updated() {
    
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
  },
  methods: {
    ...mapActions({
      FetchCreditors: "purchase/fetchCreditors",
      ClearBalance: "purchase/ClearCredit",
      FetchModes: "setting/fetchPaymentOptions",

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
            .replace(/[^0-9.]+/g, "")
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
        this.clear.paid = (data.totAmt-data.amtPaid-data.discount).toLocaleString();
        this.clear.total = data.totAmt;
    },
    checkAmt(){
      if(this.clear.paid.replace(/,/g, '') > this.clear.total){
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
        if(this.clear.paid.replace(/,/g, '') > this.clear.total){
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
        this.ClearBalance(this.clear);
    },
    closeModal(modalId) {
      this.resetError();
      this.resetModal();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

  },
  created() {
    this.FetchCreditors();
    this.FetchModes();
    setTimeout(()=> {
         this.setDefaultMode();
    }, 3000);
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
                <h4 class="card-title">Items Bought On Credits</h4>
              </div>

            </div>

            <div class="row">
              <div class="col-md-4"></div> 
              <div class="col-md-8" align="right">
                <router-link to="/purchases/imported_creditors"><b-button variant="info">Imported Creditors</b-button></router-link>
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
                  :items="Creditors?Creditors:[]"
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
                  {{ (data.item.totAmt).toLocaleString() }}
                </template>
                <template #cell(amt_paid)="data">
                    {{ (data.item.amtPaid).toLocaleString() }}
                </template>
                <template #cell(discount_field)="data">
                  {{ (data.item.discount).toLocaleString() }}
                </template>
                <template #cell(balance)="data">
                  {{ (data.item.Balance).toLocaleString() }}
                </template>
                <template v-if="MyUserPerm.clear_creditors == 1" #cell(actions)="data">
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
            <Loader  v-if="!Creditors"/>
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
        <h4 class="text-warning">Clear Credit of {{ thousandSeperator(clear.paid) }}</h4></template
      >
        <label>Amount Paid</label>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            v-model="clear.paid"
            placeholder="Quantity removed"
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
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('clear-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button v-if="SaveStatus" type="button" class="btn btn-warning" disabled>
          <i class="fa fa-minus"></i> Confirm
          <b-spinner
            variant="light"
            small
            label="Submiting"
          ></b-spinner>
        </button>
        <button v-else type="button" class="btn btn-warning" @click="clearAmt('clear-modal')">
          <i class="fa fa-minus"></i> Confirm
        </button>
      </div>
    </b-modal>
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
