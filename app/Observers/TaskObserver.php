<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;

class TaskObserver
{
    public function created(Task $task): void
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'new_value' => $task->status,
        ]);
    }

    public function updating(Task $task): void
    {
        if ($task->isDirty('status')) {
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'old_value' => $task->getOriginal('status'),
                'new_value' => $task->status,
            ]);

            if ($task->status === 'done') {
                $task->completed_at = now();
            }
        } else {
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
            ]);
        }
    }
}
