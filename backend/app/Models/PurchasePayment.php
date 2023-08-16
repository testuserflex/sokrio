<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function paymentmode(){
        return $this->belongsTo(PaymentOption::class, 'mode', 'id');
    }
    public function purchase(){
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
}
