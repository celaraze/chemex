<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\RowAction\ServiceIssueUpdateAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\ServiceIssue;
use App\Support\Data;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Tools\Selector;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Tab;

/**
 * @property int status
 */
class ServiceIssueController extends AdminController
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
                $tab->add(Data::icon('issue').trans('main.issue'), $this->grid(), true);
                $tab->addLink(Data::icon('statistics').trans('main.statistics'), admin_route('service.statistics'));
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('service.columns.index'));
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
        return Grid::make(new ServiceIssue(['service']), function (Grid $grid) {
            $grid->model()->orderBy('status', 'ASC');

            $grid->column('id');
            $grid->column('service.name');
            $grid->column('issue');
            $grid->column('status')->using(Data::serviceIssueStatus());
            $grid->column('start');
            $grid->column('end');

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                // @permissions
                if ($this->status == 1 && Admin::user()->can('service.issue.update')) {
                    $actions->append(new ServiceIssueUpdateAction());
                }
            });

            /**
             * 快速搜索.
             */
            $grid->quickSearch('id', 'service.name', 'issue')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 规格筛选.
             */
            $grid->selector(function (Selector $selector) {
                $selector->select('status', [
                    1 => admin_trans_label('Status NG'),
                    2 => admin_trans_label('Status OK'),
                ]);
            });

            /**
             * 按钮控制.
             */
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableBatchActions();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
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
        return Show::make($id, new ServiceIssue(['service']), function (Show $show) {
            $show->field('id');
            $show->field('service.name');
            $show->field('issue');
            $show->field('status')->using(Data::serviceIssueStatus());
            $show->field('start');
            $show->field('end');
            $show->field('created_at');
            $show->field('updated_at');

            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Alert
     */
    protected function form(): Alert
    {
        return Data::unsupportedOperationWarning();
    }
}
