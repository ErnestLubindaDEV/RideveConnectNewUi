<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MothersDaySubmission extends Model
{
    // 1. The "Allowed List" (Must be inside the class)
    protected $fillable = [
        'user_id',
        'employee_name',
        'selected_date',
        'reason',
    ];

    // 2. The Relationship (Must also be inside the class)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}