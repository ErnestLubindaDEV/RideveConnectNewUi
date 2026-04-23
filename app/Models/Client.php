<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel naming conventions)
    protected $table = 'contacts';

    // Mass assignable fields
    protected $fillable = [
        'client_name',
        'contact_name',
        'interests',
        'phone_number',
        'email_address',
    ];

    // Optional: If you want timestamps handled automatically
    public $timestamps = true;

    // Optional: Casts (if needed)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
