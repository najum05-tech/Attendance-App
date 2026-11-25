<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'start_date',
        'end_date',
        'reason',
        'status',   // pending, approved, rejected
        'admin_comment',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
