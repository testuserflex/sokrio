<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpoDetail extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function pUnit(){
        return $this->belongsTo(ProductUnit::class, 'unit', 'id');
    }
    public function lpo(){
        return $this->belongsTo(Lpo::class, 'lpo_id', 'id');
    }
}
