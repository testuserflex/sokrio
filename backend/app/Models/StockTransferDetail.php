<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product(){
        return $this->belongsTo(BusinessProduct::class, 'product_id', 'id');
    }
    public function punit(){
        return $this->belongsTo(ProductUnit::class, 'unit', 'id');
    }
    public function transfer(){
        return $this->belongsTo(StockTransfer::class, 'transfer_id', 'id');
    }
    public function source(){
        return $this->belongsTo(Branch::class, 'source_branch', 'id');
    }
    public function destination(){
        return $this->belongsTo(Branch::class, 'destination_branch', 'id');
    }
    public function receivedby(){
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
