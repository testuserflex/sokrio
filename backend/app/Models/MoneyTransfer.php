<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function sourcemode() {
        return $this->belongsTo(PaymentOption::class, 'from', 'id');
    }
    public function destinationmode() {
        return $this->belongsTo(PaymentOption::class, 'to', 'id');
    }
}
