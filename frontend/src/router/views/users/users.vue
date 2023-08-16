<script>
  import Layout from "../../layouts/main";
  import PageHeader from "@/components/page-header";
  import appConfig from "@/app.config";
  import Loader from "@/components/widgets/preloader";
  import { mapState,mapGetters,mapActions } from "vuex"
  import { ModelListSelect } from "vue-search-select";
  import Swal from "sweetalert2";
  
  /**
   * Advanced table component
   */
  export default {
    page: {
      title: "System Users",
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
        title: "System Users",
        items: [
          {
            text: "Home",
            href: "/",
          },
          {
            text: "System Users",
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
            key: "names",
            sortable: true,
            label: "NAME",
          },
          {
            key: "role.role",
            sortable: true,
            label: "ROLE",
          },
          {
            key: "phone1",
            sortable: true,
            label: "Contact",
          },

          {
            key: "uemail",
            sortable: true,
            label: "Email",
          },
          {
            key: "uname",
            sortable: true,
            label: "USERNAME",
          },
          {
            sortable: false,
            key: "actions",
            label: "ACTIONS",
          },
        ],
        existingUser: [],
        nameError: "",
        phoneError: "",
        roleError: "",
        emailError: "",
        passwordError: "",
        usernameError: "",
  
        // Value from user input for adding unit
        data: {
          name: "",
          username: "",
          contact: "",
          role: "",
          email: "",
          password: "",
        },
        // edit unit
        editdata: {
          name: "",
          username: "",
          contact: "",
          role: "",
          email: "",
          password: "",
          id: "",
        },
        // delete unit
        deletedata: {
          name: "",
          id: "",
        },
        editPass:{
        id: "",
        name: "",
        password: "",
      }
      };
    },
    computed: {
      // MAP STATE
      ...mapState("notification", [
        "type",
        "message",
        "show",
      ]),
      ...mapGetters("users", ["SaveStatus", "Users","Roles"]),
      ...mapGetters("auth", ["MyUserPerm","User"]),
  
      /**
       * Total no. of records
       */
  
      rows() {
        return this.Users ? this.Users.length : 1;
      },
  
      /**
       * Todo list of records
       */
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.Users ? this.Users.length : 1;
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
        FetchUsers: "users/fetchUsers",
        FetchRoles: "users/fetchRoles",
        AddUser: "users/addUser",
        EditUser: "users/editUser",
        DeleteUser: "users/deleteUser",
        ChangePassword: "users/changePassword",
      }),
      /**
       * Search the table data with search input
       */
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length;
        this.currentPage = 1;
      },
  
      
      
     
      validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (re.test(email)) {
            this.emailError = "";
              
          } else {
            this.emailError = 'Please enter a valid email address';
             
          }
      },
  
      addUser(modalId) {
        this.modal_id = modalId;
  
        if (this.data.name === "") {
          this.nameError = "User name field is required";
          return;
        } else {
          this.nameError = "";
        }

        if (this.data.contact === "") {
          this.phoneError = "Contact field is required";
          return;
        } else {
          this.phoneError = "";
        }
        if(this.data.email != ""){
         this.validateEmail(this.data.email);
         if(this.emailError != ""){
          return;
         }
        }

        if (this.data.username === "") {
          this.usernameError = "User Name field is required";
          return;
        }else if (this.data.username.toString().length <4) {
        this.usernameError = "User Name field should be at least four characters";
        return;
        } else {
          this.usernameError = "";
        }

        if (this.data.role === "") {
          this.roleError = "User Role field is required";
          return;
        } else {
          this.roleError = "";
        }

        if (this.data.password === "") {
          this.passwordError = "Password field is required";
          return;
        }else if (this.data.password.toString().length <5) {
        this.passwordError = "User password field should be at least five characters";
        return;
      } else {
          this.passwordError = "";
        }
  
        this.AddUser(this.data);
      },
  
      resetAdd() {
        this.data.name = "";
        this.data.username= "";
        this.data.contact= "";
        this.data.role= "";
        this.data.email= "";
         this.data.password= "";
        this.modal_id = "";
        this.resetError();

      },
      resetEdit() {
        this.editdata.name = "";
        this.editdata.name = "";
        this.editdata.username= "";
        this.editdata.contact= "";
        this.editdata.role= "";
        this.editdata.email= "";
        this.editdata.id = "";
        this.modal_id = "";
        this.resetError();
      },
  
      resetDelete() {
        this.deletedata.name = "";
        this.deletedata.id = "";
        this.modal_id = "";
      },
  
      resetError() {
        this.nameError = "";
        this.usernameError = "";
        this.phoneError = "";
        this.passwordError = "";
        this.roleError = "";
        this.emailError = "";
      },
      showEditModal(data) {
        this.editdata.name = data.item.names;
      this.editdata.names= data.item.names;
      this.editdata.username = data.item.uname;
      this.editdata.contact = data.item.phone1;
      this.editdata.email = data.item.uemail ? data.item.uemail: "";
      this.editdata.role = data.item.role.id;
      this.editdata.id = data.item.id;
      },
      showPassModal(data) {
      this.editPass.name = data.item.names;
      this.editPass.password = ""
      this.editPass.id = data.item.id;
    },
      editUser(modalId) {
        this.modal_id = modalId;
        if (this.editdata.name === "") {
        this.nameError = "User name field is required";
        return;
        }else if (this.editdata.username.toString().length <4) {
        this.usernameError = "User Name field should be at least four characters";
        return;
        } else {
          this.usernameError = "";
        } 
        if (this.editdata.contact === "") {
          this.phoneError = "User contact field is required";
          return;
        } else {
          this.phoneError = "";
        }
        
        if(this.editdata.email != ""){
         this.validateEmail(this.editdata.email);
         if(this.emailError != ""){
          return;
         }
        }

        if (this.editdata.username === "") {
          this.usernameError = "User username field is required";
          return;
        } else {
          this.usernameError = "";
        }
       
        
        if (this.editdata.role === "") {
          this.roleError = "User role field is required";
          return;
        } else {
          this.roleError = "";
        }
        this.EditUser(this.editdata);
      },

      changePassword(modalId) {
      this.modal_id = modalId;
      if (this.editPass.password === "") {
        this.passwordError = "User password field is required";
        return;
      } else if (this.editPass.password.toString().length <5) {
        this.passwordError = "User password field should be at least five characters";
        return;
      } else {
        this.passwordError = "";
      }

      this.ChangePassword(this.editPass);
    },
      showDeleteModal(data) {
        this.deletedata.name = data.item.name;
        this.deletedata.id = data.item.id;
      },
      deleteUser(modalId) {
        this.modal_id = modalId;
        this.DeleteUser(this.deletedata);
      },
  
      //== close modal
      closeModal(modalId) {
        this.$nextTick(() => {
          this.$bvModal.hide(modalId);
        });
      },
    },
    created() {
      this.FetchUsers();
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
                        <h4 class="card-title">All System Users</h4>
                    </div>
                    <div v-if="MyUserPerm.add_users == 1 && BranchData.BranchId != 0" class="col-md-3" align="right">
                        <b-button v-b-modal.add-user-modal variant="primary"><i class="fa fa-plus"></i> Add New</b-button>
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
                  :items="Users?Users:[]"
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
                      variant="info"
                      v-b-modal.pass-change-modal
                      class="m-2"
                      @click="showPassModal(data)"
                      v-if="MyUserPerm.edit_users == 1 "
                      ><i class="fa fa-lock"></i> Password
                    </b-button>
                    <b-button
                      size="sm"
                      variant="primary"
                      v-b-modal.edit-user-modal
                      class="m-2"
                      v-if="MyUserPerm.edit_users == 1 && User.id != data.item.id"
                      @click="showEditModal(data)"
                      ><i class="fa fa-edit"></i> Edit</b-button
                    >                    
                    <b-button
                      size="sm"
                      variant="danger"
                      v-if="MyUserPerm.delete_users == 1 && User.id != data.item.id && data.item.status == 1"
                      v-b-modal.delete-user-modal
                      @click="showDeleteModal(data)"
                      ><i class="fa fa-trash"></i> Delete
                    </b-button>
                    <b-button
                      size="sm"
                      variant="info"
                      v-if="MyUserPerm.delete_users == 1 && User.id != data.item.id && data.item.status == 0"
                      v-b-modal.delete-user-modal
                      @click="showDeleteModal(data)"
                      ><i class="fa fa-check"></i> Activate
                    </b-button>
                  </template>
                </b-table>
              </div>
              <Loader  v-if="!Users"/>
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
      <b-modal id="add-user-modal" hide-footer @hidden="resetAdd">
      <template #modal-title> Add New User </template>
      <div class="form-group mb-3">
          <label>Full Name <span class="text-danger">*</span></label>
          <div class="input-group">
          <input
              type="text"
              placeholder="Enter Full Name"
              class="form-control"
              @input="resetError()"
              v-model="data.name"
          />
          </div>
          <div class="text-danger" v-if="nameError">
          <em>{{ nameError }}</em>
          </div>
      </div>
      <div class="form-group mb-3">
          <label>Contact <span class="text-danger">*</span></label>
          <div class="input-group">
          <input
              type="text"
              placeholder="Enter Phone Number"
              class="form-control"
              @input="resetError()"
              v-model="data.contact"
          />
          </div>
          <div class="text-danger" v-if="phoneError">
          <em>{{ phoneError }}</em>
          </div>
      </div>
      <div class="form-group mb-3">
          <label>Email</label>
          <div class="input-group">
          <input
              type="email"
              placeholder="Enter Email Address"
              class="form-control"
              v-model="data.email"
              @input="resetError()"
          />
          </div>
          <div class="text-danger" v-if="emailError">
              <em>{{ emailError }}</em>
          </div>
      </div>
      <div class="form-group mb-3">
          <label>Username <span class="text-danger">*</span></label>
          <div class="input-group">
          <input
              type="text"
              placeholder="Enter Username"
              class="form-control"
              @input="resetError()"
              v-model="data.username"
          />
          </div>
          <div class="text-danger" v-if="usernameError">
          <em>{{ usernameError }}</em>
          </div>
      </div>
      <div class="form-group mb-3">
          <label>Role <span class="text-danger">*</span></label>
          <model-list-select
              :list="Roles ? Roles : []"
              v-model="data.role"
              option-value="id"
              option-text="name"
              placeholder="Select Role"
              @input="resetError()"
            >
          </model-list-select>
              
          <div class="text-danger" v-if="roleError">
          <em>{{ roleError }}</em>
          </div>
      </div>
      <div class="form-group mb-3">
          <label>Password <span class="text-danger">*</span></label>
          <div class="input-group">
          <input
              type="password"
              placeholder="Enter Account Password"
              class="form-control"
              @input="resetError()"
              v-model="data.password"
          />
          </div>
          <small class="text-warning">At least 5 characters</small>
          <div class="text-danger" v-if="passwordError">
          <em>{{ passwordError }}</em>
          </div>
      </div>
      <div class="modal-footer">
          <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="closeModal('add-user-modal')"
          >
          <i class="fa fa-times"></i> Close
          </button>
          <button
          type="button"
          class="btn btn-primary"
          @click="addUser('add-user-modal')"
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
      <!-- edit modal starts -->
              <b-modal id="edit-user-modal" hide-footer @hidden="resetEdit">
                <template #modal-title> Edit User {{ editdata.names }}</template>
                  <div class="form-group mb-3">
                      <label>Full Name <span class="text-danger">*</span></label>
                      <div class="input-group">
                      <input
                          type="text"
                          placeholder="Enter Full Name"
                          class="form-control"
                          @input="resetError()"
                          v-model="editdata.name"
                      />
                      </div>
                      <div class="text-danger" v-if="nameError">
                      <em>{{ nameError }}</em>
                      </div>
                  </div>
                  <div class="form-group mb-3">
                      <label>Contact <span class="text-danger">*</span></label>
                      <div class="input-group">
                      <input
                          type="text"
                          placeholder="Enter Phone Number"
                          class="form-control"
                          @input="resetError()"
                          v-model="editdata.contact"
                      />
                      </div>
                      <div class="text-danger" v-if="phoneError">
                      <em>{{ phoneError }}</em>
                      </div>
                  </div>
                  <div class="form-group mb-3">
                      <label>Email</label>
                      <div class="input-group">
                      <input
                          type="email"
                          placeholder="Enter Email Address"
                          class="form-control"
                          v-model="editdata.email"
                          @input="resetError()"
                      />
                      </div>
                      <div class="text-danger" v-if="emailError">
                          <em>{{ emailError }}</em>
                      </div>
                  </div>
                  <div class="form-group mb-3">
                      <label>Username <span class="text-danger">*</span></label>
                      <div class="input-group">
                      <input
                          type="text"
                          placeholder="Enter Username"
                          class="form-control"
                          @input="resetError()"
                          v-model="editdata.username"
                      />
                      </div>
                      <div class="text-danger" v-if="usernameError">
                      <em>{{ usernameError }}</em>
                      </div>
                  </div>
                  <div class="form-group mb-3">
                      <label>Role <span class="text-danger">*</span></label>
                          <model-list-select
                                  :list="Roles ? Roles : []"
                                  v-model="editdata.role"
                                  option-value="id"
                                  option-text="name"
                                  class="mb-3"
                                  placeholder="Select User Role"
                                  @input="resetError()"
                                >
                                </model-list-select>
                      <div class="text-danger" v-if="roleError">
                      <em>{{ roleError }}</em>
                      </div>
                  </div>
                  
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-danger"
                      data-dismiss="modal"
                      @click="closeModal('edit-user-modal')"
                    >
                      <i class="fa fa-times"></i> Close
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary"
                      @click="editUser('edit-user-modal')"
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
      <!-- password modal starts -->
            <b-modal id="pass-change-modal" hide-footer @hidden="resetEdit">
              <template #modal-title> Edit User {{ editPass.name }}</template>
          
                <div class="form-group mb-3">
                    <label>Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                    <input
                        type="password"
                        placeholder="Enter Account Password"
                        class="form-control"
                        @input="resetError()"
                        v-model="editPass.password"
                    />
                    </div>
                    <small class="text-warning">At least 5 characters</small>
                    <div class="text-danger" v-if="passwordError">
                    <em>{{ passwordError }}</em>
                    </div>
                </div>
   
    
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                  @click="closeModal('pass-change-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="changePassword('pass-change-modal')"
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
  
              <!-- modal delete user starts -->
              <b-modal
                header-bg-variant="danger"
                header-text-variant="light"
                body-text-variant="danger"
                footer-bg-variant="danger"
                footer-text-variant="danger"
                id="delete-user-modal"
                hide-footer
                @hidden="resetDelete"
              >
                <template #modal-title> Delete User {{ deletedata.name }}</template>
                <div class="form-group mb-3">
                  <div class="input-group">
                    <h5>
                      Do you want to delete this User? Click Proceed Button to delete.
                    </h5>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                    @click="closeModal('delete-user-modal')"
                  >
                    <i class="fa fa-times"></i> Close
                  </button>
                  <button
                    type="button"
                    class="btn btn-danger"
                    @click="deleteUser('delete-user-modal')"
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