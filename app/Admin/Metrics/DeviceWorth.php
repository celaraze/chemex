<?php

namespace App\Admin\Metrics;

use Closure;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class DeviceWorth extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return DeviceWorth
     */
    public function content($content): DeviceWorth
    {
        if ($content instanceof LazyGrid) {
            $content->simple();
        }
        $total = DB::select('SELECT SUM(price) as total from device_records WHERE deleted_at IS NULL');
        $total = $total[0]->total;
        if (empty($total)) {
            $total = 0;
        }
        $device_worth = trans('main.device_worth');
        $html = <<<HTML
<div class="small-box" style="margin-bottom: 0;border-radius: .25rem">
    <div class="inner">
        <h4>{$total}</h4>
        <p>{$device_worth}</p>
    </div>
</div>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();

        return $this;
    }
}
