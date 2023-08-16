<script>
import Layout from "../../layouts/main";
import appConfig from "@/app.config";
import { mapGetters, mapActions } from "vuex";
import moment from "moment";
import Loader from "@/components/widgets/preloader";
/**
 * Invoice-detail component
 */
export default {
  page: {
    title: "LPO Details",
    meta: [{ name: "description", content: appConfig.description }],
  },
  components: { Layout,Loader },
  data() {
    return {
      current_data: moment(new Date()).format("DD-MM-YYYY"),
      id: atob(this.$route.params.id),
    };
  },
  computed: {
    ...mapGetters("stock", ["LPO"]),
    // ...mapGetters("setting", ["GeneralSettings"]),
    ...mapGetters("auth", ["business","branch"]),
  },
  methods: {
    ...mapActions({
      FetchLPO: "stock/fetchLPODetails",
      // FetchGeneralSettings: "setting/fetchGeneralSettings",
    }),
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
  },
  updated() {
    setTimeout(() => {
      window.onafterprint = window.close;
      window.print();
    }, 1000);
  },

  created() {
    this.FetchLPO(this.id);
    // this.FetchGeneralSettings();
  },
};
</script>


<template>
  <Layout>
    <div class="row">
      <div class="col-lg-12">
        <div class="d-print-none">
          <div class="float-end">
            <a
              href="javascript:window.print()"
              class="btn btn-primary btn-sm waves-effect waves-light me-1"
              ><i class="fa fa-print"></i> RePrint LPO</a
            >
          </div>
        </div>
        <div class="d-print-none">
          <button @click="$router.go(-1)" class="btn btn-success btn-sm mb-2">
            <i class="fas fa-arrow-circle-left"></i> Go Back
          </button>
        </div>

        <div class="bg-white">
          <div class="print-font p-2">
              <Loader v-if="!LPO" />
              <div class="mt-3" v-else>
                  <center>
                    <div>
                    <strong style="font-size: calc(1.2671875rem + 0.20625vw)">
                    {{
                      business
                    }}
                    </strong>
                    </div>
                  <p>
                    <span>
                      {{
                        LPO ? LPO.branch.address : "---"
                      }}</span
                    >
                    <br />
                    <strong>Tel: </strong
                    >{{
                      LPO
                        ?LPO.contacts
                        : "---"
                    }}
                    <br />
                    <strong>Email: </strong
                    >{{ LPO ? LPO.branch.email : "---" }}
                  </p>
                  </center>
              </div>
            <!-- <hr style="border-style: dashed; width: 97%" /> -->
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
                  LOCAL PURCHASE ORDER
                  <br />
                  </strong
                >
                </span
              >
              <span>Supplier: {{ LPO?LPO.supplier:"---" }}</span>
              <br />
            </h5>
            <h4>Items Ordered </h4>
            
            <center>
              <table width="100%" style="font-size:15px" class="mTable mb-3">
                <thead>
                  <tr width="100%" >
                    <th width="5%">S/N</th>
                    <th width="35%" >Item</th>
                    <th width="20%" >Qty</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(record, r) in LPO?LPO.all:'---'" :key="r">
                    <td width="5%">{{ r + 1 }}</td>
                    <td width="35%" >{{ record.product }}</td>
                    <td width="20%" >{{ record.qty }} {{ record.unit }}</td>
                  </tr>
                </tbody>
              </table>
              <hr style="border-style: dashed; width: 97%" />
              <h2><b>Total Amount:</b> {{ LPO?(LPO.totAmt).toLocaleString():0 }}/=</h2>
              <h5><b>Total Paid:</b> {{ LPO?(LPO.paid).toLocaleString():0 }}/=</h5>
              <h5 class="mb-4"><b>Total Balance:</b> {{ LPO?(LPO.totAmt-LPO.paid).toLocaleString():0 }}/=</h5>
              <span class="mag">
                 <strong> LPO Prepared By
                  {{ LPO?LPO.user:"---" }} on
                  {{ LPO?LPO.dateAdded:"---" }}</strong>
              </span>
            </center>
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
    <!-- end row -->
  </Layout>
</template>
<style scoped>
    table,th{
        border: 1px solid grey;
    }
    td{
        border: 1px dotted grey;
    }
    /* .mag{
        margin-top: 270px !important;
    } */
    .btn-primary, .page-link { background-color:#FAB031 !important;border-color:#FAB031 !important;}
</style>