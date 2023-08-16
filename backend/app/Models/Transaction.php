<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded=[];

    const Sale = 1;
    const Expense = 2;
    const Purchase = 3;
    const SaleReturn = 4;
    const PurchaseReturn = 5;
    const CashOut = 6;
    const CashIn = 7;
    const MoneyTransfer = 8;
    const CustomerDeposit = 9;
    const OpeningBalance = 10;
    const LPO = 11;
    const SupplierDeposit = 12;

    public function modex(){
        return $this->belongsTo(PaymentOption::class, "mode", "id");
    }
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function branch(){
        return $this->belongsTo(Branch::class, "branch_id", "id");
    }
}
