<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpoReceiveCart extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function lpo(){
        return $this->belongsTo(Lpo::class, 'lpoId', 'id');
    }
}
