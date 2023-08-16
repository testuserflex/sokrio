<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function punit(){
        return $this->belongsTo(ProductUnit::class,'unit','id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
