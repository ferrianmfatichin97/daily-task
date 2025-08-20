<?php

namespace App\Observers;

use App\Models\TaskHistory;

class TaskHistoryObserver
{
    /**
     * Handle the TaskHistory "created" event.
     */
    public function creating(TaskHistory $taskHistory): void
    {
        if (empty($taskHistory->user_id) && auth()->check()) {
            $taskHistory->user_id = auth()->id();
        }

        if (empty($taskHistory->action)) {
            $taskHistory->action = 'created';
        }
    }


    /**
     * Handle the TaskHistory "updated" event.
     */
    public function updated(TaskHistory $taskHistory): void
    {
        //
    }

    /**
     * Handle the TaskHistory "deleted" event.
     */
    public function deleted(TaskHistory $taskHistory): void
    {
        //
    }

    /**
     * Handle the TaskHistory "restored" event.
     */
    public function restored(TaskHistory $taskHistory): void
    {
        //
    }

    /**
     * Handle the TaskHistory "force deleted" event.
     */
    public function forceDeleted(TaskHistory $taskHistory): void
    {
        //
    }
}
