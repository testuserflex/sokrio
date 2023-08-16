<script>
import Layout from "../../layouts/main";
import appConfig from "@/app.config";
import { mapGetters, mapActions, mapState } from "vuex";
// for highcharts
import Vue from "vue";
import HighchartsVue from "highcharts-vue";
import Highcharts from "highcharts";
import exportingInit from 'highcharts/modules/exporting';
import { ModelListSelect } from "vue-search-select";
import DatePicker from "vue2-datepicker";
// import moment from "moment";

Vue.use(HighchartsVue);
exportingInit(Highcharts);

/**
 * Dashboard Component
 */
export default {
  page: {
    title: "Dashboard",
    meta: [
      {
        name: "description",
        content: appConfig.description,
      },
    ],
  },
  components: {
    Layout,
    ModelListSelect,
    DatePicker
  },
  data() {
    return {
      title: "Dashboard",
      items: [
        {
          text: "Dashboards",
          href: "/",
        },
        {
          text: "Default",
          active: true,
        },
      ],
      chartOptions: {
         exporting: {
              buttons: {
                  contextButton: {
                      menuItems: ['viewFullscreen', 'downloadPNG', 'downloadJPEG', 'downloadPDF']
                  }
              }
          },
         chart: {
            type: 'column',
          },
          title: {
            text: 'Monthly Sales and Expenses '
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            accessibility: {
              description: 'Months of the year'
            }
          },
          yAxis: {
            title: {
              text: 'Amount'
            },
            labels: {
              formatter: function () {
                return this.value + '°';
              }
            }
          },
          tooltip: {
            crosshairs: true,
            shared: true
          },
          plotOptions: {
            spline: {
              marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
              }
            }
          },
          
          series: [{
            name: 'Income',
            color: "#fab131",
            marker: {
              symbol: 'square'
            },
            data: [0,0,0,0,0,0,0,0,0,0,0,0]

          }, {
            name: 'Expenses',
            color: "#C6FA07",
            marker: {
              symbol: 'diamond'
            },
            data:[0,0,0,0,0,0,0,0,0,0,0,0]
          }]          
      },
      timeFilters: [
        { title: "Today", value: "today" },
        { title: "Yesterday", value: "yesterday" },
        { title: "Last 7 days", value: "last7" },
        { title: "Last 14 days", value: "last14" },
        { title: "Last 28 days", value: "last28" },
        { title: "This month", value: "month" },
        { title: "This quarter", value: "quarter" },
        { title: "Customise", value: "custom" },
      ],
      filterData: {
        time: "today",
        dateRange: "",
        branch_id:0
      },
      dashboardLabel: "Today's",
   
    };
  },
  computed: {
    // MAP STATE
    ...mapState("notification", [
      "type",
      "message",
      "show",
    ]),
    ...mapGetters("analysis", ["Summary", "filterStatus"]),
    ...mapGetters("auth", ["MyUserPerm", "Branches"]),
    ...mapGetters("setting", ["GeneralSettings"]),
  },
  // },
  methods: {
    ...mapActions({
      FetchSummary: "analysis/fetchSummary",
      FetchBraches: "auth/fetchBranches",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
    }),
    thousandSeparator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        let xx = m.toString().split(".");
        let x1=xx[0]
            .toString()
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if (!xx[1]){
            if(m.toString().charAt(m.length - 1) == '.'){
            return x1+".";
             
            }else{
                return x1;

            }
        }else{
          return x1+"."+xx[1];
        }
      } else {
        return m;
      }
    },
    assignGraph(){
      this.chartOptions.series[0].data= this.Summary  ? this.Summary.msales : [0,0,0,0,0,0,0,0,0,0,0,0];
      this.chartOptions.series[1].data= this.Summary  ? this.Summary.mexpenses : [0,0,0,0,0,0,0,0,0,0,0,0];
    },
    FetchSummaryData(){
      this.FetchSummary(this.filterData);
    },
    setLabel() {
      if (this.filterData.time == "today") {
        this.dashboardLabel = "Today's";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "yesterday") {
        this.dashboardLabel = "Yesterday's";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "last7") {
        this.dashboardLabel = "Last 7 days";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "last14") {
        this.dashboardLabel = "Last 14 days";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "last28") {
        this.dashboardLabel = "Last 28 days";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "month") {
        this.dashboardLabel = "This month's";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "quarter") {
        this.dashboardLabel = "This quarter's";
        this.filterData.dateRange = "";
      }
      if (this.filterData.time == "custom") {
        this.dashboardLabel = "";
      }
    },
  },
  watch:{
    Summary(newValue){
      if(newValue){
        this.assignGraph();
      }
    }
  },
  updated(){
    this.assignGraph();    
  },

  created() {
    this.FetchSummary(this.filterData);
    this.assignGraph();
    this.FetchGeneralSettings();
    this.FetchBraches();
  },
};
</script>

