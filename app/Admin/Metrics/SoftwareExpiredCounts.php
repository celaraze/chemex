<?php

namespace App\Admin\Metrics;

use App\Models\SoftwareRecord;
use Closure;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;

class SoftwareExpiredCounts extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return SoftwareExpiredCounts
     */
    public function content($content): SoftwareExpiredCounts
    {
        if ($content instanceof LazyGrid) {
            $content->simple();
        }

        $counts = SoftwareRecord::where('expired', '<', date(now()))->count();
        $software_expired_counts = trans('main.software_expired_counts');
        $html = <<<HTML
<div class="info-box" style="background:transparent;margin-bottom: 0;padding: 0;">
    <span class="info-box-icon"><i class="feather icon-disc" style="color:rgba(178,68,71,1);"></i></span>
    <div class="info-box-content">
        <span class="info-box-text mt-1">{$software_expired_counts}</span>
        <span class="info-box-number">{$counts}</span>
    </div>
</div>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();
        $this->style('margin-top:-0.1rem');

        return $this;
    }
}
