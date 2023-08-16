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
    title: "Supplier",
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
      title: "Supplier",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Supplier",
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
          key: "name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "phone",
          sortable: true,
          label: "CONTACT",
        },
        {
          key: "address",
          sortable: true,
          label: "ADDRESS",
        },
        {
          key: "email",
          sortable: true,
          label: "EMAIL",
        },
        {
          key: "credit",
          sortable: true,
          label: "CREDIT LIMIT",
        },
        {
          key: "user.name",
          sortable: true,
          label: "ADDED By",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingSupplier: [],
      nameError: "",
      phoneError: "",

      // Value from user input for adding option
      data: {
        name: "",
        credit_limit: 0,
        contact: "",
        email: "",
        address: "",
      },
      // edit option
      editdata: {
        name: "",
        credit_limit: 0,
        contact: "",
        email: "",
        address: "",
        id: "",
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
    ...mapGetters("stock", ["Suppliers", "SaveStatus"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Suppliers ? this.Suppliers.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Suppliers ? this.Suppliers.length : 1;
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
      FetchSuppliers: "stock/fetchSuppliers",
      AddSupplier: "stock/addSupplier",
      EditSupplier: "stock/editSupplier",
      DeleteSupplier: "stock/deleteSupplier",
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

    checkName() {
      //when creating a new one
      if (this.data.name != "" && this.data.contact !="") {
        this.existingSupplier = this.Suppliers.filter((supplier) => {
          return (supplier.name.toUpperCase() == this.data.name.toUpperCase()) 
          && (supplier.contact == this.data.contact);
        });
        if (this.existingSupplier.length) {
          this.nameError = this.data.name + " exists already";
        } else {
          this.nameError = "";
        }
      } else {
        this.existingSupplier = [];
      }
    },

    addSupplier(modalId) {
      this.modal_id = modalId;

      if (this.data.name === "") {
        this.nameError = "Supplier name field is required";
        return;
      } else {
        this.nameError = "";
      }
      if (this.existingSupplier.length) {
        this.nameError = this.data.name + " exists already please";
        return;
      } else {
        this.nameError = "";
      }

      this.AddSupplier(this.data);
    },

    resetAdd() {
      this.data.name = "";
      this.data.contact = "";
      this.data.email = "";
      this.data.address = "";
      this.data.credit_limit = 0;
      this.modal_id = "";
    },
    resetEditCat() {
      this.editdata.name = "";
      this.editdata.email = "";
      this.editdata.contact = "";
      this.editdata.address = "";
      this.editdata.credit_limit = 0;
      this.editdata.id = "";
      this.modal_id = "";
    },

    resetDelete() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.modal_id = "";
    },

    resetError() {
      this.nameError = "";
      this.phoneError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.contact = data.item.phone;
      this.editdata.address = data.item.address;
      this.editdata.email = data.item.email;
      this.editdata.credit_limit = data.item.credit_limit;
      this.editdata.id = data.item.id;
    },
    editSupplier(modalId) {
      this.modal_id = modalId;
      this.EditSupplier(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.name;
      this.deletedata.id = data.item.id;
    },
    deleteSupplier(modalId) {
      this.modal_id = modalId;
      this.DeleteSupplier(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchSuppliers();
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
                      <h4 class="card-title">All Suppliers</h4>
                  </div>
                  <div v-if="MyUserPerm.add_suppliers == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
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
                :items="Suppliers?Suppliers:[]"
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
                <template #cell(credit)="data">
                  {{ thousandSeperator(data.item.credit_limit) }}
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
                    v-if="MyUserPerm.edit_suppliers == 1"
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-option-modal
                    class="m-2"
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    v-if="MyUserPerm.delete_suppliers == 1"
                    size="sm"
                    variant="danger"
                    v-b-modal.delete-option-modal
                    @click="showDeleteModal(data)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                </template>
              </b-table>
            </div>
            <Loader v-if="!Suppliers" />
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
    <b-modal id="add-option-modal" hide-footer @hidden="resetAdd">
    <template #modal-title> Add New Supplier </template>
    <div class="form-group mb-2">
        <label>Supplier Name</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Supplier Name"
            class="form-control"
            @input="resetError()"
            v-model="data.name"
            required
        />
        </div>
        <div class="text-danger" v-if="nameError">
        <em>{{ nameError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Contact</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Phone Number"
            class="form-control"
            v-model="data.contact"
            @input="resetError(), checkName()"
        />
      </div>
      <div class="text-danger" v-if="phoneError">
      <em>{{ phoneError }}</em>
      </div>        
    </div>
    <div class="form-group mb-2">
        <label>Address</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Location"
            class="form-control"
            v-model="data.address"
        />
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Email</label>
        <div class="input-group">
        <input
            type="email"
            placeholder="Enter Email Address"
            class="form-control"
            v-model="data.email"
        />
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Credit Limit</label>
        <div class="input-group">
        <input
            type="number"
            placeholder="Enter Credit Limit"
            class="form-control"
            v-model="data.credit_limit"
        />
        </div>
        <small class="text-primary">Maximum Allowable Balance</small>
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
        @click="addSupplier('add-option-modal')"
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
        <label>Supplier Name</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Supplier Name"
            class="form-control"
            @input="resetError()"
            v-model="editdata.name"
        />
        </div>
        <div class="text-danger" v-if="nameError">
        <em>{{ nameError }}</em>
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Contact</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Phone Number"
            class="form-control"
            v-model="editdata.contact"
             @input="resetError(), checkName()"
        />
      </div>
        <div class="text-danger" v-if="phoneError">
        <em>{{ phoneError }}</em>
        </div>        
    </div>
    <div class="form-group mb-2">
        <label>Address</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="Enter Location"
            class="form-control"
            v-model="editdata.address"
        />
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Email</label>
        <div class="input-group">
        <input
            type="email"
            placeholder="Enter Email Address"
            class="form-control"
            v-model="editdata.email"
        />
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Credit Limit</label>
        <div class="input-group">
        <input
            type="number"
            placeholder="Enter Credit Limit"
            class="form-control"
            v-model="editdata.credit_limit"
        />
        </div>
        <small class="text-primary">Maximum Allowable Balance</small>
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
                  @click="editSupplier('edit-option-modal')"
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
              @hidden="resetDelete"
            >
              <template #modal-title> Delete Supplier {{ deletedata.name }}</template>
              <div class="form-group mb-2">
                <div class="input-group">
                  <h5>
                    Do you want to delete this Supplier? Click Proceed Button to delete.
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
                  @click="deleteSupplier('delete-option-modal')"
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
