<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function productunits()
    {
        return $this->hasMany(ProductUnit::class, 'product_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function businessproduct()
    {
        return $this->belongsTo(BusinessProduct::class, 'item_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id', 'id');
    }
    // public function category()
    // {
    //     return $this->belongsTo(ProductCategory::class, 'item_id', 'id');
    // }
}
