<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function business(){
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
    public function users(){
        return $this->hasMany(User::class, 'business_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(Country::class, 'currency_code', 'id');
    }
}
