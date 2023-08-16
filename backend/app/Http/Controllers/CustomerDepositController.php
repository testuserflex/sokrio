<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerDepositResource;
use App\Models\Customer;
use App\Http\Controllers\FunctionController;
use App\Models\GeneralSetting;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Debtor;
use App\Models\SmsBalance;
use App\Models\CustomerAccount;
use App\Models\CustomerDeposit;
use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerDepositController extends Controller
{

    public  $functionmaster;
    public function __construct(){
      $this->functionmaster  = new FunctionController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $customerdeposit = CustomerDeposit::where('business_id',Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $customerdeposit = CustomerDeposit::where('branch_id',Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return CustomerDepositResource:: collection($customerdeposit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'mode' => 'required',
        ]);

        // generate receipt
        $receiptdet = $this->functionmaster->generateReceipt();
        $recno = $receiptdet['recno'];
        $receipt = $receiptdet['receipt'];

        $credit = Sale::where('customer_id', $request->customer)->where('total','>','paid')->where('branch_id', Auth::user()->branch_id)->first();
        $debtor = Debtor::where('customer_id', $request->customer)->where('total','>','paid')->where('branch_id', Auth::user()->branch_id)->first();
        if(!$credit && !$debtor){
            $cdeposit=CustomerDeposit::create([
                'customer_id' => $request->customer,
                'amount' => str_replace(",","",$request->amount),
                'date' => $request->date ?? date('Y-m-d'),
                'mode' => $request->mode,
                'receipt' => $recno,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
           CustomerAccount::create([
               'customer_id' => $cdeposit->customer_id,
               'amount_in' => $cdeposit->amount,
               'amount_out' => 0,
               'category' => 2,
               'date' => $cdeposit->date,
               'mode' => $cdeposit->mode,
               'tId' => $cdeposit->id,
               'description' => 'Customer Deposit',
               'user_id' => Auth::user()->id,
               'branch_id' => Auth::user()->branch_id,
               'business_id' => Auth::user()->business_id,

           ]);

            $this->functionmaster->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

            $action = "Recorded a  Customer deposit of: ".$request->amount." From ".Customer::find($cdeposit->customer_id)->name;
            $this->functionmaster->log($action,Log::Finance);

            return response(['id'=> $cdeposit->id, 'status'=> 200]);
        }
        elseif($credit){
            if(($credit->total-$credit->paid)>=str_replace(",","",$request->amount)){
                $paid = str_replace(",","",$request->amount);
                $credit->paid = $credit->paid+str_replace(",","",$request->amount);
                $credit->save();

                $spayment = Payment::create([
                    'sale_id' => $credit->id,
                    'amount' => $paid,
                    'mode' => $request->mode,
                    'receipt' => $receipt,
                    'date' => $request->date ?? date('Y-m-d'),
                    'recno' => $recno,
                    'month' => date("m"),
                    'year' => date("Y"),
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                // insert into customer account
                CustomerAccount::create([
                    'customer_id' => $credit->customer_id,
                    'amount_in' => $spayment->amount,
                    'amount_out' => 0,
                    'tId' => $spayment->id,
                    'mode' => $spayment->mode,
                    'date' => $spayment->date,
                    'description' => "Clearing balance from buying items of ".$credit->total,
                    'user_id' => $credit->user_id,
                    'branch_id' => $credit->branch_id,
                    'business_id' => $credit->business_id,
                ]);
                $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
                if($credit->customer_id && $setting->message_saledetails == 1){
                    $salemessage = 'Your payment of '.$paid.' has been received and you have a pending balance of '.$credit->total-$credit->paid.'. Thank you for supporting '.Business::find(Auth::user()->business_id)->name;
                    $smsSize = ceil(strlen($salemessage) / 160);
                    $totalSMS = intVal($smsSize);
                    //fetch sms balance
                    $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                    if ($smsBalance >= $totalSMS) {
                        $reciepients = $request->recievers;
                        //sending the sms
                        $contact = substr(Customer::find($sale->customer_id)->contact, -9);
                        //using the message api function
                        $this->sendSMSAPI($contact, $salemessage, $smsSize, 'Sales');
                    } else {
                        return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS", "status" => 500]);
                    }
                }
                return response(['message'=> 'Debt Cleared Successfully', 'status'=> 200]);

            }else{
                $balance = round(str_replace(",","",$request->amount)-($credit->total-$credit->paid), 2);
                $paid = $credit->total-$credit->paid;
                $credit->paid = $credit->total;
                $credit->save();

                $spayment = Payment::create([
                    'sale_id' => $credit->id,
                    'amount' => $paid,
                    'mode' => $request->mode,
                    'receipt' => $receipt,
                    'date' => $request->date ?? date('Y-m-d'),
                    'recno' => $recno,
                    'month' => date("m"),
                    'year' => date("Y"),
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                // insert into customer account
                CustomerAccount::create([
                    'customer_id' => $credit->customer_id,
                    'amount_in' => $spayment->amount,
                    'amount_out' => 0,
                    'tId' => $spayment->id,
                    'mode' => $spayment->mode,
                    'date' => $spayment->date,
                    'description' => "Clearing balance from buying items of ".$credit->total,
                    'user_id' => $credit->user_id,
                    'branch_id' => $credit->branch_id,
                    'business_id' => $credit->business_id,
                ]);
                $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
                if($credit->customer_id && $setting->message_saledetails == 1){
                    $salemessage = 'Your payment of '.$paid.' has been received and you have a pending balance of '.$credit->total-$credit->paid.'. Thank you for supporting '.Business::find(Auth::user()->business_id)->name;
                    $smsSize = ceil(strlen($salemessage) / 160);
                    $totalSMS = intVal($smsSize);
                    //fetch sms balance
                    $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                    if ($smsBalance >= $totalSMS) {
                        $reciepients = $request->recievers;
                        //sending the sms
                        $contact = substr(Customer::find($sale->customer_id)->contact, -9);
                        //using the message api function
                        $this->sendSMSAPI($contact, $salemessage, $smsSize, 'Sales');
                    } else {
                        return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS", "status" => 500]);
                    }
                }

                    // insert into transactions
                $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$request->mode,"Debt Clearance",$spayment->date);
                // insert into logs
                $this->functionmaster->log("Cleared a debt of amount $paid", Log::Sale);

                $cdeposit=CustomerDeposit::create([
                    'customer_id' => $credit->customer_id,
                    'amount' => $balance,
                    'date' => $request->date ?? date('Y-m-d'),
                    'mode' => $request->mode,
                    'receipt' => $recno,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);
                CustomerAccount::create([
                    'customer_id' => $cdeposit->customer_id,
                    'amount_in' => $cdeposit->amount,
                    'amount_out' => 0,
                    'category' => 2,
                    'date' => $cdeposit->date,
                    'mode' => $cdeposit->mode,
                    'tId' => $cdeposit->id,
                    'description' => 'Customer Deposit',
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,

                ]);

                $this->functionmaster->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

                $action = "Recorded a  Customer deposit of: ".$cdeposit->amount." From ".Customer::find($cdeposit->customer_id)->name;
                $this->functionmaster->log($action,Log::Finance);

                return response(['id'=> $cdeposit->id, 'status'=> 200]);
            }
        }elseif($debtor){
            if(($debtor->total-$debtor->paid)>=str_replace(",","",$request->amount)){
                $paid = str_replace(",","",$request->amount);
                $debtor->paid = $debtor->paid+str_replace(",","",$request->amount);
                $debtor->save();

                $spayment = Payment::create([
                    'sale_id' => $debtor->id,
                    'amount' => $paid,
                    'mode' => $request->mode,
                    'receipt' => $receipt,
                    'date' => $request->date ?? date('Y-m-d'),
                    'recno' => $recno,
                    'month' => date("m"),
                    'year' => date("Y"),
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                // insert into customer account
                CustomerAccount::create([
                    'customer_id' => $debtor->customer_id,
                    'amount_in' => $spayment->amount,
                    'amount_out' => 0,
                    'tId' => $spayment->id,
                    'mode' => $spayment->mode,
                    'date' => $spayment->date,
                    'description' => "Clearing balance from imported debt of total".$debtor->total,
                    'user_id' => $spayment->user_id,
                    'branch_id' => $spayment->branch_id,
                    'business_id' => $spayment->business_id,
                ]);

                // insert into transactions
                $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$request->mode,"Previous Debt Clearance",$spayment->date);
                // insert into logs
                $this->functionmaster->log("Cleared a previous debt of amount $request->paid", Log::Sale);

                return response(['message'=> 'Debt Cleared Successfully', 'status'=> 200]);

            }else{
                $balance = str_replace(",","",$request->amount)-($debtor->total-$debtor->paid);
                $paid = $debtor->total-$debtor->paid;
                $debtor->paid = $debtor->total;
                $debtor->save();


                $spayment = Payment::create([
                    'sale_id' => $debtor->id,
                    'amount' => $paid,
                    'mode' => $request->mode,
                    'receipt' => $receipt,
                    'date' => $request->date ?? date('Y-m-d'),
                    'recno' => $recno,
                    'month' => date("m"),
                    'year' => date("Y"),
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                // insert into customer account
                CustomerAccount::create([
                    'customer_id' => $debtor->customer_id,
                    'amount_in' => $spayment->amount,
                    'amount_out' => 0,
                    'tId' => $spayment->id,
                    'mode' => $spayment->mode,
                    'date' => $spayment->date,
                    'description' => "Clearing balance from imported debt of total".$debtor->total,
                    'user_id' => $spayment->user_id,
                    'branch_id' => $spayment->branch_id,
                    'business_id' => $spayment->business_id,
                ]);

                // insert into transactions
                $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$request->mode,"Previous Debt Clearance",$spayment->date);
                // insert into logs
                $this->functionmaster->log("Cleared a previous debt of amount $request->paid", Log::Sale);

                $cdeposit=CustomerDeposit::create([
                    'customer_id' => $debtor->customer_id,
                    'amount' => $balance,
                    'date' => $request->date ?? date('Y-m-d'),
                    'mode' => $request->mode,
                    'receipt' => $recno,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);
                CustomerAccount::create([
                    'customer_id' => $cdeposit->customer_id,
                    'amount_in' => $cdeposit->amount,
                    'amount_out' => 0,
                    'category' => 2,
                    'date' => $cdeposit->date,
                    'mode' => $cdeposit->mode,
                    'tId' => $cdeposit->id,
                    'description' => 'Customer Deposit',
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,

                ]);

                $this->functionmaster->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

                $action = "Recorded a  Customer deposit of: ".$cdeposit->amount." From ".Customer::find($cdeposit->customer_id)->name;
                $this->functionmaster->log($action,Log::Finance);

                return response(['id'=> $cdeposit->id, 'status'=> 200]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerDeposit  $customerDeposit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerDeposit  $customerDeposit
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerDeposit $customerDeposit)
    {
        //
    }

    // Receipt Printing
    public function receipt_data($id)
    {
        $items = CustomerDeposit::find($id);
        return new CustomerDepositResource($items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerDeposit  $customerDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customerdep = CustomerDeposit::find($id);
        $customerdep->amount = str_replace(",","",$request->amount);
        $customerdep->date = $request->date;
        $customerdep->save();

       // update account
        CustomerAccount::where('customer_id',$customerdep->customer_id)->where('tId',$customerdep->id)->where('category',2)
            ->update(['amount_in'=>$customerdep->amount]);

        // update transactions table
        Transaction::where('category',9)->where('tId',$customerdep->id)->where('branch_id',Auth::user()->branch_id)
            ->update(['amount_in'=>$customerdep->amount]);

        // record log
        $action = "Updated a customer deposit of amount ".$customerdep->amount." recorded on ".$customerdep->date;

        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerDeposit  $customerDeposit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cdep = CustomerDeposit::find($id);
        $cdep->delete();
        CustomerAccount::where('customer_id',$cdep->customer_id)->where('tId',$id)->where('category',2)->where('branch_id',Auth::user()->branch_id)->delete();
        Transaction::where('category',9)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)->delete();
        $action = "deleted a customer deposit of of amount ".$cdep->amount." recorded on ".$cdep->date;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }
}
