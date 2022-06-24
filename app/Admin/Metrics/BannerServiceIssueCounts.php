<?php

namespace App\Admin\Metrics;

use App\Models\ServiceIssue;
use Closure;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;

class BannerServiceIssueCounts extends Card
{
    /**
     * @param string|Closure|Renderable|LazyWidget $content
     *
     * @return BannerServiceIssueCounts
     */
    public function content($content): BannerServiceIssueCounts
    {
        if ($content instanceof LazyGrid) {
            $content->simple();
        }
        $value = ServiceIssue::count();
        $text = trans('main.service_issue_counts');
        $route = admin_route('service.issues.index');
        $bg = url('static/images/yellow.png');
        $html = <<<HTML
<a href="$route">
    <div class="small-box" style="padding:0 20px;height:100px;margin-bottom: 0;border-radius: .25rem;background: url('{$bg}') no-repeat;background-size: 100% 100%;">
    <div class="inner">
        <h4 style="color: white;font-size: 30px;text-shadow: #888888 1px 1px 2px;">{$value}</h4>
        <p style="color: white;text-shadow: #888888 1px 1px 2px;">{$text}</p>
    </div>
</div>
</a>
HTML;

        $this->content = $this->formatRenderable($html);
        $this->noPadding();

        return $this;
    }
}
