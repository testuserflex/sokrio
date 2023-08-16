<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messaging extends Model
{
    protected $guarded = [];

    /**
     * Get the branch that owns the Messaging
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(ClinicBranch::class, 'branch_id', 'id');
    }
    public function clinc()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }
}
