<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ServiceRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Tab;

class ServiceColumnController extends AdminController
{
    use ControllerHasColumnSort;

    protected Repository $repository;

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
                $tab->addLink(Data::icon('statistics').trans('main.statistics'), admin_route('service.statistics'));
                $tab->add(Data::icon('column').trans('main.column'), $this->render(), true);
                $row->column(12, $tab);
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    public function repository(): ServiceRecord
    {
        return new ServiceRecord();
    }
}
