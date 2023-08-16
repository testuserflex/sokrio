<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceChange extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
