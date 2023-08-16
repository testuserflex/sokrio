<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
    public  function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public  function category(){
        return $this->belongsTo(ProductCategory::class,'category_id','id');
    }
}
