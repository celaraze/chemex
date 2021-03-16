<?php

namespace App\Admin\Metrics;

use App\Models\PartRecord;
use Closure;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;

class PartCounts extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return $this
     */
    public function content($content): PartCounts
    {
        if ($content instanceof LazyGrid) {
            $content->simple();
        }
        $counts = PartRecord::count();
        $part_counts = trans('main.part_counts');
        $html = <<<HTML
<div class="small-box" style="margin-bottom: 0;border-radius: .25rem">
  <div class="inner">
    <h4>{$counts}</h4>
    <p>{$part_counts}</p>
  </div>
</div>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();

        return $this;
    }
}
