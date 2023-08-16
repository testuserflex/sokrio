<script>
import Layout from "../../layouts/main";
import appConfig from "@/app.config";
import { mapGetters, mapActions } from "vuex";
import moment from "moment";
/**
 * Invoice-detail component
 */
export default {
  page: {
    title: "Reconciliation Details",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: { Layout },
  data() {
    return {
      current_data: moment(new Date()).format("DD-MM-YYYY"),
      id: atob(this.$route.params.id),
    };
  },
  computed: {
    ...mapGetters("stock", ["ReconciliationReportDetails"]),
  },
  methods: {
    ...mapActions({
      FetchReport: "stock/fetchReconciliationReportDetails",
    }),
  },
  updated() {
    // setTimeout(() => {
    //   window.print();
    // }, 1000);
  },
  created() {
    this.FetchReport(this.id);
  },
};
</script>

<style scoped>
/* .print-font {
  font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
} */
</style>

<template>
  <Layout>
    <div class="row">
      <div class="col-lg-12">
        <div class="d-print-none">
          <div class="float-end">
            <a
              href="javascript:window.print()"
              class="btn btn-primary waves-effect waves-light me-1"
              ><i class="fa fa-print"></i> Print Report</a
            >
          </div>
        </div>
        <div class="d-print-none">
          <button @click="$router.go(-1)" class="btn btn-success mb-2">
            <i class="fas fa-arrow-circle-left"></i> Go Back
          </button>
        </div>

        <div class="bg-white p-2">
          <div class="print-font" style="padding-left: 15px">
            <table width="100%" class="mt-3">
              <b-spinner
                v-if="!ReconciliationReportDetails"
                class="m-0"
                variant="primary"
                role="status"
              ></b-spinner>
              <tr v-else>
                <td width="100%" style="text-align: right; padding-right: 15px !important">
                  <center>
                    <strong style="font-size: calc(1.2671875rem + 0.20625vw)">
                    {{
                      ReconciliationReportDetails ? ReconciliationReportDetails.branchx.name : "---"
                    }}</strong
                  >
                  <p>
                    <span>
                      {{
                        ReconciliationReportDetails ? ReconciliationReportDetails.branchx.address : "---"
                      }}</span
                    >
                    <br />
                    <strong>Tel: </strong
                    >{{
                      ReconciliationReportDetails
                        ? "+256" + ReconciliationReportDetails.contacts
                        : "---"
                    }}
                    <br />
                    <strong>Email: </strong
                    >{{ ReconciliationReportDetails ? ReconciliationReportDetails.branchx.email : "---" }}
                  </p>
                  </center>
                </td>
              </tr>
            </table>
            <hr style="border-style: dashed; width: 97%" />
            <h5
              style="
                text-align: center;
                font-weight: bold;
                font-family: Courier New, Courier, Lucida Sans Typewriter,
                  Lucida Typewriter, monospace;
              "
            >
              <span>
                <strong>
                  STOCK RECONCILIATION REPORT
                  <br />
                  By <br />
                  {{ ReconciliationReportDetails.user }} on
                  {{ ReconciliationReportDetails.date }}</strong
                ></span
              >
              <br />
            </h5>
            <h4>Items affected</h4>
            <center>
              <table width="100%" class="table" style="font-size: 15px">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Unit Quantity</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Variance</th>
                    <th>Variance Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(record, r) in ReconciliationReportDetails.items" :key="r">
                    <td>{{ r + 1 }}</td>
                    <td>{{ record.product }}</td>
                    <td>
                      <tr v-for="(pdt, p) in record.log" :key="p">
                        <td>{{pdt.actual_quantity}}</td>
                        <td>{{pdt.unit}}</td>
                      </tr>
                    </td>
                    <td>{{ record.from }} {{record.baseunit}}</td>
                    <td>{{ record.to }} {{record.baseunit}}</td>
                    <td>{{ record.diff }} {{record.baseunit}}</td>
                    <td>{{ record.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </center>
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
    <!-- end row -->
  </Layout>
</template>
