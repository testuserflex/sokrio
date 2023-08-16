<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
import { ModelListSelect } from "vue-search-select";
import moment from "moment";



/**
 * Advanced table component
 */
export default {
  page: {
    title: "Supplier Deposits",
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
      title: "Supplier Deposits ",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Suppliers",
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
          key: "date",
          sortable: true,
          label: "DATE",
        },
        {
          key: "supplier.name",
          sortable: true,
          label: "SUPPLIER",
        },
        {
          key: "damount",
          sortable: true,
          label: "AMOUNT",
        },
        {
          key: "mode",
          sortable: true,
          label: "MODE",
        },

        {
          key: "user.name",
          sortable: true,
          label: "ADDED By",
        },
        {
          key: "added_on",
          sortable: true,
          label: "DATE ADDED",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      amountError: "",
      dateError: "",
      supplierError: "",
      modeError: "",

      // Value from user input for adding a deposit
      data: {
        supplier: "",
        amount: "",
        mode: "",
        date: moment(new Date()).format("YYYY-MM-DD")
      },
      // edit unit
      editdata: {
        date: "",
        amount: "",
        id: "",
      },
      // delete unit
      deletedata: {
        name: "",
        id: "",
      },
    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("stock", ["SaveStatus", "Suppliers","SupplierDeposits"]),
    ...mapGetters("setting", ["PaymentOptions"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.SupplierDeposits ? this.SupplierDeposits.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.SupplierDeposits ? this.SupplierDeposits.length : 1;
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue == true) {
        this.closeModal(this.modal_id);
        if (this.type == "success") {
          Swal.fire("Success!", this.message.msg, "success");
          // this.$toast.success(this.message.msg);
        } else {
          // this.$toast.error(this.message.msg);
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
  },
  methods: {
    ...mapActions({
      FetchSupplierDeposits: "stock/fetchSupplierDeposits",
      FetchSuppliers: "stock/fetchSuppliers",
      FetchPaymentOptions: "setting/fetchPaymentOptions",
      AddSupplierDeposit: "stock/addSupplierDeposit",
      EditSupplierDeposit: "stock/editSupplierDeposit",
      DeleteSupplierDeposit: "stock/deleteSupplierDeposit",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },



    addSupplierDeposit(modalId) {
      this.modal_id = modalId;

      if (this.data.date === "") {
        this.dateError = "Date  field is required";
        return;
      } else {
        this.dateError = "";
      }
      if (this.data.date > moment(new Date()).format("YYYY-MM-DD")) {
        this.dateError = "Dat cannot be greater than today";
        return;
      } else {
        this.dateError = "";
      }

      if (this.data.supplier === "") {
        this.supplierError = "Supplier  field is required";
        return;
      } else {
        this.supplierError = "";
      }

      if (this.data.amount === "") {
        this.amountError = "Amount field is required";
        return;
      } else {
        this.amountError = "";
      }
      if (this.data.mode === "") {
        this.modeError = "Payment Mode field is required";
        return;
      } else {
        this.modeError = "";
      }


      this.AddSupplierDeposit(this.data);
    },

    resetAdd() {
      this.data.supplier = "";
      this.data.amount = "";
      this.data.mode = "";
      this.modal_id = "";
    },
    resetEdit() {
      this.editdata.date = "";
      this.editdata.amount = "";
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.supplierError = "";
      this.amountError = "";
      this.dateError = "";
      this.modeError = "";
    },
    showEditModal(val) {
      this.editdata.amount = this.thousandSeperator(val.item.amount);
      this.editdata.date = val.item.date;
      this.editdata.id = val.item.id;
    },
    editSupplierDeposit(modalId) {
      this.modal_id = modalId;
      if ((this.editdata.amount < 0) || (this.editdata.amount == "")) {
        this.amountError = "Amount should be greater than 0";
        return;
      } else {
        this.amountError = "";
      }
      if (this.editdata.date == "") {
        this.dateError = "Date field is required";
        return;
      } else {
        this.dateError = "";
      }
      this.EditSupplierDeposit(this.editdata);
    },
  
    showDeleteModal(val) {
      this.deletedata.name = this.thousandSeperator(val.item.amount);
      this.deletedata.id = val.item.id;
    },
    deleteSupplierDeposit(modalId) {
      this.modal_id = modalId;
      this.DeleteSupplierDeposit(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
    thousandSeperator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        let xx = m.toString().split(".");
        let x1=xx[0]
            .toString()
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if (!xx[1]){
            if(m.toString().charAt(m.length - 1) == '.'){
            return x1+".";
             
            }else{
                return x1;

            }
        }else{
          return x1+"."+xx[1];
        }
      } else {
        return m;
      }
    },
    addCommas() {
      this.data.amount = this.thousandSeperator(this.data.amount);
    },
    addComma() {
      this.editdata.amount = this.thousandSeperator(this.editdata.amount);
    },

    setDefaultMode(){
      let defaultop = this.PaymentOptions?this.PaymentOptions:[].filter((mode)=>{
            return mode.default == 1;
        })
        this.data.mode = defaultop[0]?defaultop[0].id:0;
    },
  },
  created() {
    this.FetchSupplierDeposits();
    this.FetchSuppliers();
    this.FetchPaymentOptions();
    this.setDefaultMode();
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
                <h4 class="card-title">Supplier Deposits</h4>
              </div>
              <div v-if="MyUserPerm.add_supplier_deposits == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
                <b-button v-b-modal.add-product-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
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
                  :items="SupplierDeposits?SupplierDeposits:[]"
                  :fields="fields"
                  responsive="sm"
                  :per-page="perPage"
                  :current-page="currentPage"
                  :sort-desc.sync="sortDesc"
                  :filter="filter"
                  :filter-included-fields="filterOn"
                  @filtered="onFiltered"
              >  
               <template #cell(damount)="data">
                  <span >
                    {{ thousandSeperator(data.item.amount) }}
                    </span>

                </template>
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>

                <template #cell(actions)="data">
                  <b-button
                      v-if="MyUserPerm.edit_supplier_deposits == 1"
                      size="sm"
                      variant="primary"
                      v-b-modal.edit-product-modal
                      class="m-2"
                      @click="showEditModal(data)"
                  ><i class="fa fa-edit"></i></b-button
                  >
                  <b-button
                      v-if="MyUserPerm.delete_supplier_deposits == 1"
                      size="sm"
                      variant="danger"
                      v-b-modal.delete-product-modal
                      @click="showDeleteModal(data)"
                  ><i class="fa fa-trash"></i></b-button
                  >
                </template>
              </b-table>
            </div>
            <Loader  v-if="!SupplierDeposits"/>
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
    <!-- modal starts -->
    <b-modal id="add-product-modal" hide-footer @hidden="resetAdd">
      <template #modal-title> Add Supplier deposit </template>
          <div class="form-group mb-3">
            <label>Date <span class="text-danger">*</span></label>
              <div class="input-group">
                <input
                    type="date"
                    placeholder="Enter date "
                    class="form-control"
                    v-model="data.date"
                    max=""
                    @input="resetError()"

                />
              </div>
              <div class="text-danger" v-if="dateError">
                <em>{{ dateError }}</em>
              </div>
            </div>
            <div class="form-group mb-3">
              <label>Select Supplier <span class="text-danger">*</span></label>
              <model-list-select
                  :list="Suppliers ? Suppliers : []"
                  v-model="data.supplier"
                  option-value="id"
                  option-text="name"
                  class="mb-3"
                  placeholder="Select Supplier"
                  @input="resetError()"
              >
              </model-list-select>

              <div class="text-danger" v-if="supplierError">
                <em>{{supplierError }}</em>
              </div>
            </div>


      <div class="form-group mb-3">
        <label>Amount  <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
              type="text"
              placeholder="Enter Amount "
              class="form-control"
              @input="resetError(), addCommas()"
              v-model="data.amount"
          />
        </div>
        <div class="text-danger" v-if="amountError">
          <em>{{ amountError }}</em>
        </div>
      </div>

      <div class="form-group mb-3">
        <label>Mode <span class="text-danger">*</span></label>
        <model-list-select
            :list="PaymentOptions ? PaymentOptions : []"
            v-model="data.mode"
            option-value="id"
            option-text="mode"
            class="mb-3"
            placeholder="Select mode"
            @input="resetError()"
        >
        </model-list-select>
        <div class="text-danger" v-if="modeError">
          <em>{{ modeError }}</em>
        </div>

      </div>



      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-danger"
            data-dismiss="modal"
            @click="closeModal('add-product-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-primary"
            @click="addSupplierDeposit('add-product-modal')"
        > <i class="fa fa-check"></i>
          Submit
          <b-spinner
              v-if="SaveStatus"
              variant="light"
              small
              label="Spinning"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
    <!--     edit modal starts-->
    <b-modal id="edit-product-modal" hide-footer @hidden="resetEdit">
      <template #modal-title> Edit Supplier deposit of {{ editdata.amount }}</template>
      <div class="form-group mb-3">
        <label>Date <span class="text-danger">*</span></label>
        <div class="input-group">
          <input
              type="date"
              class="form-control"
              @input="resetError()"
              v-model="editdata.date"
          />
        </div>
        <div class="text-danger" v-if="dateError">
          <em>{{ dateError }}</em>
        </div>
      </div>
      <div class="form-group mb-3">
        <label>Amount <span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control"
            @input="resetError(), addComma()"
            v-model="editdata.amount"
            
        />

        <div class="text-danger" v-if="supplierError">
          <em>{{ supplierError }}</em>
        </div>
      </div>
      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-danger"
            data-dismiss="modal"
            @click="closeModal('edit-product-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-primary"
            @click="editSupplierDeposit('edit-product-modal')"
        > <i class="fa fa-check"></i>
          Save Changes
          <b-spinner
              v-if="SaveStatus"
              variant="light"
              small
              label="Saving"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->

    <!-- modal delete product starts -->
    <b-modal
        header-bg-variant="danger"
        header-text-variant="light"
        body-text-variant="danger"
        footer-bg-variant="danger"
        footer-text-variant="danger"
        id="delete-product-modal"
        hide-footer
        @hidden="resetDelete"
    >
      <template #modal-title> Delete Supplier deposit of {{ deletedata.name }}</template>
      <div class="form-group mb-3">
        <div class="input-group">
          <h5>
            Do you want to delete this record? Click Proceed Button to delete.
          </h5>
        </div>
      </div>
      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="closeModal('delete-product-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-danger"
            @click="deleteSupplierDeposit('delete-product-modal')"
        >
          <i class="fa fa-trash"></i> Proceed
          <b-spinner
              v-if="SaveStatus"
              variant="light"
              small
              label="Deleting"
          ></b-spinner>
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
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
