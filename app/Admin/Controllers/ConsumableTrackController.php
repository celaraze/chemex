<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ConsumableTrack;
use App\Support\Data;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tab;

class ConsumableTrackController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(Data::icon('record').trans('main.record'), admin_route('consumable.records.index'));
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('consumable.categories.index'));
                $tab->add(Data::icon('track').trans('main.history'), $this->grid(), true);
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('consumable.columns.index'));
                $row->column(12, $tab);
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new ConsumableTrack(['consumable', 'user']), function (Grid $grid) {
            $grid->model()->withTrashed()->orderBy('created_at', 'DESC');

            $grid->column('id');
            $grid->column('consumable.name');
            $grid->column('operator');
            $grid->column('number');
            $grid->column('change');
            $grid->column('user.name');
            $grid->column('purchased');
            $grid->column('expired');

            /**
             * 筛选.
             */
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });

            /**
             * 按钮控制.
             */
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
            $grid->disableBatchActions();
            $grid->disableRowSelector();
            $grid->toolsWithOutline(false);
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new ConsumableTrack(['consumable', 'user']), function (Show $show) {
            $show->field('id');
            $show->field('consumable.name');
            $show->field('operator');
            $show->field('number');
            $show->field('change');
            $show->field('user.name');
            $show->field('purchased');
            $show->field('expired');
            $show->field('created_at');
            $show->field('updated_at');

            /**
             * 按钮控制.
             */
            $show->disableEditButton();
            $show->disableDeleteButton();
            $show->disableQuickEdit();
        });
    }
}