<template>
  <Layout>
      <!-- <PageHeader :title="title" :items="items" /> -->
    <div class="row">
      <div class="row py-3 text-center">
        <h4 class="text-primary">{{Summary.businessname}}</h4><h4 v-if="GeneralSettings.show_branchname == 1 && Summary.branchname"> - {{Summary.branchname}}</h4>
      </div>
      <div class="row">    
        <div class="col-md-3">
          <label>FILTER BY TIME</label>
          <model-list-select
            :list="this.timeFilters ? this.timeFilters : []"
            v-model="filterData.time"
            option-value="value"
            size="sm"
            @input="
              setLabel();
              FetchSummaryData();
            "
            option-text="title"
            placeholder="Select Performance"
          >
          </model-list-select>
        </div>
        <div class="col-md-3" v-show="filterData.time == 'custom'">
          <label>PICK DATES</label>
          <date-picker
            v-model="filterData.dateRange"
            range
            append-to-body
            lang="en"
            confirm
          ></date-picker>
        </div>                              
          <div v-if="Summary.branchid == 0" class="col-md-3 mb-3">
            <label>FILTER BY BRANCHES</label>
            
            <model-list-select
              :list="Branches ? Branches : []"
              v-model="filterData.branch_id"
              option-value="id"
              option-text="name"
              placeholder="Select Branch"
              @input="FetchSummaryData()"
            >
            </model-list-select>
          </div>
          <div class="col-md-3 mt-4" align="right">
          <button class="btn btn-warning" type="button" @click="FetchSummaryData()">
            <span class="fa fa-filter"></span> Filter
            <b-spinner
              v-show="filterStatus"
              variant="light"
              small
              role="status"
            ></b-spinner>
          </button>
        </div>
        </div> 
      <div class="row">
        <h4 class="text-center">{{ dashboardLabel || filterData.dateRange == "" ? dashboardLabel:(filterData.dateRange['0'].toDateString())+' - '+(filterData.dateRange['1'].toDateString()) }} Transactions</h4>
        <div class="col-md-6">          
          <div class="col-xl-12">
            <div class="row">
              <div class="col-lg-12 SummaryDiv">
                <div class="card mini-stats-wid smrcard">
                  <div class="card-body">
                    <!-- <div class="d-flex flex-wrap"> -->
                      <!-- <div class="me-3"> -->                        
                          <div class="row">
                            <div class="col-md-5">
                              <h4 class="text-muted mb-0">
                            <i class="bx bxs-book-bookmark  bg-light rounded-circle text-primary font-size-20"></i> 
                              Sales</h4>

                            </div>                            
                            <div class="col-md-7">
                              <h4 class="mb-0" align="right">{{Summary ? Summary.currency_code:''}} : {{Summary.sales ? thousandSeparator(Summary.sales) : 0}}</h4>
                            </div>
                          </div>
                        
                        
                      <!-- </div> -->

                    <!-- </div> -->
                  </div>
                </div>
                
              </div>              
              <div class="col-lg-12">
                <div class="card mini-stats-wid smrcard">
                  <div class="card-body">
                    <!-- <div class="d-flex flex-wrap"> -->
                      <!-- <div class="me-3"> -->
                        <div class="row">
                          <div class="col-md-5">
                            <h4 class="text-muted mb-0">
                           <i class="bx bxs-book-bookmark  bg-light rounded-circle text-primary font-size-20"></i> 
                             Expenses</h4>

                          </div>
                          <div class="col-md-7">
                              <h4 class="mb-0" align="right">{{Summary ? Summary.currency_code:''}}: {{Summary.expenses ? thousandSeparator(Summary.expenses) : 0}}</h4>
                          </div>
                        </div>

                        
                      <!-- </div> -->

                    <!-- </div> -->
                  </div>
                </div>
              </div>
              
            </div>
            <!-- end row -->
          </div>
        <!-- end col -->
        
        </div>
        <div class="col-md-6">
           <div class="col-lg-12">
            <div class="row">

              <div class="col-lg-12">
                <div class="card mini-stats-wid smrcard">
                  <div class="card-body">
                    <!-- <div class="d-flex flex-wrap"> -->
                      <!-- <div class="me-3"> -->
                        <div class="row">
                          <div class="col-md-5">
                            <h4 class="text-muted mb-0">
                           <i class="bx bxs-book-bookmark  bg-light rounded-circle text-primary font-size-20"></i> 
                             Credit Sales</h4>

                          </div>
                          <div class="col-md-7">
                              <h4 class="mb-0" align="right">{{Summary ? Summary.currency_code:''}}: {{Summary.debts ? thousandSeparator(Summary.debts ) :0}}</h4>
                          </div>
                        </div>

                        
                      <!-- </div> -->

                    <!-- </div> -->
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="card mini-stats-wid smrcard">
                  <div class="card-body">
                    <!-- <div class="d-flex flex-wrap"> -->
                      <!-- <div class="me-3"> -->
                        <div class="row">
                          <div class="col-md-5">
                            <h4 class="text-muted mb-0">
                           <i class="bx bxs-book-bookmark bg-light rounded-circle text-primary font-size-20"></i> 
                             Credits Paid</h4>

                          </div>
                          <div class="col-md-7">
                              <h4 class="mb-0" align="right">{{Summary ? Summary.currency_code:''}}: {{Summary.credit ? thousandSeparator(Summary.credit) : 0}}</h4>
                          </div>
                        </div>

                        
                      <!-- </div> -->

                    <!-- </div> -->
                  </div>
                </div>
              </div>

            </div>
            <!-- end row -->          
          </div>
        <!-- end col -->
        
          
        </div>
      </div>

      <div class="row py-3">
        <div class="col-md-6 text-center">
          <div class="row">
            <div class="col-md-6 text-center">
              <h3><b>Business Value:</b> </h3>
            </div>
            <div class="col-md-6 text-center">
              <h3 v-if="GeneralSettings.stockvalue_calculation == 0" class="mb-0" align="left">{{Summary ? Summary.currency_code:''}} : {{ Summary.Buying_businessvalue ? thousandSeparator(Summary.Buying_businessvalue) : 0}}</h3>
              <h3 v-if="GeneralSettings.stockvalue_calculation == 1" class="mb-0" align="left">{{Summary ? Summary.currency_code:''}} : {{ Summary.Selling_businessvalue ? thousandSeparator(Summary.Selling_businessvalue) : 0}}</h3>
            </div>
            </div>          
        </div>
        <div class="col-md-6 text-center">
          <div class="row">
            <div class="col-md-6 text-center">
              <h3><b>Cash at hand:</b> </h3>
            </div>
            <div class="col-md-6 text-center">
              <h3 class="mb-0" align="left">{{Summary ? Summary.currency_code:''}}: {{Summary.cash ? thousandSeparator(Summary.cash) : 0}}</h3>
            </div>
            </div>          
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title mb-4">Annual Analysis</h4>
              <div class="row text-center">
                <div class="col-sm-6">
                  <h5 class="mb-0">{{Summary ? Summary.currency_code:''}}: {{Summary.totalSales ?  thousandSeparator(Summary.totalSales):0}}</h5>
                  <p class="text-muted">Sales</p>
                </div>
                <div class="col-sm-6">
                  <h5 class="mb-0">{{Summary ? Summary.currency_code:''}}: {{Summary.totalExpenses ? thousandSeparator( Summary.totalExpenses) :0}}</h5>
                  <p class="text-muted">Expenses</p>
                </div>
              </div>
              <!-- Sales Chart -->
                <highcharts class="hc" :options="chartOptions" ref="chart" ></highcharts>

            </div>
          </div>
        </div>
      </div >
      
      <!-- end col -->
    </div>
    <!-- end row -->
  </Layout>
</template>
<style type="text/css">
.smrcard{
  box-shadow: 2px 2px 4px #000000;  margin-top: 00px !important;
  height:80px !important;
  margin-bottom:10px !important;

}

.SummaryDiv{
  margin-bottom:0px !important;

}
.hc{
  height:500px !important;
}
</style>
