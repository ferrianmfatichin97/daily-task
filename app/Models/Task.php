<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'status', 'due_date', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }
}
