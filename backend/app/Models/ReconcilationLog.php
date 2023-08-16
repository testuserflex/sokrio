<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconcilationLog extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
