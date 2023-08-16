<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
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
        return $this->belongsTo(Stock::class, 'product_id', 'id');
    }
}
