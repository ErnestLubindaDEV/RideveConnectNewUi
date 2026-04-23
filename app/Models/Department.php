<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'supervisor_id'];

    public function employees()
    {
        return $this->hasMany(HRM::class);
    }

    public function supervisor()
    {
        return $this->hasOne(HRM::class)->where('position', 'Supervisor');
    }
}
