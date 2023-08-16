<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstockTransfer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'sent_by', 'id');
    }

    public function destination(){
        return $this->belongsTo(Branch::class, 'destination_branch', 'id');
    }
    public function items(){
        return $this->hasMany(InstockTransferDetail::class, 'transfer_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'products', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
