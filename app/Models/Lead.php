<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'notes',
        'status',
        'created_by',
    ];

    // Optional: relationship to User who created the lead
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
