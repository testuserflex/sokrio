<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";
import { ModelListSelect } from "vue-search-select";


/**
 * Advanced table component
 */
export default {
  page: {
    title: "Money Transfers",
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
      title: "Money Transfers",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Money Transfers",
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
          key: "amount",
          sortable: true,
          label: "AMOUNT",
        },
        {
          key: "from_field",
          sortable: true,
          label: "SOURCE ACCOUNT",
        },
        {
          key: "to_field",
          sortable: true,
          label: "DESTINATION ACCOUNT",
        },
        {
          key: "madeBy",
          sortable: true,
          label: "MADE BY",
        },
        {
          key: "reason",
          sortable: true,
          label: "DESCRIPTION",
        },
        {
          key: "recordedby.name",
          sortable: true,
          label: "ADDED By",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingOption: [],
      sourceError: "",
      destinationError: "",
      amountError: "",

      // Value from user input for adding option
      data: {
        source: "",
        destination: "",
        date: "",
        amount: "",
        madeby: "",
        reason: "",
      },

      // delete option
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
    ...mapGetters("setting", ["PaymentOptions","MoneyTransfers", "SaveStatus"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.MoneyTransfers ? this.MoneyTransfers.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.MoneyTransfers ? this.MoneyTransfers.length : 1;
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
    }
  },
  methods: {
    ...mapActions({
      FetchPaymentOptions: "setting/fetchPaymentOptions",
      FetchMoneyTransfers: "setting/fetchMoneyTransfers",
      AddMoneyTransfer: "setting/addMoneyTransfer",
      DeleteMoneyTransfer: "setting/deleteMoneyTransfer",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    checkName() {
      //when creating a new one

      if (this.data.source != "" && this.data.amount !="" ) {
        let bck = this.PaymentOptions.filter((mode) => {
          return mode.id == this.data.source
        });
        let bal = bck[0].amount;
        // alert(bal)
        if (bal<this.data.amount){
          this.amountError="Amount to transfer cannot be greater than the source account balance";
          return
        }

      }
    },

    addMoneyTransfer(modalId) {
      this.modal_id = modalId;

      if (this.data.source === "") {
        this.sourceError = "Source field is required";
        return;
      } else {
        this.sourceError = "";
      }
      if (this.data.destination === "") {
        this.destinationError = "Destination  Type field is required";
        return;
      } else {
        this.destinationError = "";
      }
      if (this.data.amount === "") {
        this.amountError = "Amount Type Name field is required";
        return;
      } else {
        this.amountError = "";
      }
      if (this.data.source === this.data.destination) {
        this.destinationError = "Source and Destination Accounts Cannot Be the Same";
        return;
      } else {
        this.destinationError = "";
      }
      this.checkAmount()

      this.AddMoneyTransfer(this.data);
    },
    checkAmount(){
    //  to check amount balances here for sourse account
    },

    resetAddCat() {
      this.data.source = "";
      this.data.destination = "";
      this.data.amount = "";
      this.data.reason = "";
      this.data.madeby = "";
      this.data.date = "";
      this.sourceError = "";
      this.destinationError = "";
      this.amountError = "";
      this.modal_id = "";
    },

    resetDeleteCat() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },


    resetError() {
      this.sourceError = "";
      this.destinationError = "";
      this.amountError = "";
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.amount+" from "+data.item.from.name+" to "+data.item.to.name;
      this.deletedata.id = data.item.id;
    },

    deleteMoneyTransfer(modalId) {
      this.modal_id = modalId;
      this.DeleteMoneyTransfer(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

  },
  created() {
    this.FetchPaymentOptions();
    this.FetchMoneyTransfers();
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
                <h4 class="card-title">Money Transfers</h4>
              </div>
              <div v-if="MyUserPerm.add_money_transfers == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
                <b-button v-b-modal.add-option-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
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
                  :items="MoneyTransfers?MoneyTransfers:[]"
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
                <template #cell(from_field)="data">
                  {{ data.item.from.name }} - {{ data.item.from.type }}({{ data.item.from.account_number }})
                </template>
                <template #cell(to_field)="data">
                  {{ data.item.to.name }} - {{ data.item.to.type }}({{ data.item.to.account_number }})
                </template>

                <template #cell(name_field)="data">
                  {{ data.item.name }}
                  <span class="btn btn-light btn-rounded btn-xs" v-if="data.item.default==1"><small>default</small></span>
                  <b-button v-else
                            class="btn-xs"
                            variant="success"
                            v-b-modal.default-option-modal
                            @click="showDefaultModal(data)"
                  ><i class="fa fa-cog"></i> Set Default</b-button
                  >
                </template>

                <template #cell(actions)="data">
                  <b-button
                      v-if="MyUserPerm.delete_money_transfers == 1"
                      size="sm"
                      variant="danger"
                      v-b-modal.delete-option-modal
                      @click="showDeleteModal(data)"
                  ><i class="fa fa-xs fa-trash"> Delete</i></b-button
                  >
                </template>
              </b-table>
            </div>
            <Loader v-if="!MoneyTransfers" />
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
    <b-modal id="add-option-modal" hide-footer @hidden="resetAddCat">
      <template #modal-title> Add Transfer </template>
      <div class="form-group mb-2">
        <label>Date</label>
        <div class="input-group">
          <input
              type="date"
              placeholder="Enter Balance"
              class="form-control"
              v-model="data.date"
          />
        </div>
      </div>
      <div class="form-group mb-2">
        <label>Source Account</label>
        <div class="input-group">
          <model-list-select
              :list="PaymentOptions ? PaymentOptions : []"
              v-model="data.source"
              option-value="id"
              option-text="mode"
              placeholder="Select Method"

          >
          </model-list-select>
        </div>
        <div class="text-danger" v-if="sourceError">
          <em>{{ sourceError }}</em>
        </div>
      </div>
      <div class="form-group mb-2">
        <label>Destination account</label>
        <model-list-select
            :list="PaymentOptions ? PaymentOptions : []"
            v-model="data.destination"
            option-value="id"
            option-text="mode"
            placeholder="Select Method"

        >
        </model-list-select>
        <div class="text-danger" v-if="destinationError">
          <em>{{ destinationError }}</em>
        </div>
      </div>
      <div class="form-group mb-2">
        <label>Amount</label>
        <div class="input-group">
          <input
            type="number"
            placeholder="Enter Balance"
            class="form-control"
            v-model="data.amount"
            @input="checkName()"
        />
        </div>
        <div class="text-danger" v-if="amountError">
          <em>{{ amountError }}</em>
        </div>
      </div>
      <div class="form-group mb-2">
        <label>Made By</label>
        <div class="input-group">
          <input
              type="text"
              placeholder="Enter the Name"
              class="form-control"
              v-model="data.madeby"
              @input="resetError()"
          />

        </div>
      </div>

      <div class="form-group mb-2">
        <label>Description</label>
        <div class="input-group">

          <textarea
              class="form-control"
              v-model="data.reason"
              placeholder="Description"
          >

          </textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-danger"
            data-dismiss="modal"
            @click="closeModal('add-option-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-primary"
            @click="addMoneyTransfer('add-option-modal')"
        ><i class="fa fa-check"></i>
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


    <!-- modal delete option starts -->
    <b-modal
        header-bg-variant="danger"
        header-text-variant="light"
        body-text-variant="danger"
        footer-bg-variant="danger"
        footer-text-variant="danger"
        id="delete-option-modal"
        hide-footer
        @hidden="resetDeleteCat"
    >
      <template #modal-title> Delete transfer of {{ deletedata.name }}</template>
      <div class="form-group mb-2">
        <div class="input-group">
          <h5>
            Do you want to delete this Transfer ? Click Proceed Button to delete.
          </h5>
        </div>
      </div>
      <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="closeModal('delete-option-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
            type="button"
            class="btn btn-danger"
            @click="deleteMoneyTransfer('delete-option-modal')"
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
