<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccount extends Model
{
    use HasFactory;
    protected $guarded=[];
    const Purchase = 1;
    const Deposit = 2;
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function paymode()
    {
        return $this->belongsTo(PaymentOption::class, 'mode', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
