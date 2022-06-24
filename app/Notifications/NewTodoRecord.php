<?php

namespace App\Notifications;

use App\Models\TodoRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTodoRecord extends Notification
{
    use Queueable;

    public $todoRecord;

    /**
     * Create a new notification instance.
     *
     * @param TodoRecord $todoRecord
     */
    public function __construct(TodoRecord $todoRecord)
    {
        $this->todoRecord = $todoRecord;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'todo_record_id' => $this->todoRecord->id,
            'title' => trans('new_todo_record_title'),
            'content' => trans('new_todo_record_content'),
            'expired' => $this->todoRecord->end,
            'url' => admin_route('todo.records.show', [$this->todoRecord->id]),
        ];
    }
}
