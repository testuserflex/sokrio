<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapGetters,mapActions,mapState } from "vuex"
import Swal from "sweetalert2";
import moment from "moment";

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Stock Transfers",
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
      title: "Stock Transfers",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Stock Transfers Report",
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
          key: "source",
          sortable: true,
          label: "SOURCE",
        },
        {
          key: "source_type",
          sortable: true,
          label: "SOURCE TYPE",
        },
        {
          key: "destination",
          sortable: true,
          label: "DESTINATION",
        },
        {
          key: "products",
          sortable: true,
          label: "NO. OF PRODUCTS",
        },
        {
          key: "sentby",
          sortable: true,
          label: "SENT BY",
        },
        {
          key: "senton",
          sortable: true,
          label: "SENT ON",
        },
        {
          key: "status",
          sortable: true,
          label: "STATUS",
        },
        {
          key: "actions",
          sortable: false,
          label: "ACTION",
        },
      ],
      receive: {
          id: "",
          items: [],
          date: moment(new Date()).format("YYYY-MM-DD"),
          destination: 0,
          },
      products: [],
      amountError: "",

    };
  },
  computed: {
         // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
      ]),
    ...mapGetters("stocktransfer", ["SaveStatus","StockTransfers"]),
    ...mapGetters("general", ["BusinessSettings"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.StockTransfers ? this.StockTransfers.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.StockTransfers ? this.StockTransfers.length : 1;
  },
  updated() {
  },
  watch: {
    show(newValue) {
      if (newValue == true) {
        if (this.type == "success") {
            this.closeModal(this.modal_id);
          Swal.fire("Success!", this.message.msg, "success");
        } else {
          this.closeModal(this.modal_id);
          Swal.fire("Error!", this.message.msg, "error");
        }
      }
    },
  },
  methods: {
    ...mapActions({
      FetchTransfers: "stocktransfer/fetchStockTransfers",
      Receive: "stocktransfer/Receive",
      FetchSettings: "general/fetchBusinessSettings",

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

    ViewItems(data) {
        this.products = data.items;
    },
    ViewReceiveModal(data) {
        this.receive.id = data.id;
        this.receive.items = data.items;
    },
    ReceiveItems(modalId){
        this.modal_id = modalId;
        this.Receive(this.receive);
    },
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

  },
  created() {
    this.FetchTransfers();
    this.FetchSettings();
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
                <h4 class="card-title">Stock Transfer History</h4>
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
                  :items="StockTransfers?StockTransfers:[]"
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
                <template #cell(status)="data">
                  <div v-if="data.item.status=='Pending'" class="text-warning">
                    {{ data.item.status }}
                  </div>
                  <div v-else-if="data.item.status=='Partially Received'" class="text-primary">
                    {{ data.item.status }}
                  </div>
                  <div v-else class="text-success">
                    {{ data.item.status }}
                  </div>
                </template> 
                <template #cell(actions)="data">
                  <b-button-group>
                    <b-button
                        size="sm"
                        variant="primary"
                        v-b-modal.view-item-modal
                        @click="ViewItems(data.item)"
                        ><i class="fa fa-eye"></i> View</b-button
                    >
                    <b-button v-if="data.item.status=='Pending' && data.item.is_destination==1 || data.item.status=='Partially Received' && data.item.is_destination==1"
                        v-b-modal.receive-item-modal
                        variant="success"
                        size="sm"
                        @click="ViewReceiveModal(data.item)"
                        ><i class="fa fa-arrow-down"></i> Receive</b-button
                    >
                  </b-button-group>
                </template>
              </b-table>
            </div>
            <Loader  v-if="!StockTransfers"/>
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
 <!-- modal mark as view-item starts -->
    <b-modal id="view-item-modal" size="lg" hide-footer>
      <template #modal-title>
        <h4 class="text-warning">Items That were Transferred</h4></template
      >
      <table  class="table table-striped table-bordered table-responsive">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Item</th>
                  <th>Qty Sent</th>
                  <th>Qty Received</th>
                  <th>Buying Price</th>
                  <th>Date Received</th>
              </tr>
          </thead>
        <tbody>
        <tr v-for="(det,d) in products" :key="d" width="100%">
            <td>{{ d+1 }}</td>
            <td>{{ det.product }}</td>
            <td>{{ det.qty_sent }} {{ det.unit }}</td>
            <td>{{ det.qty_received }}</td>
            <td>{{ det.bPrice?det.bPrice:0 }}</td>
            <td>{{ det.receivedOn }}</td>
        </tr>
        </tbody>
        </table>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          data-dismiss="modal"
          @click="closeModal('view-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->

    <!-- modal receive starts -->
    <b-modal id="receive-item-modal" hide-footer>
      <template #modal-title>
        <h4 class="text-warning">Receive Items</h4></template
      >
      <table  class="table table-striped table-bordered table-responsive">
          <thead>
              <tr width="100%">
                  <th width="10%">No</th>
                  <th width="40%">Item</th>
                  <th width="25%">Qty Sent</th>
                  <th width="25%">Qty Received</th>
              </tr>
          </thead>
        <tbody>          
        <tr v-for="(det,index) in receive.items" :key="index" width="100%">
            <td width="10%">{{ index+1 }}</td>
            <td width="40%">{{ det.product }}</td>
            <td width="25%">{{ det.qty_sent }} {{ det.unit }}</td>
            <td width="25%"><input type="number" class="form-control" v-model="det.qt_rcv" step="any" placeholder="Qty Received"></td>
        </tr>
        </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Receive Date</label>
                    <input type="date" v-model="receive.date" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Destination <span class="text-danger">*</span></label>
                    <div class="input-group">
                    <select class="form-control" v-model="receive.destination">
                        <option value="0">Shop</option>
                        <option value="1" v-if="BusinessSettings?BusinessSettings.store==1:false">Store</option>
                    </select>
                    </div>
                </div>
            </div>
        </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="closeModal('receive-item-modal')"
        >
          <i class="fa fa-times"></i> Close
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="ReceiveItems('receive-item-modal')"
        >
          <i class="fa fa-arrow-down"></i> Receive
        </button>
      </div>
    </b-modal>
    <!-- modal ends -->
  </Layout>
</template>

<style>
.btn-primary,.page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>
