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
    title: "General Settings",
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
      title: "General Settings",
      items: [
        {
          text: "Home",
          href: "/",
        },
        {
          text: "General Settings",
          active: true,
        },
      ],


      // Value from user input for adding option
      data: {
        id: "",
        column: "",
        status: "",
      },

      adddata: {
        message: '',
      },
      messageError:"",
      clientmsg: 0,
      clientSize: 0,
    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("setting", ["GeneralSettings", "SaveStatus", "Message"]),

    /**
     * Total no. of records
     */

    rows() {
      return this.GeneralSettings ? this.GeneralSettings.length : 1;
    },

    /**
     * Todo list of records
     */
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.GeneralSettings ? this.GeneralSettings.length : 1;
  },

  methods: {
    ...mapActions({
      FetchGeneralSettings: "setting/fetchGeneralSettings",
      ChangeSetting: "setting/ChangeGeneralSettings",
      SaveMessage: "setting/SaveMessage",
      FetchMessage: "setting/FetchMessage",
    }),


    UpdateSetting(val,event) {
      this.data.id=this.GeneralSettings.id;
      this.data.column=val;
      this.data.status=event.target.value;
      this.ChangeSetting(this.data);
    },

    //== close modal
    closeModal(modalId) {
      this.$nextTick(() => {
        this.$bvModal.hide(modalId);
      });
    },

    getValues() {
      this.clientmsg = this.adddata.message.length;
      this.clientSize = Math.ceil(this.clientmsg / 160);
    },

    AddMessage(modalId) {
      if (this.adddata.message == "") {
        this.messageError = "Please add a message to set";
        return;
      }else{
        this.messageError = "";
      }
      this.modal_id = modalId;
      this.SaveMessage(this.adddata);
      this.resetAdd();
      this.closeModal(modalId);
    },
    resetAdd(){
      this.adddata.message = "",
      this.messageError = ""
    },

    FillDetails(){
      this.adddata.message = this.Message.customer_message;
      this.clientmsg = this.adddata.message.length;
      this.clientSize = Math.ceil(this.clientmsg / 160);
    },

},

