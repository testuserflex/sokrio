<script>
// import axios from "axios";

import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import { ModelListSelect } from "vue-search-select";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapGetters, mapActions, mapState } from "vuex";
import moment from "moment";
import Swal from "sweetalert2";
/**
 * Advanced table component
 */
export default {
  page: {
    title: "Expenses",
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
      title: "Expenses",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Expenses",
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
          label: "DATE/TIME",
        },

        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      // Value from user input for adding category
      data: {
        description: "",
        category: "",
        date: moment(new Date()).format("YYYY-MM-DD"),
        amount: "",
        mode: "",
      },
      // edit category
      editdata: {
        description: "",
        category: "",
        id: "",
        amount: "",
        mode: "",
        date: "",
      },
      // make inputs required
      amountError: "",
      dateError: "",
      categoryError: "",
      modeError: "",
      // delete category
      deletedata: {
        amount: "",
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
    ...mapGetters("expenses", ["Expenses", "ExpenseSaveStatus", "ExpenseCategories"]),
    ...mapGetters("setting", ["PaymentOptions"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Expenses ? this.Expenses.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Expenses ? this.Expenses.length : 1;
  },
  updated(){
  },
  watch: {
    show(newValue) {
      // Do whatever makes sense now
      if (newValue == true) {
        if (this.type == "success") {
          this.closeModal(this.modal_id);
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
      FetchExpenses: "expenses/fetchExpenses",
      AddExpenditure: "expenses/recordExpense",
      EditExpenditure: "expenses/editExpenses",
      DeleteExpense: "expenses/deleteExpense",
      FetchExpenseCategories: "expenses/fetchExpenseCategories",
      FetchModes: "setting/fetchPaymentOptions",
    }),
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
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    setDefaultMode(){
      let defaultop = this.PaymentOptions.filter((mode)=>{
            return mode.default == 1;
        })
        this.data.mode = defaultop[0].id;
    },

    addExpense(modalId) {
      this.modal_id = modalId;

      if (this.data.amount === "") {
        this.amountError = "Amount Spent is required";
        return;
      } else {
        this.amountError = "";
      }
      if (this.data.category === "") {
        this.categoryError = "Expense Category is required";
        return;
      } else {
        this.categoryError = "";
      }
      if (this.data.date === "") {
        this.dateError = "Date field is required";
        return;
      } else {
        this.dateError = "";
      }
      if (this.data.mode === "") {
        this.modeError = "Payment Method is required";
        return;
      } else {
        this.modeError = "";
      }

      this.AddExpenditure(this.data);
    },

    resetAddExp() {
      this.data.date = "";
      this.data.amount = "";
      this.data.description = "";
      this.data.category = "";
      this.data.mode = "";
      this.amountError = "";
      this.dateError = "";
      this.modeError = "";
      this.categoryError = "";
      this.modal_id = "";
    },
    resetEditExp() {
      this.editdata.amount = "";
      this.editdata.date = "";
      this.editdata.mode = "";
      this.editdata.description = "";
      this.editdata.category = "";
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDeleteExp() {
      this.deletedata.amount = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetErrors() {
      this.amountError = "";
      this.dateError = "";
      this.modeError = "";
      this.categoryError = "";
    },
    showEditModal(data) {
      this.editdata.amount = data.item.amount;
      this.editdata.date = data.item.date;
      this.editdata.mode = data.item.mode.id;
      this.editdata.id = data.item.id;
      this.editdata.category = data.item.category.id;
      this.editdata.description = data.item.description;
    },
    editExpense(modalId) {
      this.modal_id = modalId;
      this.EditExpenditure(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.amount = data.item.amount;
      this.deletedata.id = data.item.id;
    },
    deleteExpenses(modalId) {
      this.modal_id = modalId;
      this.DeleteExpense(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchExpenses();
    this.FetchExpenseCategories();
    this.FetchModes();
    setTimeout(()=> {
         this.setDefaultMode();
    }, 3000);
  },
};
</script>

<template>
  <Layout>
    <Toasts
      :show-progress="true"
      :rtl="true"
      :max-messages="20"
      :time-out="3000"
      :closeable="false"
      :solid="true"
    ></Toasts>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <b-button
                  v-if="MyUserPerm.add_expenses == 1"
                  class="float-end"
                  variant="primary"
                  v-b-modal.add-expense-modal
                  ><i class="fa fa-plus"></i> Record Expense</b-button
                >
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
                :items="Expenses ? Expenses : []"
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
                <template #cell(mode_field)="data">
                  {{ data.item.mode.name }} ({{ data.item.mode.type_name }}-{{ data.item.mode.account_number }})
                </template>
                <template #cell(amount_field)="data">
                  {{ thousandSeperator(data.item.amount) }}
                </template>
                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    v-if="MyUserPerm.edit_expenses == 1"
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-expense-modal
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    v-if="MyUserPerm.delete_expenses == 1"
                    size="sm"
                    variant="danger"
                    v-b-modal.delete-expense-modal
                    @click="showDeleteModal(data)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                  </b-button-group>
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

            <!-- add expense modal starts -->
            <b-modal id="add-expense-modal" hide-footer @hidden="resetAddExp">
              <template #modal-title> Record New Expense</template>
              <div class="form-group mb-3">
                <label>Expense Category</label>
                <model-list-select
                  :list="ExpenseCategories ? ExpenseCategories : []"
                  v-model="data.category"
                  option-value="id"
                  option-text="name"
                  class="mb-3"
                  placeholder="Select Expense Category"
                  @input="resetErrors()"
                >
                </model-list-select>
                <div class="text-danger" v-if="categoryError">
                  <em>{{ categoryError }}</em>
                </div>
              </div>
              <div class="form-group mb-3">
                <label>Expense Amount</label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    v-model="data.amount"
                    @input="resetErrors()"
                    placeholder="Amount Spent"
                  />
                </div>
                <div class="text-danger" v-if="amountError">
                  <em>{{ amountError }}</em>
                </div>
              </div>
              <div class="form-group mb-3">
                <label>Date</label>
                <div class="input-group">
                  <input
                    type="date"
                    class="form-control"
                    v-model="data.date"
                    @input="resetErrors()"
                  />
                </div>
                <div class="text-danger" v-if="dateError">
                  <em>{{ dateError }}</em>
                </div>
              </div>
              <div class="form-group">
                <label>Payment Method</label>
                <model-list-select
                  :list="PaymentOptions ? PaymentOptions : []"
                  v-model="data.mode"
                  option-value="id"
                  option-text="mode"
                  placeholder="Select Method"
                  @input="resetErrors()"
                >
                </model-list-select>
              </div>
              <div class="text-danger" v-if="modeError">
                <em>{{ modeError }}</em>
              </div>
              <div class="form-group mb-3">
                <label>Description</label>
                <div class="input-group">
                    <textarea rows="5" 
                    class="form-control"
                    v-model="data.description"
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
                  @click="closeModal('add-expense-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="addExpense('add-expense-modal')"
                >
                  <i class="fa fa-check"></i> Save Expense
                  <b-spinner
                    v-if="ExpenseSaveStatus"
                    variant="light"
                    small
                    label="Saving"
                  ></b-spinner>
                </button>
              </div>
            </b-modal>
            <!-- modal ends -->
            <!-- edit modal starts -->
            <b-modal id="edit-expense-modal" hide-footer @hidden="resetEditExp">
              <template #modal-title> Edit Expense of {{ thousandSeperator(editdata.amount) }}</template>
              <div class="form-group mb-3">
                <label>Expense Category</label>
                <model-list-select
                  :list="ExpenseCategories ? ExpenseCategories : []"
                  v-model="editdata.category"
                  option-value="id"
                  option-text="name"
                  class="mb-3"
                  placeholder="Select Expense Category"
                >
                </model-list-select>
              </div>
              <div class="form-group mb-3">
                <label>Expense Amount</label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    v-model="editdata.amount"
                    @input="resetErrors()"
                    placeholder="Amount Spent"
                  />
                </div>
                <div class="text-danger" v-if="amountError">
                  <em>{{ amountError }}</em>
                </div>
              </div>
              <div class="form-group mb-3">
                <label>Date</label>
                <div class="input-group">
                  <input
                    type="date"
                    class="form-control"
                    v-model="editdata.date"
                    @input="resetErrors()"
                  />
                </div>
                <div class="text-danger" v-if="dateError">
                  <em>{{ dateError }}</em>
                </div>
              </div>
              <div class="form-group">
                <label>Payment Method</label>
                <model-list-select
                  :list="PaymentOptions ? PaymentOptions : []"
                  v-model="editdata.mode"
                  option-value="id"
                  option-text="name"
                  placeholder="Select Method"
                  @input="resetErrors()"
                >
                </model-list-select>
              </div>
              <div class="text-danger" v-if="modeError">
                <em>{{ modeError }}</em>
              </div>
              <div class="form-group mb-3">
                <label>Description</label>
                <div class="input-group">
                    <textarea rows="5" 
                    class="form-control"
                    v-model="editdata.description"
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
                  @click="closeModal('edit-expense-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="editExpense('edit-expense-modal')"
                >
                  <i class="fa fa-check"></i> Save Changes
                  <b-spinner
                    v-if="ExpenseSaveStatus"
                    variant="light"
                    small
                    label="Saving"
                  ></b-spinner>
                </button>
              </div>
            </b-modal>
            <!-- modal ends -->

            <!-- modal delete expense starts -->
            <b-modal
              header-bg-variant="danger"
              header-text-variant="light"
              body-text-variant="danger"
              footer-bg-variant="danger"
              footer-text-variant="danger"
              id="delete-expense-modal"
              hide-footer
              @hidden="resetDeleteExp"
            >
              <template #modal-title> Delete Expenditure of {{ thousandSeperator(deletedata.amount) }}</template>
              <div class="form-group mb-3">
                <div class="input-group">
                  <h5>Do you want to delete this expense? This is a permanent action.</h5>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('delete-expense-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-danger"
                  @click="deleteExpenses('delete-expense-modal')"
                >
                  <i class="fa fa-trash"></i> Proceed
                  <b-spinner
                    v-if="ExpenseSaveStatus"
                    variant="light"
                    small
                    label="Deleting"
                  ></b-spinner>
                </button>
              </div>
            </b-modal>
            <!-- modal ends -->
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>   
