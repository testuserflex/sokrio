<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reconcilation extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function items(){
        return $this->hasMany(StockReconciliation::class, 'recId', 'id');
    }
}
