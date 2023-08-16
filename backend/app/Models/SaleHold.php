<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleHold extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function items(){
        return $this->hasMany(SaleCart::class,'holdId', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }
    public function branchx(){
        return $this->belongsTo(Branch::class,'branch_id', 'id');
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id', 'id');
    }
}
