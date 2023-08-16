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
  <div v-if="PurchasesReceipt.data" class="row mainbody">
        <div id="app">
            <img v-if="PurchasesReceipt.data.business2.Businesslogo" :src="PurchasesReceipt.data?PurchasesReceipt.data.business2.Businesslogo:''" height="80" /> 
            <div style=" transform: scale(1,1.5); font-size: 16px; text-align: center;">
                <!-- <b-spinner v-else class="m-0" variant="primary" role="status"></b-spinner> -->
                
               <div style=" font-size: 20px; text-transform: uppercase;"><strong v-if="ReceiptSettings.indicate_business_name == 1"><br>{{ PurchasesReceipt.data.business.name??"" }}</strong><strong v-if="ReceiptSettings.indicate_branch_name == 1"> - {{PurchasesReceipt.data.branchobj.name??""}}</strong></div>
                <div><strong>Location: </strong><span v-html="PurchasesReceipt.data?PurchasesReceipt.data.branchobj.address:'---'"></span></div>
                <div><strong>Tel: </strong>{{PurchasesReceipt.data.branchobj.phone1??""}}/{{PurchasesReceipt.data.branchobj.phone2??""}}</div>
                <div><strong>Email: </strong>{{PurchasesReceipt.data.branchobj.email??""}}<br></div>
            </div>
            <center>
                <b><h3 style="text-decoration: underline; margin-top: 50px;">PURCHASE PAYMENT RECEIPT</h3></b>
            <b><hr style="border-style: dashed; width: 97%; text-decoration:underline dotted;"></b>
            <table style="width: 60%;">
                <tr>
                    <td><b>Purchase Date:</b></td>
                    <td>{{ PurchasesReceipt.data?PurchasesReceipt.data.dateAdded:"---" }}</td>
                </tr>
            </table>
            <b><hr style="border-style: dashed; width: 97%;"></b>
            <div style="width: 90%;">
                <strong>PRODUCTS PURCHASED</strong>
                <table width="100%" style="font-size: 13px; margin-top: 5px;">
                  <thead>
                    <tr>
                      <th style="text-align: center;" width="25%">PRODUCT</th>
                      <th style="text-align: center;" width="25%">UNIT PRICE</th>
                      <th style="text-align: center;" width="25%">QUANTITY</th>
                      <th style="text-align: center;" width="25%">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(record, r) in PurchasesReceipt.data?PurchasesReceipt.data.items:[]" :key="r">
                      <td style="text-align: center;" width="25%">{{ record.product }}</td>
                      <td style="text-align: center;" width="25%">{{ thousandSeprator(record.unitPrice) }}</td>
                      <td style="text-align: center;" width="25%">{{ record.qty }}</td>
                      <td style="text-align: center;" width="25%">{{ thousandSeprator(record.total) }}</td>
                    </tr>
                  </tbody>
                </table>
                <b><hr style="border-style: dashed; width: 97%;"></b>
                <b>Total:
                    {{
                  this.thousandSeprator(
                    PurchasesReceipt.data
                      ? PurchasesReceipt.data.totAmt
                      : "--"
                  )
                }}
                /=</b>
                <p>
                    <b>Amount Paid:</b>
                    {{
                  this.thousandSeprator(
                    PurchasesReceipt.data ? PurchasesReceipt.data.amtPaid : "0"
                  )
                }}/=
                <span v-if="PurchasesReceipt.data.discount > 0">
                    <br />
                    <b>Discount:</b>
                    {{
                  this.thousandSeprator(
                    PurchasesReceipt.data ? PurchasesReceipt.data.discount : "0"
                  )
                }}/=
                </span>
                    <br />
                    <b>Balance:</b>
                    {{
                  this.thousandSeprator(
                    PurchasesReceipt.data ? (PurchasesReceipt.data.totAmt)-(PurchasesReceipt.data.amtPaid + PurchasesReceipt.data.discount): "0"
                  )
                }}/=
                </p>
                <p v-if="PurchasesReceipt.data && PurchasesReceipt.data.supplier"><b>Supplier:</b> {{PurchasesReceipt.data.supplier}} - {{PurchasesReceipt.data.supplierobj.contact}}</p>
            </div>
                <b><hr style="border-style: dashed; width: 97%" /></b>
                <p v-if="ReceiptSettings.indicate_user == 1"><b>Paid By:</b> {{PurchasesReceipt.data ? PurchasesReceipt.data.user : "-----"}}</p>
                <p>..................................................</p>
                <b><hr style="border-style: dashed; width: 97%" /></b>
                <p>
                    Receipt Printed via
                    <span>
                    <b>via Poscream Software</b></span><br>
                    wwww.poscream.com
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
        PurchasesReceipt:{},
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
        // alert(this.id)
        

        const token = localStorage.getItem('token');
        // alert(token)
        if (token) {
            axios.get(`https://newapp.poscreamug.com/api/user`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.User = resp.data.data
                console.log(resp.data.data);
            });
            axios.get(`https://newapp.poscreamug.com/api/purchase_print_receipt/${this.id}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.PurchasesReceipt = resp.data
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
          .replace(/\D/g, "")
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

