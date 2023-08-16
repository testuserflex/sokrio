<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lpo extends Model
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
    public function supplierx(){
        return $this->belongsTo(Supplier::class, 'supplier', 'id');
    }
    public function items(){
        return $this->hasMany(LpoDetail::class, 'lpo_id', 'id');
    }
}
