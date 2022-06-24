<?php

namespace App\Observers;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Models\MaintenanceRecord;
use App\Models\PartRecord;
use App\Models\PartTrack;

class PartRecordObserver
{
    /**
     * Handle the PartRecord "created" event.
     *
     * @param PartRecord $partRecord
     * @return void
     */
    public function created(PartRecord $partRecord)
    {
        //
    }

    /**
     * Handle the PartRecord "updated" event.
     *
     * @param PartRecord $partRecord
     * @return void
     */
    public function updated(PartRecord $partRecord)
    {
        //
    }

    /**
     * Handle the PartRecord "deleted" event.
     *
     * @param PartRecord $partRecord
     * @return void
     */
    public function deleted(PartRecord $partRecord)
    {
        // 配件删除时，同时删除全部归属记录
        $part_tracks = PartTrack::where('part_id', $partRecord->id)->get();
        foreach ($part_tracks as $part_track) {
            $part_track->delete();
        }

        // 软删除配件盘点记录
        $check_records = CheckRecord::where('check_item', 'part')->get();
        foreach ($check_records as $check_record) {
            $check_tracks = CheckTrack::where('check_id', $check_record->id)->get();
            foreach ($check_tracks as $check_track) {
                $check_track->delete();
            }
        }

        // 软删除设备故障记录
        $maintenance_records = MaintenanceRecord::where('item', 'part')
            ->where('item_id', $partRecord->id)
            ->get();
        foreach ($maintenance_records as $maintenance_record) {
            $maintenance_record->delete();
        }
    }

    /**
     * Handle the PartRecord "restored" event.
     *
     * @param PartRecord $partRecord
     * @return void
     */
    public function restored(PartRecord $partRecord)
    {
        //
    }

    /**
     * Handle the PartRecord "force deleted" event.
     *
     * @param PartRecord $partRecord
     * @return void
     */
    public function forceDeleted(PartRecord $partRecord)
    {
        //
    }
}
