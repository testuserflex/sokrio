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
    title: "Expired Items",
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
      title: "Expired Items",
      items: [
        {
          text: "Home",
          href: "#/",
        },
        {
          text: "Expired Items",
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
          key: "product.name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "category",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "days_field",
          sortable: true,
          label: "EXPIRED",
        },
        {
          key: "expiry",
          sortable: true,
          label: "EXPIRY DATE",
        },
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
          key: "product.name",
          sortable: true,
          label: "NAME",
        },
        {
          key: "category",
          sortable: true,
          label: "CATEGORY",
        },
        {
          key: "batch",
          sortable: true,
          label: "BATCH",
        },
        {
          key: "days_field",
          sortable: true,
          label: "EXPIRED",
        },
        {
          key: "expiry",
          sortable: true,
          label: "EXPIRY DATE",
        },
        {
          key: "branch.name",
          sortable: true,
          label: "BRANCH",
        },
      ],
      remove: {
        name: "",
        id: "",
        qty: "",
        product: "",
        status: "",
      },
      qtyError: "",
    };
  },
  computed: {
        // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("stock", ["ExpiredStock","SaveStatus"]),
    ...mapGetters("auth", ["MyUserPerm","branchid"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.ExpiredStock ? this.ExpiredStock.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ExpiredStock ? this.ExpiredStock.length : 1;
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
      fetchExpiredItems: "stock/fetchExpiredStock",
      RemoveDrug: "stock/removeExpired",
    }),
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },

    showExpiredModal(data) {
      this.remove.name = data.item.product.name;
      this.remove.product = data.item.product.id;
      this.remove.id = data.item.id;
      this.remove.status = 1;
    },
    resetModal() {
      this.remove.name = "";
      this.remove.id = "";
      this.remove.qty = "";
      this.remove.product = "";
      this.remove.status = 1;
    },
    resetqty() {
      this.remove.qty = "";
    },
    resetError(){
      this.qtyError = "";
    },
    removeexp(modalId) {
      if(this.remove.status == 1 && this.remove.qty == ""){
        this.qtyError = "Quantity Expired is required";
        return
      }
      else{
        this.qtyError = "";
      }
      if ((this.remove.status == 1 && this.remove.qty != "") || this.remove.status == 2) {
        this.modal_id = modalId;
        this.RemoveDrug(this.remove);
      }
    },
    //== close modal
    closeModal(modalId) {
      this.resetError();
      this.resetqty();
      this.resetModal();
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },
  },
  created() {
    this.fetchExpiredItems();
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
                <h5 class="text-danger">Products that expired</h5>
              </div>
              <div class="col-4">
                <div>
                  <router-link
                    :to="{
                      name: 'Stock',
                    }"
                  >
                    <b-button class="float-end" variant="primary"
                      ><i class="fa fa-undo"></i> Stock</b-button
                    >
                  </router-link>
                </div>
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
                :items="ExpiredStock ? ExpiredStock : []"
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
                <template #cell(days_field)="data">
                  {{ data.item.days * -1 }} days ago
                </template>
                <template #cell(actions)="data">
                  <b-button
                    size="sm"
                    variant="warning"
                    v-b-modal.expired-modal
                    @click="showExpiredModal(data)"
                    v-if="!data.item.removed && MyUserPerm.remove_expired_stock == 1"
                    ><i class="fa fa-minus"></i> Remove</b-button
                  >
                </template>
              </b-table>
            </div>
            <center>
                <Loader v-if="!ExpiredStock" />
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
    <b-modal id="expired-modal" hide-footer @hidden="resetModal">
      <template #modal-title>
        <h4 class="text-warning">Remove this <em><small>{{ remove.name }}</small></em> from Stock</h4></template
      >
      <div class="form-group">
        <label for="">Stock Status</label>
        <select class="form-control" v-model="remove.status" @change="resetqty()">
          <option value="1" selected>Was Still in Stock</option>
          <option value="2">Was Out of Stock</option>
        </select>
      </div>
      <div class="form-group mb-3" v-if="this.remove.status == 1">
        <label>Quantity Removed</label>
        <div class="input-group">
          <input
            type="number"
            class="form-control"
            v-model="remove.qty"
            placeholder="Quantity removed"
            @input="resetError()"
          />
        </div>
        <div class="text-danger" v-if="qtyError">
          <em>{{ qtyError }}</em>
        </div>
        <small class="text-primary">Quantity in Base/Smallest Unit</small>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="closeModal('expired-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-warning" @click="removeexp('expired-modal')">
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
