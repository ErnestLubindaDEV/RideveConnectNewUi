<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $fillable = [
        'needed_by',
        'requested_by',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'collecting_staff',
        'signature',
    ];

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }
}
