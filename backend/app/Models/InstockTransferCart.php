<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstockTransferCart extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function destination(){
        return $this->belongsTo(Branch::class, 'destination_branch', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function punit(){
        return $this->belongsTo(ProductUnit::class, 'unit', 'id');
    }
}
