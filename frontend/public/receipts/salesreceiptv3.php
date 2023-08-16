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
                <!--<div><strong>Location: </strong><span v-html="SalesReceipt.data?SalesReceipt.data.branch.address:'---'"></span></div>-->
                <div><strong>Tel: </strong>{{SalesReceipt.data.branch.phone1??""}}/{{SalesReceipt.data.branch.phone2??""}}</div>
                <div v-if="ReceiptSettings.indicate_businessemail == 1"><strong>Email: </strong>{{SalesReceipt.data.branch.email??""}}</div>
            </div>
            <center>
                <h3 style="margin-top: 30px;"><b style="text-decoration: underline;">RECEIPT NO</b>: {{SalesReceipt.data.receipt}}</h3>     
            <table style="width: 90%;">
                <tr>
                    <td><b>Date:</b></td>
                </tr>
                <tr> <td>{{ SalesReceipt.data?SalesReceipt.data.addedon:"---" }}</td></tr>
            </table
            <b><hr style="border-style: solid grey; width: 97%;"></b>
            <div style="width: 90%;">
                <table v-if="ReceiptSettings.show_quantityfirst == 1" width="100%" style="font-size: 13px; margin-top: 5px;">
                  <thead>
                    <tr>
                      <th width="25%">QUANTITY</th>
                      <th width="25%">PRODUCT</th>
                      <th width="25%">UNIT PRICE</th>
                      <th width="25%">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(record, r) in SalesReceipt.data?SalesReceipt.data.items:[]" :key="r">
                      <td width="25%">{{ record.qty }}{{ record.usymbol }}</td>
                      <td width="25%">{{ record.product }}</td>
                      <td width="25%">{{ thousandSeprator(record.SPrice) }}</td>
                      <td width="25%">{{ thousandSeprator(record.SPrice*record.qty) }}</td>
                    </tr>
                  </tbody>
                </table>
                <table v-if="ReceiptSettings.show_quantityfirst != 1" width="100%" style="font-size: 13px; margin-top: 5px;">
                  <thead>
                    <tr>
                      <th width="45%">PRODUCT</th>
                      <th width="20%">UNIT PRICE</th>
                      <th width="25%">QUANTITY</th>
                      <th width="20%">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td colspan="4"><b><hr style="border-style: solid grey; width: 97%;"></b></td></tr>
                    <tr v-for="(record, r) in SalesReceipt.data?SalesReceipt.data.items:[]" :key="r">
                        <td colspan="4">
                            <table>
                             <tr>{{ record.product }}</tr>
                              <tr>
                                  <td width="50%"></td>
                                  <td width="20%">{{ thousandSeprator(record.SPrice) }}</td>
                                  <td width="25%">{{ record.qty }}{{ record.usymbol }}</td>
                                  <td width="20%">{{ thousandSeprator(record.SPrice*record.qty) }}</td>
                              </tr>
                            </table>
                        </td>
                    </tr>
                  </tbody>
                </table>
                <b><hr style="border-style: solid grey; width: 97%;"></b>
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
                    <b>Received:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data.received - SalesReceipt.data.totalAmt > 0 ? SalesReceipt.data.received : SalesReceipt.data.totalPaid
                  )
                }}/=
                <br />
                <span v-if="SalesReceipt.data.discount > 0">
                    <b>Discount:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data.discount>0 ? SalesReceipt.data.discount : "0"
                  )
                }}/=
                <br />
                </span>
                    <span v-if="SalesReceipt.data.balance > 0">
                    <b>Balance:</b>
                    {{
                  this.thousandSeprator(
                    SalesReceipt.data.balance > 0? SalesReceipt.data.balance : "0"
                  )
                }}/=
                <br>
                </span>
                <span v-if="SalesReceipt.data.received - SalesReceipt.data.totalAmt > 0">
                    <b>Change:</b>
                    {{
                  this.thousandSeprator(
                    
                    SalesReceipt.data.received - SalesReceipt.data.totalAmt > 0? SalesReceipt.data.received - SalesReceipt.data.totalAmt : "0"
                  )
                }}/=
                </span>
                </p>
                <p v-if="ReceiptSettings.indicate_customer == 1 && SalesReceipt.data.customer != ''"><b>Customer:</b> {{SalesReceipt.data.customer}} - {{SalesReceipt.data.customerPhone}}</p>
            </div>
                <b><hr style="border-style: solid grey; width: 97%" /></b>
                <p v-if="ReceiptSettings.indicate_user == 1"><b>Served By:</b> {{SalesReceipt.data ? SalesReceipt.data.user : "-----"}}</p>
                <p v-if="ReceiptSettings.indicate_goods_vat_inclusive == 1"><b>Prices inclusive of VAT where applicable</b></p>
                <p v-if="ReceiptSettings.indicate_goods_not_returnable == 1"><b>Goods once sold are not returnable</b></p>
                <p><b>{{SalesReceipt.data ? SalesReceipt.data.business.tagline : "-----"}}</b></p>
                <b><hr style="border-style: solid grey; width: 97%" /></b>
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
