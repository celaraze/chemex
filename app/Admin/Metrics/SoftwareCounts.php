<?php

namespace App\Admin\Metrics;

use App\Models\SoftwareRecord;
use Closure;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;

class SoftwareCounts extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return $this
     */
    public function content($content): SoftwareCounts
    {
        $counts = SoftwareRecord::count();
        $software_counts = trans('main.software_counts');
        $html = <<<HTML
<div class="small-box" style="margin-bottom: 0;border-radius: .25rem">
  <div class="inner">
    <h4>{$counts}</h4>
    <p>{$software_counts}</p>
  </div>
</div>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();

        return $this;
    }
}
