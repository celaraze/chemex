<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\CheckDevicePercentage;
use App\Admin\Metrics\DeviceAboutToExpireCounts;
use App\Admin\Metrics\DeviceCounts;
use App\Admin\Metrics\DeviceExpiredCounts;
use App\Admin\Metrics\DeviceWorthTrend;
use App\Http\Controllers\Controller;
use App\Support\Data;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;

class DeviceStatisticsController extends Controller
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('device.records.index'));
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('device.categories.index'));
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('device.tracks.index'));
                $tab->add(Data::icon('statistics').trans('main.statistics'), null, true);
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('device.columns.index'));
                $row->column(12, $tab);
            })
            ->body(function (Row $row) {
                $row->column(12, new DeviceWorthTrend());
                $row->column(3, new DeviceCounts());
                $row->column(3, new CheckDevicePercentage());
                $row->column(3, new DeviceAboutToExpireCounts());
                $row->column(3, new DeviceExpiredCounts());
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }
}
