<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model {
    use HasFactory;

    protected $table = 'project_approvals';

    protected $fillable = [
        'project_id',
        'checklist',
        'proof_status',
        'client_name',
        'approval_date',
        'signature',
        'confirmation',
    ];

    protected $casts = [
        'checklist' => 'array',
        'proof_status' => 'array',
    ];
}