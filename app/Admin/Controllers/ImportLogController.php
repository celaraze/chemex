<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ImportLog;
use App\Support\Data;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Alert;

class ImportLogController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new ImportLog(['operator']), function (Grid $grid) {
            $grid->model()->orderByDesc('id');
            $grid->column('id');
            $grid->column('item')
                ->display(function ($item) {
                    return Data::itemNameByModel()[$item];
                })
                ->link(function () {
                    return admin_route('import_log_details.index', ['log_id' => $this->id]);
                }, false);
            $grid->column('succeed');
            $grid->column('failed');
            $grid->column('operator.name');
            $grid->column('created_at');
            $grid->column('updated_at');

            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->disableActions();

            /**
             * 筛选器.
             */
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param int $id
     *
     * @return \Dcat\Admin\Widgets\Alert
     */
    protected function detail(int $id): Alert
    {
        return Data::unsupportedOperationWarning();
    }

    /**
     * Make a form builder.
     *
     * @return \Dcat\Admin\Widgets\Alert
     */
    protected function form(): Alert
    {
        return Data::unsupportedOperationWarning();
    }
}
