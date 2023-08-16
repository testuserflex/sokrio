<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    use HasFactory;
    protected $guarded=[];
    const Sale = 1;
    const Deposit = 2;
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function paymode(){
        return $this->belongsTo(PaymentOption::class, 'mode', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}
