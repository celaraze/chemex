<?php

namespace App\Observers;

use App\Models\CheckRecord;
use App\Models\User;
use App\Notifications\NewCheckRecord;

class CheckRecordObserver
{
    /**
     * Handle the CheckRecord "created" event.
     *
     * @param CheckRecord $checkRecord
     *
     * @return void
     */
    public function created(CheckRecord $checkRecord)
    {
        // 用户创建盘点任务后，同时创建一条通知给负责人
        $user = User::where('id', $checkRecord->user_id)->first();
        if (!empty($user)) {
            $user->notify(new NewCheckRecord($checkRecord));
        }
    }

    /**
     * Handle the CheckRecord "updated" event.
     *
     * @param CheckRecord $checkRecord
     *
     * @return void
     */
    public function updated(CheckRecord $checkRecord)
    {
        //
    }

    /**
     * Handle the CheckRecord "deleted" event.
     *
     * @param CheckRecord $checkRecord
     *
     * @return void
     */
    public function deleted(CheckRecord $checkRecord)
    {
        //
    }

    /**
     * Handle the CheckRecord "restored" event.
     *
     * @param CheckRecord $checkRecord
     *
     * @return void
     */
    public function restored(CheckRecord $checkRecord)
    {
        //
    }

    /**
     * Handle the CheckRecord "force deleted" event.
     *
     * @param CheckRecord $checkRecord
     *
     * @return void
     */
    public function forceDeleted(CheckRecord $checkRecord)
    {
        //
    }
}
