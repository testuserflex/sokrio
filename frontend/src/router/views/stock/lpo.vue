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
    title: "Local Purchase Orders",
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
      title: "Local Purchase Orders",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Local Purchase Orders",
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
          key: "supplier",
          sortable: true,
          label: "SUPPLIER",
        },
        {
          key: "products",
          sortable: true,
          label: "PRODUCTS",
        },
        {
          key: "dateAdded",
          sortable: true,
          label: "DATE",
        },
        {
          key: "user",
          sortable: true,
          label: "USER",
        },
        {
          key: "paid_field",
          sortable: true,
          label: "PAID",
        },
        {
          key: "balance",
          sortable: true,
          label: "BALANCE",
        },
        {
          sortable: false,
          key: "actions",
          label: "ACTIONS",
        },
      ],

      delete:{
        id:'',
        supplier:''
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
    ...mapGetters("stock", ["LPOs", "SaveStatus"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.LPOs ? this.LPOs.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.LPOs ? this.LPOs.length : 1;
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
      FetchLPOs: "stock/fetchLPOs",
      Delete: "stock/deleteAllLPO",
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
    redirect(value) {
      this.$router.push(
        `/lpo/print/${btoa(value)}`
      );
    },
    redirect1(value) {
      this.$router.push(
        `/lpo/details/${btoa(value)}`
      );
    },

    SetdeleteData(data){
      this.delete.id = data.id,
      this.delete.supplier = data.supplier
    },

    DeleteLPO(modalId){
      this.Delete(this.delete.id);
      this.closeModal(modalId);
    },
    
    resetDelete(){
      this.delete.id = "",
      this.delete.supplier = ""
    },

    closeModal(modalId) {
      this.resetDelete();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.FetchLPOs();
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
                      <h4 class="card-title">All Generated LPOs</h4>
                  </div>
                  <div class="col-md-3" align="right" v-if="BranchData.BranchId != 0">
                      <router-link to="/lpo/new"><b-button variant="primary"><i class="fa fa-plus"></i> Generate New</b-button></router-link>
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
                :items="LPOs?LPOs:[]"
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
                 <template #cell(products)="data">
                  {{ data.item.items }} products
                </template>
                <template #cell(paid_field)="data">
                  {{ thousandSeperator(data?data.item.totAmt:0) }}
                </template>
                <template #cell(balance)="data">
                  {{ thousandSeperator(data?data.item.totAmt-data.item.paid:0) }}
                </template>
                <template #cell(actions)="data">
                  <b-button-group>
                  <b-button
                    size="sm"
                    variant="primary"
                    @click="redirect1(data.item.id)"
                    ><i class="fa fa-eye"></i> View</b-button
                  >
                  <b-button
                    size="sm"
                    variant="success"
                    @click="redirect(data.item.id)"
                    ><i class="fa fa-print"></i> Print</b-button
                  >
                  <b-button
                    size="sm"
                    variant="danger"
                    v-b-modal.delete-modal
                    @click="SetdeleteData(data.item)"
                    ><i class="fa fa-trash"></i> Delete</b-button
                  >
                  </b-button-group>
                </template>
              </b-table>
            </div>
            <Loader v-if="!LPOs" />
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

            <!-- Delete Model -->
            <b-modal id="delete-modal" hide-footer @hidden="resetDelete()">
              <template #modal-title>
                <h4 class="text-danger">Your are deleting this LPO </h4></template
              >
                
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  @click="closeModal('delete-modal')"
                >
                  <i class="fa fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-danger" @click="DeleteLPO('delete-modal')">
                  <i class="fa fa-trash"></i> Delete
                  <b-spinner
                    v-if="SaveStatus"
                    variant="light"
                    small
                    label="Submiting"
                  ></b-spinner>
                </button>
              </div>
            </b-modal>
          </div>
        </div>
      </div>
    </div>
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
