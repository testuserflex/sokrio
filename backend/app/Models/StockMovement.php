<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    protected $guarded=[];
    const Product =0;
    const Sale =1;
    const SaleReturn =1;
    const Purchase =3;
    const PurchaseReturn =4;
    const SpoiltStock =5;
    const Expired =6;
    const Reconciled =7;
    const StockTransfer =8;
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
