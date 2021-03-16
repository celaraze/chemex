<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\CheckSoftwarePercentage;
use App\Admin\Metrics\SoftwareAboutToExpireCounts;
use App\Admin\Metrics\SoftwareCounts;
use App\Admin\Metrics\SoftwareExpiredCounts;
use App\Admin\Metrics\SoftwareWorthTrend;
use App\Http\Controllers\Controller;
use App\Support\Data;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;

class SoftwareStatisticsController extends Controller
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('software.records.index'));
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('software.categories.index'));
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('software.tracks.index'));
                $tab->add(Data::icon('statistics').trans('main.statistics'), null, true);
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('software.columns.index'));
                $row->column(12, $tab);
            })
            ->body(function (Row $row) {
                $row->column(12, new SoftwareWorthTrend());
                $row->column(3, new SoftwareCounts());
                $row->column(3, new CheckSoftwarePercentage());
                $row->column(3, new SoftwareAboutToExpireCounts());
                $row->column(3, new SoftwareExpiredCounts());
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }
}
