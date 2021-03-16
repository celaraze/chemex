<?php

namespace App\Admin\Metrics;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Models\SoftwareRecord;
use Closure;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Exception;
use Illuminate\Contracts\Support\Renderable;

class CheckSoftwarePercentage extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return $this
     */
    public function content($content): CheckSoftwarePercentage
    {
        if ($content instanceof LazyGrid) {
            $content->simple();
        }

        $software_records_all = SoftwareRecord::count();
        $check_record = CheckRecord::where('check_item', 'software')->where('status', 0)->first();
        if (!empty($check_record)) {
            $check_tracks_counts = CheckTrack::where('check_id', $check_record->id)
                ->where('status', '!=', 0)
                ->get()
                ->count();
            $done_counts = trans('main.check_process').$check_tracks_counts.' / '.$software_records_all;

            try {
                $percentage = round($check_tracks_counts / $software_records_all * 100, 2);
            } catch (Exception $exception) {
                $percentage = 0;
            }
        } else {
            $done_counts = trans('main.check_none');
            $percentage = 0;
        }

        $display = <<<HTML
    <div class="progress">
        <div class="progress-bar bg-info" style="background: rgba(70,160,153,1);width: {$percentage}%"></div>
    </div>
HTML;

        if ($percentage == 0) {
            $display = '';
        }

        $html = <<<HTML
<div class="info-box" style="background:transparent;margin-bottom: 0;padding: 0;">
<span class="info-box-icon"><i class="feather icon-disc" style="color:rgba(33,115,186,1);"></i></span>
    <div class="info-box-content" style="display: flex;flex-direction: column;justify-content: center;">
    <span class="info-box-number">{$done_counts}</span>
    {$display}
    <span class="progress-description">
        {$percentage}%
    </span>
    </div>
</div>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();

        return $this;
    }
}
