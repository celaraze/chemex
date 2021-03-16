<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ConsumableRecord;
use App\Support\Data;
use App\Traits\ControllerHasColumnSort;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Widgets\Tab;

class ConsumableColumnController extends AdminController
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
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('consumable.records.index'));
                $tab->addLink(Data::icon('track').trans('main.category'), admin_route('consumable.categories.index'));
                $tab->addLink(Data::icon('issue').trans('main.track'), admin_route('consumable.tracks.index'));
                $tab->add(Data::icon('column').trans('main.column'), $this->render(), true);
                $row->column(12, $tab);
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    public function repository(): ConsumableRecord
    {
        return new ConsumableRecord();
    }
}
