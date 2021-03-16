<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\ServiceWorthTrend;
use App\Http\Controllers\Controller;
use App\Support\Data;
use App\Support\Support;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Tab;

class ServiceStatisticsController extends Controller
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('service.records.index'));
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('service.tracks.index'));
                $tab->addLink(Data::icon('issue').trans('main.issue'), admin_route('service.issues.index'));
                $tab->add(Data::icon('statistics').trans('main.statistics'), null, true);
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('service.columns.index'));
                $row->column(12, $tab);
            })
            ->body(function (Row $row) {
                $row->column(12, new ServiceWorthTrend());
                $services = Support::getServiceIssueStatus();
                $row->column(12, new Card(trans('main.service_status'), view('services_dashboard')
                    ->with('services', $services)));
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }
}
