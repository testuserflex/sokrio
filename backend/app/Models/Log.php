<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $guarded=[];
    const Auth = 1;
    const Sale = 2;
    const Stock = 3;
    const Management = 4;
    const Finance = 5; // expenses, cash in, cash out, transfers, customer deposits, supplier deposits

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
