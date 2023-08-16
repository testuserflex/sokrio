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
        title: "Rejected Spoilt Stock",
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
          title: "Rejected Spoilt Stock ",
          items: [
            {
              text: "Home",
              href: "/",
            },
            {
              text: "Rejected Spoilt Stock",
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
              label: "Date",
            },
            {
              key: "product.product_name",
              sortable: true,
              label: "PRODUCT",
            },
    
            {
              key: "qty",
              sortable: true,
              label: "QTY",
            },
    
            {
              key: "buyingprice",
              sortable: true,
              label: "BUYING PRICE",
            },
    
            {
              key: "batch",
              sortable: true,
              label: "BATCH",
            },
            {
              key: "reason",
              sortable: true,
              label: "REASON",
            },
            {
              key: "user.name",
              sortable: true,
              label: "ADDED By",
            },
            // {
            //   key: "dateAdded",
            //   sortable: true,
            //   label: "DATE ADDED",
            // },
            {
              sortable: false,
              key: "actions",
              label: "ACTIONS",
            },
          ],
          fields1: [
            {
              key: "index",
              sortable: true,
              label: "S/N",
            },
            {
              key: "date",
              sortable: true,
              label: "Date",
            },
            {
              key: "product.product_name",
              sortable: true,
              label: "PRODUCT",
            },
    
            {
              key: "qty",
              sortable: true,
              label: "QTY",
            },
    
            {
              key: "buyingprice",
              sortable: true,
              label: "BUYING PRICE",
            },
    
            {
              key: "batch",
              sortable: true,
              label: "BATCH",
            },
            {
              key: "reason",
              sortable: true,
              label: "REASON",
            },
            {
              key: "user.name",
              sortable: true,
              label: "ADDED By",
            },
            {
              key: "branch.name",
              sortable: true,
              label: "BRANCH",
            },
          ],
          FBatches: [],
            // delete unit
            deletedata: {
                name: "",
                id: "",
            },
          resetDelete() {
            this.deletedata.name = "";
            this.deletedata.id = "";
            this.modal_id = "";
            },
          //== close modal
          closeModal(modalId) {
            this.$nextTick(() => {
                this.$bvModal.hide(modalId);
            });
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
        ...mapGetters("stock", ["SaveStatus", "RejectedSpoilt"]),
        ...mapGetters("products", ["Products","Batches"]),
        ...mapGetters('auth',["MyUserPerm","branchid","Branches"]),
    
        /**
         * Total no. of records
         */
    
        rows() {
          return this.RejectedSpoilt ? this.RejectedSpoilt.length : 1;
        },
    
        /**
         * Todo list of records
         */
      },
      mounted() {
        // Set the initial number of items
        this.totalRows = this.RejectedSpoilt ? this.RejectedSpoilt.length : 1;
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
          FetchProducts: "products/fetchProducts",
          FetchBatches: "products/fetchBatches",
          FetchRejectedSpoilt: "stock/fetchRejectedSpoilt",
          DeleteSpoilt: "stock/deleteSpoilt",
        }),
        /**
         * Search the table data with search input
         */
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },
      },
      created() {
        this.FetchRejectedSpoilt();
        this.FetchProducts();
        this.FetchBatches();
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
                    <h4 class="card-title">Rejected Spoilt Stock</h4>
                  </div>
                  <div class="col-md-3" align="right">
                    <router-link :to="'/stock/spoilt'">
                    <b-button variant="primary">Confirmed Spoilt</b-button>
                    </router-link>
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
                      :items="RejectedSpoilt?RejectedSpoilt:[]"
                      :fields="branchid==0?fields1:fields"
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
    <!--                  <b-button-->
    <!--                      size="sm"-->
    <!--                      variant="primary"-->
    <!--                      v-b-modal.edit-product-modal-->
    <!--                      class="m-2"-->
    <!--                      @click="showEditModal(data)"-->
    <!--                  ><i class="fa fa-edit"></i></b-button-->
    <!--                  >-->
                      <b-button v-if="data.item.expired!=1 && MyUserPerm.delete_spoilt_stock == 1"
                          size="sm"
                          variant="danger"
                          v-b-modal.delete-product-modal
                          @click="showDeleteModal(data)"
                      ><i class="fa fa-trash"></i></b-button
                      >
                    </template>
                  </b-table>
                </div>
                <Loader  v-if="!RejectedSpoilt"/>
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
          <template #modal-title> Delete Product</template>
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
                @click="deleteSpoilt('delete-product-modal')"
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
    .btn-primary { background-color:#FAB031 !important;border-color:#FAB031 !important;}
    </style>
    