created() {
  this.FetchGeneralSettings();
  this.FetchMessage();
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
              <div class="row">
              <div class="col-md-8">
                <h4 class="card-title">Business General Settings</h4>
              </div>
              <div class="col-md-4" align="right" v-if="BranchData.BranchId != 0">
                <b-button v-b-modal.add-message-modal variant="warning" @click="FillDetails()"><i class="fa fa-plus"></i>Appreciation Message</b-button>
              </div>
            </div>

            </div>


            </div>

             <!-- modal set message starts -->
        <b-modal
            header-bg-variant="warning"
            header-text-variant="light"
            body-text-variant="warning"
            footer-bg-variant="warning"
            footer-text-variant="warning"
            id="add-message-modal"
            hide-footer
            @hidden="resetAdd()"
        >
          <template #modal-title>Set your message</template>
          <div class="form-group mb-3">
              <div class="mb-3">
                <label>Message Body</label
                >
                <div>
                  <textarea
                    v-model="adddata.message"
                    class="form-control"
                    rows="5"
                    @input="getValues()"
                  ></textarea>
                  <div v-if="messageError" class="text-danger">
                    Please enter a message to send.
                  </div>
                  <div
                    v-if="adddata.message && adddata.message.length > 0"
                    class="text-success font-size-15"
                    style="border-bottom: 2px solid #0e8f2d"
                  >
                    <strong>Characters:</strong> {{ clientmsg }} |
                    <strong>Size:</strong> {{ clientSize }} |
                  </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
                type="button"
                class="btn btn-danger"
                data-dismiss="modal"
                @click="closeModal('add-message-modal')"
            >
              <i class="fa fa-times"></i> Close
            </button>
            <button
                type="button"
                class="btn btn-warning"
                @click="AddMessage('add-message-modal')"
            >
              <i class="fa fa-trash"></i> Proceed
              <b-spinner
                  v-if="SaveStatus"
                  variant="light"
                  small
                  label="Adding Message"
              ></b-spinner>
            </button>
          </div>
        </b-modal>
        <!-- modal set message  ends -->

            <!-- Table -->
            <div class="table-responsive mb-0">

              <div class="row m-4">
                <div class="col-md-3">Back Date Sales</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('back_date_sales',$event)"
                      :value="GeneralSettings.back_date_sales"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Back Date Purchases</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('back_date_purchases',$event)"
                      :value="GeneralSettings.back_date_purchases"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Allow Sale Holding</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('sale_holding',$event)"
                      :value="GeneralSettings.sale_holding"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Allow Negative Stock</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('allow_negative_stock',$event)"
                      :value="GeneralSettings.allow_negative_stock"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Allow Multiple Units</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('allow_multiple_units',$event)"
                      :value="GeneralSettings.allow_multiple_units"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Track Expiries</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('track_expiries',$event)"
                      :value="GeneralSettings.track_expiries"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Track Reserve Price</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('allow_reserve_price',$event)"
                      :value="GeneralSettings.allow_reserve_price"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Track untaken orders</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('track_untake_orders',$event)"
                      :value="GeneralSettings.track_untake_orders"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Track Customers</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('track_customers',$event)"
                      :value="GeneralSettings.track_customers"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Allow Customer deposits</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('customer_deposit',$event)"
                      :value="GeneralSettings.customer_deposit"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Track Suppliers</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('track_suppliers',$event)"
                      :value="GeneralSettings.track_suppliers"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Print Order Invoices</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('print_order_invoices',$event)"
                      :value="GeneralSettings.print_order_invoices"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Import Products to store</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('import_products',$event)"
                      :value="GeneralSettings.import_products"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>

                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Send Appreciation message</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('send_appreciation',$event)"
                      :value="GeneralSettings.send_appreciation"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Enable Credit Limit</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('enable_credit_limit',$event)"
                      :value="GeneralSettings.enable_credit_limit"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>

                <div class="col-md-2"></div>

                <div class="col-md-3" mb-2>Enable Debt Limit</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('enable_debt_limit',$event)"
                      :value="GeneralSettings.enable_debt_limit"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Enable Ready Orders Confirmation</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('confirm_readyorders',$event)"
                      :value="GeneralSettings.confirm_readyorders"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>

                <div class="col-md-2"></div>
                <div class="col-md-3" mb-2>Enable wholeselling</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('enable_wholeselling',$event)"
                      :value="GeneralSettings.enable_wholeselling"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Calculate stock value using;</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('stockvalue_calculation',$event)"
                      :value="GeneralSettings.stockvalue_calculation"
                  >
                    <option value="1">Selling Price</option>
                    <option value="0" >Buying Price</option>

                  </select>
                </div>

                <div class="col-md-2"></div>
                <div class="col-md-3">Capture sale description and picking date</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('sale_description',$event)"
                      :value="GeneralSettings.sale_description"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Allow negative stock transfer</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('negative_stocktransfer',$event)"
                      :value="GeneralSettings.negative_stocktransfer"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-3">Set  Total As Default Paid</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('set_total_as_paid',$event)"
                      :value="GeneralSettings.set_total_as_paid"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Allow Sale Discount</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('sale_discount',$event)"
                      :value="GeneralSettings.sale_discount"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-3">Allow Purchase Discount</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('purchase_discount',$event)"
                      :value="GeneralSettings.purchase_discount"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>
              <div class="row m-4">
                <div class="col-md-3">Show  Branch Name</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('show_branchname',$event)"
                      :value="GeneralSettings.show_branchname"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-3">Record Selling Price on Stocking</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('sellingprice_onstocking',$event)"
                      :value="GeneralSettings.sellingprice_onstocking"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>
              <div class="row m-4">
                <div class="col-md-3">Autofill Price on Stocking</div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('autofillprices_onstocking',$event)"
                      :value="GeneralSettings.autofillprices_onstocking"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-3">Show Cash at hand </div>
                <div class="col-md-2">
                  <select
                      class="form-control"
                      @change="UpdateSetting('cash_at_hand',$event)"
                      :value="GeneralSettings.cash_at_hand"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>
                </div>
              </div>
              <div class="row m-4">
                <div class="col-md-3">Show Business value </div>
                <div class="col-md-2">  
                  <select
                      class="form-control"
                      @change="UpdateSetting('show_businessvalue',$event)"
                      :value="GeneralSettings.show_businessvalue"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>                
                </div>
                <div class="col-md-2"></div>               
                <div class="col-md-3">Show Batch While Selling </div>
                <div class="col-md-2">  
                  <select
                      class="form-control"
                      @change="UpdateSetting('show_batch_selling',$event)"
                      :value="GeneralSettings.show_batch_selling"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>                
                </div>
              </div>

              <div class="row m-4">
                <div class="col-md-3">Deposit Customer Balances </div>
                <div class="col-md-2">  
                  <select
                      class="form-control"
                      @change="UpdateSetting('deposit_balances',$event)"
                      :value="GeneralSettings.deposit_balances"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>                
                </div>
                <div class="col-md-2"></div>               
                <div class="col-md-3">Use Last Cost Price for Profit Calculation </div>
                <div class="col-md-2">  
                  <select
                      class="form-control"
                      @change="UpdateSetting('use_last_costprice',$event)"
                      :value="GeneralSettings.use_last_costprice"
                  >
                    <option value="1">Yes</option>
                    <option value="0" >No</option>

                  </select>                
                </div>
              </div>


              



            </div>
            <Loader v-if="!GeneralSettings" />

          </div>
        </div>
      </div>
  </Layout>

</template>

