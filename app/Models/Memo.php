<?php
// app/Models/Memo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $table = 'memos'; // Explicitly set the table name

    protected $fillable = [
        'subject',
        'message',
        'recipients',
        'memo_by', // New field for sender's name
        'signature',   // New field for sender's signature
    ];
    
    protected $casts = [
        'recipients' => 'array', // Decode JSON recipients automatically
    ];
}
