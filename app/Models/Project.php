<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork',
        'client_name',
        'sizes',
        'quantities',
        'print_material',
        'inks',
        'cleaning_solution',
        'employee_id',
        'creator',
        'project_type',      
        'assigned_employees', 
        'status',    
        'estimated_time',
        'updated_by',
        'updated_by_name',
    ];

 // Project.php (Model)
public function employees()
{
    return $this->belongsToMany(HRM::class, 'employee_project', 'project_id', 'employee_id');
}


    /**
     * Relationship: A project has many products through the pivot table.
     */
    public function products()
    {
        return $this->belongsToMany(HRM::class, 'product_project')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
