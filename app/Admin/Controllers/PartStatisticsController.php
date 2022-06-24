<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\CheckPartPercentage;
use App\Admin\Metrics\PartAboutToExpireCounts;
use App\Admin\Metrics\PartCounts;
use App\Admin\Metrics\PartExpiredCounts;
use App\Admin\Metrics\PartWorthTrend;
use App\Http\Controllers\Controller;
use App\Support\Data;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;

class PartStatisticsController extends Controller
{
    use ControllerHasTab;

    /**
     * 列表布局.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body($this->tab())
            ->body(function (Row $row) {
                $row->column(12, new PartWorthTrend());
                $row->column(3, new PartCounts());
                $row->column(3, new CheckPartPercentage());
                $row->column(3, new PartAboutToExpireCounts());
                $row->column(3, new PartExpiredCounts());
            });
    }

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->addLink(Data::icon('record') . trans('main.record'), admin_route('part.records.index'));
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('part.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('part.tracks.index'));
        $tab->add(Data::icon('statistics') . trans('main.statistics'), null, true);
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('part.columns.index'));
        $row->column(12, $tab);
        return $row;
    }

}
