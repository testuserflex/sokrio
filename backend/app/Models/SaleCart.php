<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCart extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function unitx(){
        return $this->belongsTo(ProductUnit::class, 'unit', 'id');
    }
    public function helditems(){
        return $this->belongsTo(SaleHold::class, 'holdId', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

}
