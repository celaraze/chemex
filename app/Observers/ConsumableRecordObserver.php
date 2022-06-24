<?php

namespace App\Observers;

use App\Models\ConsumableRecord;
use App\Models\ConsumableTrack;

class ConsumableRecordObserver
{
    /**
     * Handle the ConsumableRecord "created" event.
     *
     * @param ConsumableRecord $consumableRecord
     * @return void
     */
    public function created(ConsumableRecord $consumableRecord)
    {
        //
    }

    /**
     * Handle the ConsumableRecord "updated" event.
     *
     * @param ConsumableRecord $consumableRecord
     * @return void
     */
    public function updated(ConsumableRecord $consumableRecord)
    {
        //
    }

    /**
     * Handle the ConsumableRecord "deleted" event.
     *
     * @param ConsumableRecord $consumableRecord
     * @return void
     */
    public function deleted(ConsumableRecord $consumableRecord)
    {
        // 软删除设备归属记录
        $consumable_tracks = ConsumableTrack::where('consumable_id', $consumableRecord->id)->get();
        foreach ($consumable_tracks as $consumable_track) {
            $consumable_track->delete();
        }
    }

    /**
     * Handle the ConsumableRecord "restored" event.
     *
     * @param ConsumableRecord $consumableRecord
     * @return void
     */
    public function restored(ConsumableRecord $consumableRecord)
    {
        //
    }

    /**
     * Handle the ConsumableRecord "force deleted" event.
     *
     * @param ConsumableRecord $consumableRecord
     * @return void
     */
    public function forceDeleted(ConsumableRecord $consumableRecord)
    {
        //
    }
}
