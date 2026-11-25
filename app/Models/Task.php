<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_by',
        'assigned_to',
        'user_id', // For backward compatibility
        'status',
        'submission',
        'attachment',
        'admin_feedback',
    ];

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
