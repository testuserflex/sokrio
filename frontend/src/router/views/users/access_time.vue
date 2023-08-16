<script>
    // import axios from "axios";
    
    import Layout from "../../layouts/main";
    import PageHeader from "@/components/page-header";
    import appConfig from "@/app.config";
    import Loader from "@/components/widgets/preloader";
    import { mapGetters, mapActions, mapState} from "vuex";
    import Swal from "sweetalert2";
    
    /**
     * Advanced table component
     */
    export default {
      page: {
        title: "User access time",
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
          title: "User access time",
          items: [
            {
              text: "Home",
              href: "#/",
            },
            {
              text: "User access time",
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
              key: "user",
              sortable: true,
              label: "NAME",
            },
            {
              key: "time",
              sortable: true,
              label: "TIME",
            },
            {
              key: "days",
              sortable: true,
              label: "DAYS OF THE WEEK",
            },
            {
              key: "actions",
              sortable: true,
              label: "EDIT TIME",
            }
          ],
          data:{
            id:'',
            from_time:'',
            to_time:'',
          },
          updatedata:{
            day:'',
            id:''
          },
          FromError: "",
          ToError: "",
        };
      },
      computed: {
            // MAP STATE
        ...mapState("notification", [
          "type",
          "message",
          "show",
        ]),
        ...mapGetters("users", ["UserAccesstime","SaveStatus"]),
        ...mapGetters("auth", ["MyUserPerm"]),
    
        /**
         * Total no. of records
         */
    
        rows() {
          return this.UserAccesstime ? this.UserAccesstime.length : 1;
        },
    
        /**
         * Todo list of records
         */
      },
      mounted() {
        // Set the initial number of items
        this.totalRows = this.UserAccesstime ? this.UserAccesstime.length : 1;
      },
       watch: {
        show(newValue) {
          if (newValue == true) {
            this.closeModal(this.modal_id);
            if (this.type == "success") {
              Swal.fire("Success!", this.message.msg, "success");
            } else {
              Swal.fire("Error!", this.message.msg, "error");
            }
          }
        },
      },
      methods: {
        ...mapActions({
            FetchAccesstime: "users/fetchAccesstime",
            Changetime: "users/ChangeAccesstime",
            UpdateDays: "users/UpdateDays",            
        }),
        /**
         * Search the table data with search input
         */
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },
        resetModal() {
          this.from_time = "";
          this.to_time = "";
          this.FromError = "";
          this.ToError = ""
        },
        resetError(){
          this.FromError = "";
          this.ToError = ""
        },
        SubmitTime(modalId) {
          if(this.data.from_time == ""){
            this.FromError = "Starting time is required";
            return
          }
          else{
            this.FromError = "";
          }
          if(this.data.to_time == ""){
            this.ToError = "Ending time is required";
            return
          }
          else{
            this.ToError = "";
          }          
          this.Changetime(this.data);
          this.closeModal(modalId);
        },
        UpdateSetting(day, id){
          this.updatedata.day = day;
          this.updatedata.id = id;
          this.UpdateDays(this.updatedata);
        },
        showEditModal(data) {
          this.data.from_time = data.item.from_time;
          this.data.to_time = data.item.to_time;
          this.data.id = data.item.id;
        },
        //== close modal
        closeModal(modalId) {
          this.resetError();
          this.resetModal();
          this.$nextTick(() => {
            this.$bvModal.hide(modalId);
          });
        },
      },
      created() {
        this.FetchAccesstime();
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
                  <div class="col-8">
                    <h5 class="text-danger">User access time</h5>
                  </div>
                  <div class="col-4">
                    
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
                    :items="UserAccesstime ? UserAccesstime : []"
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
                    <template #cell(time)="data">
                      <h5><b>From:</b> {{ data.item.from_time }}<br><b>To:</b> {{ data.item.to_time }}</h5>
                    </template>
                    <template #cell(days)="data">
                        <table class="table">
                            <thead>
                                <tr width="100%">
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="accessdata in UserAccesstime" :key="accessdata.id">
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.mon==1"                              
                                        @change="UpdateSetting('Mon',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Mon',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.tue==1"                              
                                        @change="UpdateSetting('Tue',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Tue',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.wed==1"                              
                                        @change="UpdateSetting('Wed',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Wed',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.thu==1"                              
                                        @change="UpdateSetting('Thu',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Thu',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.fri==1"                              
                                        @change="UpdateSetting('Fri',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Fri',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.sat==1"                              
                                        @change="UpdateSetting('Sat',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Sat',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg mb-3">                          
                                    <input
                                    v-if="accessdata.sun==1"                              
                                        @change="UpdateSetting('Sun',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                        checked
                                    />
                                    <input
                                    v-else                              
                                        @change="UpdateSetting('Sun',accessdata.id)"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="SwitchCheckSizelg"
                                    />                           
                                    
                                    </div>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template #cell(actions)="data">
                      <b-button
                        size="sm"
                        variant="primary"
                        v-b-modal.edit_accesstime-modal
                        @click="showEditModal(data)"
                        v-if="MyUserPerm.edit_accesstime == 1"
                        ><i class="fa fa-pencil-alt"></i> Edit</b-button
                      >
                    </template>
                  </b-table>
                </div>
                <center>
                    <Loader v-if="!UserAccesstime" />
                  </center>
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
              </div>
            </div>
          </div>
        </div>
    
        <!-- modal mark as expired starts -->
        <b-modal id="edit_accesstime-modal" hide-footer @hidden="resetModal">
          <template #modal-title>
            <h4 class="text-warning">Edit this time</h4></template
          >
          <div class="form-group mb-3">
            <label>From</label>
            <div class="input-group">
              <input
                type="time"
                class="form-control"
                v-model="data.from_time"
                placeholder="From"
                @input="resetError()"
              />
            </div>
            <div class="text-danger" v-if="FromError">
              <em>{{ FromError }}</em>
            </div>
            <small class="text-primary">Put starting time</small>
          </div>
          <div class="form-group mb-3">
            <label>To</label>
            <div class="input-group">
              <input
                type="time"
                class="form-control"
                v-model="data.to_time"
                placeholder="From"
                @input="resetError()"
              />
            </div>
            <div class="text-danger" v-if="ToError">
              <em>{{ ToError }}</em>
            </div>
            <small class="text-primary">Put ending time</small>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="closeModal('edit_accesstime-modal')"
            >
              <i class="fa fa-times"></i> Close
            </button>
            <button type="button" class="btn btn-warning" @click="SubmitTime('edit_accesstime-modal')">
              <i class="fa fa-minus"></i> Confirm
              <b-spinner
                v-if="SaveStatus"
                variant="light"
                small
                label="Submiting"
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
    