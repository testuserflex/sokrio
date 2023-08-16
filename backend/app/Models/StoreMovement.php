<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreMovement extends Model
{
    use HasFactory;
    protected $guarded=[];
    const Purchase = 0;
    const Store2Shop = 1;
    const Shop2Store = 2;
    const StoreReturn = 3;
    const StoreTransfer = 4;
    const Reconciled =5;
}
