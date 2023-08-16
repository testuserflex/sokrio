<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import { ModelListSelect } from "vue-search-select";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions,mapState } from "vuex"
import Swal from "sweetalert2";
import moment from "moment";
import axios from "axios";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Previous Creditors",
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
      title: "Previous Creditors",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Previous Creditors",
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
          key: "supp",
          sortable: true,
          label: "SUPPLIERS",
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

      data: {
        dateto: moment(new Date()).format("YYYY-MM-DD"),
        datefrom: "",
        supplier: "",
        branch_id:0
      },
      amountError: "",
      modeError: "",
      filteredReport: [],
      is_uploading: false,
      totalCreditors:0,

    };
  },
  computed: {
         // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
      ]),
    ...mapGetters("purchase", ["SaveStatus","ImportedCreditors"]),
    ...mapGetters("setting", ["PaymentOptions", "GeneralSettings"]),
    ...mapGetters("auth", ["MyUserPerm","business","branch","Branches","branchid"]),
    ...mapGetters("stock", ["Suppliers"]),

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
  mounted() {
    // Set the initial number of items
    this.totalRows = this.filteredReport ? this.filteredReport.length : 1;
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
    ImportedCreditors(newValue){
      if(newValue){
        this.SetData();
        this.totalCreditors= this.ImportedCreditors.reduce((acc, item) => acc + item.totalAmt, 0);
      }
    }
  },
  methods: {
    ...mapActions({
      FetchCreditors: "purchase/fetchImportedCreditors",
      ClearBalance: "purchase/ClearImportedCredit",
      FetchModes: "setting/fetchPaymentOptions",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      FetchSuppliers: "stock/fetchSuppliers",
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
        this.clear.paid = this.thousandSeperator(data.totalAmt-data.totalPaid-data.discount);
        this.clear.total = data.totalAmt;
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
    SetData(){
      if(this.ImportedCreditors){
        this.filteredReport = this.ImportedCreditors; 
      }       
    },
    filterReport() { 
      let date2 = this.data.dateto;
      let date1 = this.data.datefrom;
      this.filteredReport = this.ImportedCreditors.filter((element) => {
        if(this.data.branch_id == 0){
          if (this.data.supplier != "" && this.data.datefrom == "" && this.data.dateto != "") {
            return element.supplier.id == this.data.supplier && element.date <= date2;
          } else if (
            this.data.supplier == "" &&
            this.data.datefrom != "" &&
            this.data.dateto != ""
          ) {
            return element.date >= date1 && element.date <= date2;
          } else if (
            this.data.supplier != "" &&
            this.data.datefrom != "" &&
            this.data.dateto != ""
          ) {         
            return (
              element.supplier.id == this.data.supplier &&
              element.date >= date1 &&
              element.date <= date2
            );
          }
        }else{
          if (this.data.supplier != "" && this.data.datefrom == "" && this.data.dateto != "") {
            return element.supplier.id == this.data.supplier && element.date <= date2 && element.branch.id == this.data.branch_id;
          } else if (
            this.data.supplier == "" &&
            this.data.datefrom != "" &&
            this.data.dateto != ""
          ) {
            return element.date >= date1 && element.date <= date2 && element.branch.id == this.data.branch_id;
          } else if (
            this.data.supplier != "" &&
            this.data.datefrom != "" &&
            this.data.dateto != ""
          ) {         
            return (
              element.supplier.id == this.data.supplier &&
              element.date >= date1 &&
              element.date <= date2 && element.branch.id == this.data.branch_id
            );
          }else{
            return element.branch.id == this.data.branch_id;
          }
        }
      });
    },
    // Import Creditors
    importFile(modelId){
      this.is_uploading = true;
      let formData = new FormData();
      formData.append("file", this.file);

      const headers = { 'Content-Type': 'multipart/form-data' };
      axios.post('import_creditors', formData, { headers }).then((res) => {
        this.is_uploading = false;
        this.closeModal(modelId);
        if (res.status == 200) {
          let message = { 'msg': 'Success upload'}
          this.FetchCreditors();
          this.$store.dispatch('notification/success',message);
        } else {
          let message = { 'msg': 'Something went wrong and upload failed' }
          this.$store.dispatch('notification/error',message);
        }
      }).catch(()=>{
        this.is_uploading = false;
        this.closeModal(modelId);
        let message = { 'msg': 'Something went wrong and upload failed' }
        this.$store.dispatch('notification/error',message);
      });
    },
    handleUpload() {
      this.file = this.$refs.file.files[0];
    },
  },
  created() {
    this.FetchBranches();
    this.FetchCreditors();
    this.FetchGeneralSettings();
    this.FetchSuppliers();
    this.FetchModes();
    this.SetData();
    setTimeout(()=> {
         this.setDefaultMode();
    }, 3000);
  },
};
</script>

<template>
  <Layout>
    <div class="row py-3 text-center">
      <h4><span class="text-primary">{{business}}</span><span class="text-info" v-if="GeneralSettings.show_branchname == 1 && branch"> - {{branch}}</span></h4>
    </div>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-9">
                <h4 class="card-title">Previous Creditors</h4>
              </div>

            </div>

            <div class="row">             
              <div class="mb-3 col-md-6" v-if="branchid == 0">
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
              <div v-else class="col-md-6"></div>
              <div class="col-md-3 mb-3">
                  <b-button       
                  size="sm"
                  variant="primary"
                  @click="$router.go(-1)"
                  ><i class="fa fa-undo"></i> Back</b-button
                  >                  
                </div>
                <div class="col-md-3 mb-3">
                  <b-button v-if="BranchData.BranchId != 0" v-b-modal.import-creditors-modal variant="info" align="right"><i class="fa fa-plus"></i>Import Suppliers</b-button>
                </div>
            </div> 

            <div class="row">
              <div class="col-4">
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
              <div class="col-4">
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
                    <label>SUPPLIER</label>
                    
                    <model-list-select
                      :list="Suppliers ? Suppliers : []"
                      v-model="data.supplier"
                      option-value="id"
                      option-text="name"
                      placeholder="Select Supplier"
                      @input="filterReport()"
                    >
                    </model-list-select>
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
                  :items="filteredReport?filteredReport:[]"
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
                  {{ thousandSeperator(data.item.totalAmt) }}
                </template>
                <template #cell(supp)="data">
                    {{ data.item.supplier.name }}-{{ data.item.supplier.contact }}
                </template>
                <template #cell(amt_paid)="data">
                    {{ thousandSeperator(data.item.totalPaid) }}
                </template>
                <template #cell(discount_field)="data">
                  {{ thousandSeperator(data.item.discount) }}
                </template>
                <template #cell(balance)="data">
                  {{ thousandSeperator(data.item.totalAmt-(data.item.totalPaid+data.item.discount)) }}
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
              <table class="table">
                <thead>
                  <tr>
                    <!-- <th style="width: 70px">No.</th> -->
                    <th>TOTAL</th>
                    <th>{{thousandSeperator(totalCreditors)}}</th>
                    
                  </tr>
                </thead>
                </table>
            </div>
            <Loader  v-if="!filteredReport"/>
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
        <label>Amount Paid <span class="text-danger">*</span></label>
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

    <!--CREDITORS EXCEL UPLOAD MODAL-->
    <b-modal
        id="import-creditors-modal"
        ref="modal"
        hide-footer
        title="Upload an excel file of Suppliers"
      >
          <div class="mb-2">
            <label class="col-form-label" for="upload-file"
              >Select file to upload:</label
            >
            <input
              id="upload-file"
              class="form-control"
              type="file"
              ref="file"
              @change="handleUpload"
            />
          </div>
        <div class="modal-footer">
          <button
            class="btn btn-secondary"
            @click="closeModal('import-creditors-modal')"
            type="button"
          >
            Close
          </button>
          <button v-if="is_uploading" type="button" class="btn btn-warning" disabled>
            Import Suppliers
            <b-spinner
              variant="light"
              small
              label="Submiting"
            ></b-spinner>
          </button>
          <button v-else type="button" class="btn btn-primary" @click="importFile('import-creditors-modal')">
            Import Suppliers
          </button>
        </div>
      </b-modal>
    <!-- CREDITORS EXCEL UPLOAD END -->
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
