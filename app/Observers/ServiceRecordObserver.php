<?php

namespace App\Observers;

use App\Models\ServiceIssue;
use App\Models\ServiceRecord;
use App\Models\ServiceTrack;

class ServiceRecordObserver
{
    /**
     * Handle the ServiceRecord "created" event.
     *
     * @param ServiceRecord $serviceRecord
     * @return void
     */
    public function created(ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Handle the ServiceRecord "updated" event.
     *
     * @param ServiceRecord $serviceRecord
     * @return void
     */
    public function updated(ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Handle the ServiceRecord "deleted" event.
     *
     * @param ServiceRecord $serviceRecord
     * @return void
     */
    public function deleted(ServiceRecord $serviceRecord)
    {
        // 软删除服务归属记录
        $service_tracks = ServiceTrack::where('service_id', $serviceRecord->id)->get();
        foreach ($service_tracks as $service_track) {
            $service_track->delete();
        }

        // 软删除服务故障记录
        $service_issues = ServiceIssue::where('service_id', $serviceRecord->id)
            ->where('item_id', $serviceRecord->id)
            ->get();
        foreach ($service_issues as $service_issue) {
            $service_issue->delete();
        }
    }

    /**
     * Handle the ServiceRecord "restored" event.
     *
     * @param ServiceRecord $serviceRecord
     * @return void
     */
    public function restored(ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Handle the ServiceRecord "force deleted" event.
     *
     * @param ServiceRecord $serviceRecord
     * @return void
     */
    public function forceDeleted(ServiceRecord $serviceRecord)
    {
        //
    }
}
