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
    title: "User Groups",
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
      title: "User Groups",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "User Groups",
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
          label: "GROUP NAME",
        },
        {
          key: "user.name",
          sortable: true,
          label: "ADDED By",
        },
        {
          key: "dateAdded",
          sortable: true,
          label: "DATE ADDED",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],
      existingRole: [],
      nameError: "",

      // Value from user input for adding Role
      data: {
        name: "",
      },
      // edit Role
      editdata: {
        name: "",
        id: "",
      },
      // delete Role
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
    ...mapGetters("users", ["Roles", "SaveStatus"]),
    ...mapGetters("auth", ["MyUserPerm"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.Roles ? this.Roles.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.Roles ? this.Roles.length : 1;
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
      FetchRoles: "users/fetchRoles",
      AddRole: "users/addRole",
      EditRole: "users/editRole",
      DeleteRole: "users/deleteRole",
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
      if (this.data.name != "") {
        this.existingRole = this.Roles.filter((role) => {
          return role.name.toUpperCase() == this.data.name.toUpperCase();
        });
        if (this.existingRole.length) {
          this.nameError = this.data.name + " exists already";
        } else {
          this.nameError = "";
        }
      } else {
        this.existingRole = [];
      }
    },

    addCategory(modalId) {
      this.modal_id = modalId;

      if (this.data.name === "") {
        this.nameError = "User Group name field is required";
        return;
      } else {
        this.nameError = "";
      }
      if (this.existingRole.length) {
        this.nameError = this.data.name + " exists already please";
        return;
      } else {
        this.nameError = "";
      }

      this.AddRole(this.data);
    },

    resetAddCat() {
      this.data.name = "";
      this.nameError = "";
      this.modal_id = "";
    },
    resetEditCat() {
      this.editdata.name = "";
      this.editdata.id = "";
      this.nameError = "";
      this.modal_id = "";
    },

    resetDeleteCat() {
      this.deletedata.name = "";
      this.deletedata.id = "";
      this.nameError = "";
      this.modal_id = "";
    },

    resetError() {
      this.nameError = "";
    },
    showEditModal(data) {
      this.editdata.name = data.item.name;
      this.editdata.id = data.item.id;
    },
    editCategory(modalId) {
      this.modal_id = modalId;
      this.EditRole(this.editdata);
    },
    showDeleteModal(data) {
      this.deletedata.name = data.item.name;
      this.deletedata.id = data.item.id;
    },
    deleteCategory(modalId) {
      this.modal_id = modalId;
      this.DeleteRole(this.deletedata);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchRoles();
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
                      <h4 class="card-title">All User Groups</h4>
                  </div>
                  <div v-if="MyUserPerm.add_user_roles == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
                      <b-button v-b-modal.add-category-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
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
                :items="Roles?Roles:[]"
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

                <template #cell(actions)="data">
                  <b-button
                    size="sm"
                    variant="primary"
                    v-b-modal.edit-category-modal
                    class="m-2"
                    v-if="MyUserPerm.edit_user_roles == 1"
                    @click="showEditModal(data)"
                    ><i class="fa fa-edit"></i> Edit</b-button
                  >
                  <b-button
                    size="sm"
                    variant="danger"
                    v-if="MyUserPerm.delete_user_roles == 1"
                    v-b-modal.delete-category-modal
                    @click="showDeleteModal(data)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                </template>
              </b-table>
            </div>
            <Loader v-if="!Roles"/>
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
    <b-modal id="add-category-modal" hide-footer @hidden="resetAddCat">
    <template #modal-title> Add New User Group </template>
    <div class="form-group mb-3">
        <label>Group Name</label>
        <div class="input-group">
        <input
            type="text"
            placeholder="User Group name e.g Cashier"
            class="form-control"
            @keyup="checkName"
            @input="resetError"
            v-model="data.name"
        />
        </div>
        <div class="text-danger" v-if="nameError">
        <em>{{ nameError }}</em>
        </div>
    </div>
    <div class="modal-footer">
        <button
        type="button"
        class="btn btn-danger"
        data-dismiss="modal"
        @click="closeModal('add-category-modal')"
        >
        <i class="fa fa-times"></i> Close
        </button>
        <button
        type="button"
        class="btn btn-primary"
        @click="addCategory('add-category-modal')"
        >
        <i class="fa fa-check"></i>
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
            <b-modal id="edit-category-modal" hide-footer @hidden="resetEditCat">
              <template #modal-title> Edit User group {{ editdata.name }}</template>
              <div class="form-group mb-3">
                <label>Group Name</label>
                <div class="input-group">
                  <input
                    type="text"
                    class="form-control"
                    @input="resetError"
                    v-model="editdata.name"
                    placeholder="User Group name e.g Cashier"
                  />
                </div>
                <div class="text-danger" v-if="nameError">
                  <em>{{ nameError }}</em>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                  @click="closeModal('edit-category-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="editCategory('edit-category-modal')"
                >
                <i class="fa fa-check"></i>
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

            <!-- modal delete category starts -->
            <b-modal
              header-bg-variant="danger"
              header-text-variant="light"
              body-text-variant="danger"
              footer-bg-variant="danger"
              footer-text-variant="danger"
              id="delete-category-modal"
              hide-footer
              @hidden="resetDeleteCat"
            >
              <template #modal-title> Deactivate User Group {{ deletedata.name }}</template>
              <div class="form-group mb-3">
                <div class="input-group">
                  <h5>
                    Do you want to Deactivate this User Group? Click Proceed Button to delete.
                  </h5>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('delete-category-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-danger"
                  @click="deleteCategory('delete-category-modal')"
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
</style> 
