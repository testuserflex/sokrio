<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded=[];
     public function user(){
         return $this->belongsTo(User::class, 'user_id', 'id');
     }
     public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function business(){
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function items(){
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }
    public function payments(){
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }
}
