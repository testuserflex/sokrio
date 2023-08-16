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
  <div v-if="DepositReceipt.data" class="row mainbody">
        <div id="app">
            <img v-if="DepositReceipt.data.business2.Businesslogo" :src="DepositReceipt.data?DepositReceipt.data.business2.Businesslogo:''" height="80" /> 
            <div style=" transform: scale(1,1.5); font-size: 16px; text-align: center;">
                <!-- <b-spinner v-else class="m-0" variant="primary" role="status"></b-spinner> -->

               <div style="margin-top: 20px; font-size: 20px; text-transform: uppercase;"><strong v-if="ReceiptSettings.indicate_business_name == 1"><br>{{ DepositReceipt.data.business.name??"" }}</strong><strong v-if="ReceiptSettings.indicate_branch_name == 1"> - {{DepositReceipt.data.branch.name??""}}</strong></div>
                <div><strong>Location: </strong><span v-html="DepositReceipt.data?DepositReceipt.data.branch.address:'---'"></span></div>
                <div v-if="ReceiptSettings.show_contacts == 1"><strong>Tel: </strong>{{DepositReceipt.data.branch.phone1??""}}/{{DepositReceipt.data.branch.phone2??""}}</div>
                <div v-if="ReceiptSettings.indicate_businessemail == 1"><strong>Email: </strong>{{DepositReceipt.data.branch.email??""}}<br></div>
            </div>
            <center>
                <h3 style="margin-top: 50px;"><b style="text-decoration: underline;">DEPOSIT RECEIPT NO: </b>{{DepositReceipt.data.receipt}}</h3> 
                <p style="font-size: 22px;" v-if="ReceiptSettings.indicate_customer == 1 && DepositReceipt.data.customer != ''"><b>Customer:</b> {{DepositReceipt.data.customer.name}} - {{DepositReceipt.data.customer.contact}}</p>
            <b><hr style="border-style: dashed; width: 97%; text-decoration:underline dotted;"></b>
            <table style="width: 60%;">
                <tr>
                    <td><b>Date:</b></td>
                </tr>
                <tr> <td>{{ DepositReceipt.data?DepositReceipt.data.added_on:"---" }}</td></tr>
            </table
            <b><hr style="border-style: dashed; width: 97%;"></b>
            <div style="width: 90%;">
                <p>
                    <b>Amount Deposited:</b>
                    {{ DepositReceipt.data ? DepositReceipt.data.amount.toLocaleString() : "0" }}/=
                <br /><br>
                    <b>Total Balance Due:</b>
                    {{
                    DepositReceipt.data ? DepositReceipt.data.balance.balance.toLocaleString() : "0"
                }}/=
                    <br />
                </p>
                </div>
                <b><hr style="border-style: dashed; width: 97%" /></b>
                <p v-if="ReceiptSettings.indicate_user == 1"><b>Served By:</b> {{DepositReceipt.data ? DepositReceipt.data.user : "-----"}}</p>
                <p><b>{{DepositReceipt.data ? DepositReceipt.data.business.tagline : "-----"}}</b></p>
                <b v-if="DepositReceipt.data.business.tagline || ReceiptSettings.indicate_user == 1"><hr style="border-style: dashed; width: 97%" /></b>
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
        DepositReceipt:{},
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
            axios.get(`https://newapp.poscreamug.com/api/print_depositreceipt/${this.id}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Credentials': true,
                    'Authorization': `Bearer ${token}`
                }
            }).then(resp => {
                this.DepositReceipt = resp.data
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
