<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sale(){
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function modex(){
        return $this->belongsTo(PaymentOption::class, 'mode', 'id');
    }
}
