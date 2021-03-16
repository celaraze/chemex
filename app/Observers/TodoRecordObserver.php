<?php

namespace App\Observers;

use App\Models\TodoRecord;
use App\Models\User;
use App\Notifications\NewTodoRecord;

class TodoRecordObserver
{
    /**
     * Handle the TodoRecord "created" event.
     *
     * @param TodoRecord $todoRecord
     *
     * @return void
     */
    public function created(TodoRecord $todoRecord)
    {
        $admin_user = User::where('id', $todoRecord->user_id)->first();
        if (!empty($admin_user)) {
            $admin_user->notify(new NewTodoRecord($todoRecord));
        }
    }

    /**
     * Handle the TodoRecord "updated" event.
     *
     * @param TodoRecord $todoRecord
     *
     * @return void
     */
    public function updated(TodoRecord $todoRecord)
    {
        //
    }

    /**
     * Handle the TodoRecord "deleted" event.
     *
     * @param TodoRecord $todoRecord
     *
     * @return void
     */
    public function deleted(TodoRecord $todoRecord)
    {
        //
    }

    /**
     * Handle the TodoRecord "restored" event.
     *
     * @param TodoRecord $todoRecord
     *
     * @return void
     */
    public function restored(TodoRecord $todoRecord)
    {
        //
    }

    /**
     * Handle the TodoRecord "force deleted" event.
     *
     * @param TodoRecord $todoRecord
     *
     * @return void
     */
    public function forceDeleted(TodoRecord $todoRecord)
    {
        //
    }
}
