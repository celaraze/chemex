<?php

namespace App\Notifications;

use App\Models\CheckRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCheckRecord extends Notification
{
    use Queueable;

    public $checkRecord;

    /**
     * Create a new notification instance.
     *
     * @param CheckRecord $checkRecord
     */
    public function __construct(CheckRecord $checkRecord)
    {
        $this->checkRecord = $checkRecord;
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
            'check_record_id' => $this->checkRecord->id,
            'title' => trans('new_check_record_title'),
            'content' => trans('new_check_record_content'),
            'expired' => $this->checkRecord->end_time,
            'url' => admin_route('check.records.show', [$this->checkRecord->id]),
        ];
    }
}
