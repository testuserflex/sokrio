<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class,'batch_id','id');
    }

    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id','id');
    }
    public function paymentmode(){
        return $this->belongsTo(PaymentOption::class, 'mode', 'id');
    }
}
