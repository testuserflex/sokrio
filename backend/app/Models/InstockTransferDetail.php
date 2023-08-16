<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstockTransferDetail extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function product(){
        return $this->belongsTo(BusinessProduct::class, 'product_id', 'id');
    }
    public function punit(){
        return $this->belongsTo(ProductUnit::class, 'unit', 'id');
    }
    public function transfer(){
        return $this->belongsTo(InstockTransfer::class, 'transfer_id', 'id');
    }
}
