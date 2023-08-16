<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"
import Swal from "sweetalert2";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Payment Methods",
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
          key: "name_field",
          sortable: true,
          label: "OPTION NAME",
        },
        {
          key: "type",
          sortable: true,
          label: "TYPE",
        },
        {
          key: "type_name",
          sortable: true,
          label: "TYPE NAME",
        },
        {
          key: "anumber",
          sortable: true,
          label: "ACCOUNT NUMBER",
        },
        {
          key: "balance",
          sortable: true,
          label: "BALANCE",
        },
        {
          key: "addedby.name",
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
      nameError: "",
      accountError: "",
      typeError: "",
      typenameError: "",

      // Value from user input for adding option
      data: {
        name: "",
        type: "",
        type_name: "",
        account_number: "",
        balance: "",
      },
      // edit option
      editdata: {
        name: "",
        type: "",
        type_name: "",
        account_number: "",
        id: "",
      },
      // delete option
      deletedata: {
        name: "",
        id: "",
      },
      defaultOption: {
        name: "",
        id: "",
      },
      total:0,
    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("setting", ["PaymentOptions", "SaveStatus"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.PaymentOptions ? this.PaymentOptions.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.PaymentOptions ? this.PaymentOptions.length : 1;
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
      AddPaymentOption: "setting/addOption",
      EditPaymentOption: "setting/editOption",
      DeletePaymentOption: "setting/deleteOption",
      DefaultPaymentOption: "setting/changeDefaultOption",
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
      if (this.data.name != "" && this.data.type !="" && this.data.type_name !="") {
        this.existingOption = this.PaymentOptions.filter((PaymentOption) => {
          return (PaymentOption.name.toUpperCase() == this.data.name.toUpperCase()) 
          && (PaymentOption.type.toUpperCase() == this.data.type.toUpperCase()) 
          && (PaymentOption.type_name.toUpperCase() == this.data.type_name.toUpperCase());
        });
        if (this.existingOption.length) {
          this.nameError = this.data.name + " exists already";
        } else {
          this.nameError = "";
        }
      } else {
        this.existingOption = [];
      }
    },

    addPaymentOption(modalId) {
      this.modal_id = modalId;

      if (this.data.name === "") {
        this.nameError = "PaymentOption name field is required";
        return;
      } else {
        this.nameError = "";
      }
      if (this.data.type === "") {
        this.typeError = "Payment Option Type field is required";
        return;
      } else {
        this.typeError = "";
      }
      if (this.data.type_name === "") {
        this.typenameError = "Payment Option Type Name field is required";
        return;
      } else {
        this.typenameError = "";
      }
      if (this.data.account_number === "") {
        this.accountError = "Payment Option Account Number field is required";
        return;
      } else {
        this.accountError = "";
      }
      if (this.existingOption.length) {
        this.nameError = this.data.name + " exists already please";
        return;
      } else {
        this.nameError = "";
      }

      this.AddPaymentOption(this.data);
    },

    resetAddCat() {
      this.data.name = "";
      this.data.type = "";
      this.data.type_name = "";
      this.data.account_number = "";
      this.data.balance = "";
      this.nameError = "";
      this.nameError = "";
      this.accountError = "";
      this.typeError = "";
      this.typenameError = "";
      this.modal_id = "";
    },
    resetEditCat() {
      this.editdata.name = "";
      this.editdata.type = "";
      this.editdata.type_name = "";
      this.editdata.account_number = "";
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDeleteCat() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },
    resetDefault() {
      this.defaultOption.name = "";
      this.defaultOption.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.nameError = "";
      this.accountError = "";
      this.typeError = "";
      this.typenameError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.type_name = data.item.type_name;
      this.editdata.type = data.item.type;
      this.editdata.account_number = data.item.anumber;
      this.editdata.id = data.item.id;
    },
    editPaymentOption(modalId) {
      this.modal_id = modalId;
      this.EditPaymentOption(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.name;
      this.deletedata.id = data.item.id;
    },
    showDefaultModal(data) {
      this.defaultOption.name = data.item.name;
      this.defaultOption.id = data.item.id;
    },
    deletePaymentOption(modalId) {
      this.modal_id = modalId;
      this.DeletePaymentOption(this.deletedata);
    },
    changeDefault(modelId){
        this.modal_id = modelId;
        this.DefaultPaymentOption(this.defaultOption);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

    // COMMA SEPERATORS
    thousandSeperator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        return m;
      }
    },
    
    redirect(value) {
      this.$router.push(
        `/paymentOptions/statement/${btoa(value)}`
      );
    },
  },

  updated(){
    let total = 0;
      this.PaymentOptions?this.PaymentOptions.forEach((value) => {
        let amt = parseInt(value.balance.replaceAll(/,/g, ""));
        total = total + amt;
      }):[];
      this.total = total;
  },
  created() {
    this.FetchPaymentOptions();
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
                      <h4 class="card-title">All Payment Methods</h4>
                  </div>
                  <div v-if="MyUserPerm.add_payment_options == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
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
                :items="PaymentOptions?PaymentOptions:[]"
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
                  <b-button-group>
                  <b-button
                    size="sm"
                    variant="info"
                    @click="redirect(data.item.id)"
                    ><i class="fa fa-eye"></i> Statement</b-button
                  >
                  <b-button
                    v-if="MyUserPerm.edit_payment_options == 1"
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-option-modal
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    v-if="MyUserPerm.delete_payment_options == 1"
                    size="sm"
                    variant="danger"
                    v-b-modal.delete-option-modal
                    @click="showDeleteModal(data)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                  </b-button-group>
                </template>
              </b-table>
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 text-center">
                  <h5>Total Balance: <span class="text-danger">{{thousandSeperator(total)}}</span></h5>
                </div>
                <div class="col-md-2"></div>
              </div>
            </div>
            <Loader v-if="!PaymentOptions" />
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
    <template #modal-title> Add New Payment Option </template>
    <div class="form-group mb-2">
        <label>Option Name</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Option Name"
            class="form-control"
            @keyup="checkName"
            @input="resetError()"
            v-model="data.name"
        />
        </div>
        <div class="text-danger" v-if="nameError">
        <em>{{ nameError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Option Type</label>
        <select class="form-control" v-model="data.type"  @keyup="checkName()">
            <option value="">Select</option>
            <option value="mobile">Mobile Money</option>
            <option value="bank">Bank</option>
        </select>
        <div class="text-danger" v-if="typeError">
            <em>{{ typeError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Type Name</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Type Name"
            class="form-control"
            @keyup="checkName"
            @input="resetError()"
            v-model="data.type_name"
        />
        </div>
        <small class="text-primary">This can be MTN, Airtel, Stambic, Centinary etc</small>
        <div class="text-danger" v-if="typenameError">
            <em>{{ typenameError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Account Number</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Account Number"
            class="form-control"
            v-model="data.account_number"
             @keyup="checkName()"
             @input="resetError()"
        />
        </div>
        <div class="text-danger" v-if="accountError">
        <em>{{ accountError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Current Balance</label>
        <div class="input-group">
        <input
            type="number"
             @keyup="checkName()"
            placeholder="Enter Balance"
            class="form-control"
            v-model="data.balance"
        />
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
        @click="addPaymentOption('add-option-modal')"
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
    <!-- edit modal starts -->
            <b-modal id="edit-option-modal" hide-footer @hidden="resetEditCat">
              <template #modal-title> Edit Option {{ editdata.name }}</template>
              <div class="form-group mb-2">
                <label>Option Name</label>
                <div class="input-group">
                  <input
                    type="text"
                    class="form-control"
                    @input="resetError"
                    v-model="editdata.name"
                  />
                </div>
                <div class="text-danger" v-if="nameError">
                  <em>{{ nameError }}</em>
                </div>
              </div>              
            <div class="form-group mb-2">
                <label>Option Type</label>
                <select class="form-control" v-model="editdata.type"  @keyup="checkName()">
                    <option value="">Select</option>
                    <option value="mobile">Mobile Money</option>
                    <option value="bank">Bank</option>
                </select>
                <div class="text-danger" v-if="typeError">
                    <em>{{ typeError }}</em>
                </div>
            </div>
            <div class="form-group mb-2">
                <label>Type Name</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Type Name"
                    class="form-control"
                    @keyup="checkName"
                    @input="resetError()"
                    v-model="editdata.type_name"
                />
                </div>
                <small class="text-primary">This can be MTN, Airtel, Stambic, Centnary etc</small>
                <div class="text-danger" v-if="typenameError">
                    <em>{{ typenameError }}</em>
                </div>
            </div>
            <div class="form-group mb-2">
                <label>Account Number</label>
                <div class="input-group">
                <input
                    type="text"
                    placeholder="Enter Account Number"
                    class="form-control"
                    v-model="editdata.account_number"
                    @keyup="checkName()"
                    @input="resetError()"
                />
                <div class="text-danger" v-if="accountError">
                <em>{{ accountError }}</em>
                </div>
                </div>
            </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                  @click="closeModal('edit-option-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="editPaymentOption('edit-option-modal')"
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
              <template #modal-title> Delete Payment Option {{ deletedata.name }}</template>
              <div class="form-group mb-2">
                <div class="input-group">
                  <h5>
                    Do you want to delete this Payment Option? Click Proceed Button to delete.
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
                  @click="deletePaymentOption('delete-option-modal')"
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
             <!-- modal change default option starts -->
            <b-modal
              header-bg-variant="warning"
              header-text-variant="light"
              body-text-variant="warning"
              footer-bg-variant="warning"
              footer-text-variant="warning"
              id="default-option-modal"
              hide-footer
              @hidden="resetDefault()"
            >
              <template #modal-title> Set Payment Option {{ defaultOption.name }} as Default</template>
              <div class="form-group mb-2">
                <div class="input-group">
                  <h5>
                    Do you want to set this Payment Option as your default? Click Proceed Button to continue.
                  </h5>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('default-option-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="changeDefault('default-option-modal')"
                >
                  <i class="fa fa-cog"></i> Proceed
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Changing"
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
