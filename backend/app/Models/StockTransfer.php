<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'sent_by', 'id');
    }
    public function source(){
        return $this->belongsTo(Branch::class, 'source_branch', 'id');
    }
    public function destination(){
        return $this->belongsTo(Branch::class, 'destination_branch', 'id');
    }
    public function items(){
        return $this->hasMany(StockTransferDetail::class, 'transfer_id', 'id');
    }
    // public function product(){
    //     return $this->belongsTo(Product::class, 'products', 'id');
    // }
}
