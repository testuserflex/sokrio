<!doctype html>
<html class="no-js" lang="en">

<head>
     <script src="https://poscreamug.com/receiptcdn/axios.js"></script>
</head>

<style>
    .mainbody {
        margin-bottom: 20px;
        /* margin-left: 400px; */
        text-align: center;
    }

    .mainfont {
        font-family: "Lucida Console", "Courier New", monospace;
    }
    hr {
    color: black;
    border-style: dotted none none none;
    border-width: 1px;
    background-color: black;
}
</style>

  <body class="mainfont">
  <div v-if="SalesReceipt.data" class="row mainbody">
        <div id="app">
            <img v-if="SalesReceipt.data.business2.Businesslogo" :src="SalesReceipt.data?SalesReceipt.data.business2.Businesslogo:''" height="80" /> 
            <div style=" transform: scale(1,1.5); font-size: 16px; text-align: center;">
                <!-- <b-spinner v-else class="m-0" variant="primary" role="status"></b-spinner> -->

               <div style="margin-top: 20px; font-size: 20px; text-transform: uppercase;"><strong v-if="ReceiptSettings.indicate_business_name == 1"><br>{{ SalesReceipt.data.business.name??"" }}</strong><strong v-if="ReceiptSettings.indicate_branch_name == 1"> - {{SalesReceipt.data.branch.name??""}}</strong></div>
                <div><strong>Location: </strong><span v-html="SalesReceipt.data?SalesReceipt.data.branch.address:'---'"></span></div>
                <div><strong>Tel: </strong>{{SalesReceipt.data.branch.phone1??""}}/{{SalesReceipt.data.branch.phone2??""}}</div>
                <div v-if="ReceiptSettings.indicate_businessemail == 1"><strong>Email: </strong>{{SalesReceipt.data.branch.email??""}}<br></div>
            </div>
            <center>
                <h3 style="margin-top: 50px;"><b style="text-decoration: underline;">SALE PAYMENT RECEIPT NO: </b>{{SalesReceipt.data.receipt}}</h3>            
            <b><hr style="border-style: dashed; width: 97%; text-decoration:underline dotted;"></b>
            <table style="width: 60%;">
                <tr>
                    <td><b>Date:</b></td>
                </tr>
                <tr> <td>{{ SalesReceipt.data?SalesReceipt.data.addedon:"---" }}</td></tr>
            </table
            <b><hr style="border-style: dashed; width: 97%;"></b>
            <div style="width: 90%;">
                <table width="100%" style="font-size: 13px; margin-top: 5px;" v-if="GeneralSettings.show_batch_selling == 1">
                  <thead>
                    <tr>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;" width="25%">PRODUCT</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">BATCH</th>
                      <th v-if="ReceiptSettings.show_expiry_date == 1" style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">EXPIRY DATE</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">UNIT PRICE</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="10%">QUANTITY</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="20%">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(record, r) in SalesReceipt.data?SalesReceipt.data.items:[]" :key="r">
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;" width="25%">{{ record.product }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">{{ record.batch?record.batch.batch_number:'' }}</td>
                      <td v-if="ReceiptSettings.show_expiry_date == 1" style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">{{ record.batch?record.batch.expiry_date:'' }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="15%">{{ thousandSeprator(record.SPrice) }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="10%">{{ record.qty }}{{ record.usymbol }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="20%">{{ thousandSeprator(record.SPrice*record.qty) }}</td>
                    </tr>
                  </tbody>
                </table>
                <table width="100%" style="font-size: 13px; margin-top: 5px;" v-else>
                  <thead>
                    <tr>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;" width="25%">PRODUCT</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">UNIT PRICE</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">QUANTITY</th>
                      <th style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(record, r) in SalesReceipt.data?SalesReceipt.data.items:[]" :key="r">
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;" width="25%">{{ record.product }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">{{ thousandSeprator(record.SPrice) }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">{{ record.qty }}{{ record.usymbol }}</td>
                      <td style="text-align: center; border-bottom: 1px solid black; border-right: 1px solid black;" width="25%">{{ thousandSeprator(record.SPrice*record.qty) }}</td>
                    </tr>
                  </tbody>
                </table>
                <b>Total:
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data
                      ? SalesReceipt.data.totalAmt
                      : "--"
                  )
                }}
                /=</b>
                <p>
                    <b>Amount Paid:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data ? SalesReceipt.data.totalPaid : "0"
                  )
                }}/=
                <span v-if="SalesReceipt.data.discount > 0">
                <br />
                    <b>Discount:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data ? SalesReceipt.data.discount : "0"
                  )
                }}/=</span>
                    <br />
                    <b>Balance:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data.balance > 0? SalesReceipt.data.balance : "0"
                  )
                }}/=
                </p>
                <p v-if="ReceiptSettings.indicate_customer == 1 && SalesReceipt.data.customer != ''"><b>Customer:</b> {{SalesReceipt.data.customer}} - {{SalesReceipt.data.customerPhone}}</p>
            </div>
                <b><hr style="border-style: dashed; width: 97%" /></b>
                <p v-if="ReceiptSettings.indicate_user == 1"><b>Served By:</b> {{SalesReceipt.data ? SalesReceipt.data.user : "-----"}}</p>
                <p v-if="ReceiptSettings.indicate_goods_vat_inclusive == 1"><b>Prices inclusive of VAT where applicable</b></p>
                <p v-if="ReceiptSettings.indicate_goods_not_returnable == 1"><b>Goods once sold are not returnable</b></p>
                <p><b>{{SalesReceipt.data ? SalesReceipt.data.business.tagline : "-----"}}</b></p>
                <b><hr style="border-style: dashed; width: 97%" /></b>
                <p>
                    Printed via
                    <br>
                    www.poscream.com
                </p>
            </center>



        </div>
  </div>
  <script src="https://poscreamug.com/receiptcdn/vue.js"></script>
  <script>
new Vue({
  el: "#app",
  data() {
    return {
        id: '',
        SalesReceipt:{},
        User:{},
        ReceiptSettings:{},
        GeneralSettings:{},
        someValue: 10
    };
  },
  methods: {
    getReciept() {
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        let id = params.get("id")
        this.id = atob(id)
        

        const token = localStorage.getItem('token');
        if (token) {
            axios.get(`https://newapp.poscreamug.com/api/user`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.User = resp.data
                console.log(resp);
            });
            axios.get(`https://newapp.poscreamug.com/api/print_receipt/${this.id}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.SalesReceipt = resp.data
                console.log(resp.data);
            });

            axios.get(`https://newapp.poscreamug.com/api/receipt_settings`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.ReceiptSettings = resp.data
                console.log(resp.data);
            });
            
            axios.get(`https://newapp.poscreamug.com/api/general_settings`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.GeneralSettings = resp.data
            });
        }
    },
    thousandSeprator(m) {
      if (m !== "" || m !== undefined || m !== 0 || m !== "0" || m !== null) {
        return m
          .toString()
          .replace(/[^0-9.]+/g, "")
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else {
        return m;
      }
    }, 
  },
  computed:{  
      someComputed() {
          return this.someValue * 10;
      }     
  },
  updated() {
    setTimeout(() => {
        window.onafterprint = window.close;
        window.print();
    }, 1000);
},

  created() {
      this.getReciept();
  },


});
</script>
  </body>
  </html>
