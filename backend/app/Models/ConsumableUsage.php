<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableUsage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function item(){
        return $this->belongsTo(ConsumableItem::class, 'item_id', 'id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
