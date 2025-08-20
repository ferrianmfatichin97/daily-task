<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
    ];

    /**
     * Get the task that owns the history record.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that owns the history record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the description of the history action.
     */
    public function getDescriptionAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Task created',
            'updated' => 'Task updated',
            'status_changed' => "Status changed from {$this->old_value} to {$this->new_value}",
            'priority_changed' => "Priority changed from {$this->old_value} to {$this->new_value}",
            'assigned' => "Assigned to user",
            'due_date_changed' => "Due date changed from {$this->old_value} to {$this->new_value}",
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}