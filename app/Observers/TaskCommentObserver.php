<?php

namespace App\Observers;

use App\Models\TaskComment;
use App\Models\TaskHistory;
use Illuminate\Support\Str;

class TaskCommentObserver
{
    public function created(TaskComment $comment): void
    {
        TaskHistory::create([
            'task_id'   => $comment->task_id,
            'user_id'   => $comment->user_id,
            'action'    => 'commented',
            'new_value' => Str::limit($comment->comment, 50),
        ]);
    }
}
