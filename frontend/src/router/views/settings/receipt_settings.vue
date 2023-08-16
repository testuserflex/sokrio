<script>
import Layout from "../../layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";
import Loader from "@/components/widgets/preloader";
import { mapState,mapGetters,mapActions } from "vuex"

/**
 * Advanced table component
 */
export default {
  page: {
    title: "Receipt Settings",
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
      title: "Receipt Settings",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "Receipt Settings",
          active: true,
        },
      ],


      // Value from user input for adding option
      data: {
        id: "",
        column: "",
        status: "",
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
    ...mapGetters("setting", ["ReceiptSettings","GeneralSettings", "SaveStatus"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.ReceiptSettings ? this.ReceiptSettings.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.ReceiptSettings ? this.ReceiptSettings.length : 1;
  },

  methods: {
    ...mapActions({
      fetchReceiptSettings: "setting/fetchReceiptSettings",
      ChangeSetting: "setting/ChangeReceiptSettings",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
    }),


    UpdateSetting(val,event) {
      this.data.id=this.ReceiptSettings.id;
      this.data.column=val;
      this.data.status=event.target.value;
      this.ChangeSetting(this.data);
    },


  },
  created() {
    this.fetchReceiptSettings();
    this.FetchGeneralSettings();
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

                <!--                <h4 class="card-title">All Payment Methods</h4>-->
              </div>

            </div>


          </div>
          <!-- Table -->
          <div class="table-responsive mb-0">

            <div class="row m-4">
              <div class="col-md-3">Print Receipt After Sale</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('print_receipt_after_sale',$event)"
                    :value="ReceiptSettings.print_receipt_after_sale"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>
              <div class="col-md-3">Indicate Customer Name on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_customer',$event)"
                    :value="ReceiptSettings.indicate_customer"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4">
              <div class="col-md-3">Indicate Website on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_website',$event)"
                    :value="ReceiptSettings.indicate_website"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3" mb-2>Indicate Tin on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_tin',$event)"
                    :value="ReceiptSettings.indicate_tin"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4">
              <div class="col-md-3">Indicate Business Name on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_business_name',$event)"
                    :value="ReceiptSettings.indicate_business_name"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3" mb-2>Indicate Goods Once Sold are not Returnable</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_goods_not_returnable',$event)"
                    :value="ReceiptSettings.indicate_goods_not_returnable"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4">
              <div class="col-md-3">Indicate goods are Inclusive of VAT on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_goods_vat_inclusive',$event)"
                    :value="ReceiptSettings.indicate_goods_vat_inclusive"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3" mb-2>Indicate User On Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_user',$event)"
                    :value="ReceiptSettings.indicate_user"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4">
              <div class="col-md-3">Print Receipt after Purchase</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('print_receipt_after_purchase',$event)"
                    :value="ReceiptSettings.print_receipt_after_purchase"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3" mb-2>Indicate Branch name On Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('indicate_branch_name',$event)"
                    :value="ReceiptSettings.indicate_branch_name"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4" v-if="GeneralSettings.sale_description == 1">
              <div class="col-md-3">Indicate sale description on Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('show_description',$event)"
                    :value="ReceiptSettings.show_description"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3" mb-2>Indicate picking date On Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('show_pickingdate',$event)"
                    :value="ReceiptSettings.show_pickingdate"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
            </div>

            <div class="row m-4">
              <div class="col-md-3">Receipt Type</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('receipt_type',$event)"
                    :value="ReceiptSettings.receipt_type"
                >
                  <option value="1">Type 1</option>
                  <option value="2" >Type 2</option>
                  <option value="3" >Type 3</option>
                  <option value="4" >Type 4</option>

                </select>
              </div>
              <div class="col-md-2"></div>
              <div class="col-md-3" mb-2>Indicate Contacts On Receipt</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('show_contacts',$event)"
                    :value="ReceiptSettings.show_contacts"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>

              
            </div>

            <div class="row m-4">
              <div class="col-md-3">Print Deposit Receipts</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('print_deposits',$event)"
                    :value="ReceiptSettings.print_deposits"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
              <div class="col-md-2"></div>

              <div class="col-md-3">Show Expiry Date</div>
              <div class="col-md-2">
                <select
                    class="form-control"
                    @change="UpdateSetting('show_expiry_date',$event)"
                    :value="ReceiptSettings.show_expiry_date"
                >
                  <option value="1">Yes</option>
                  <option value="0" >No</option>

                </select>
              </div>
                            
            </div>






          </div>
          <Loader v-if="!ReceiptSettings" />

        </div>
      </div>
    </div>
  </Layout>

</template>

