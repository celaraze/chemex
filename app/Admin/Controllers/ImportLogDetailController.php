<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ImportLogDetail;
use App\Support\Data;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Alert;

class ImportLogDetailController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new ImportLogDetail(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('log_id');
            $grid->column('status')->using(Data::successOrFail());
            $grid->column('log');
            $grid->column('created_at');
            $grid->column('updated_at');

            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->disableActions();

            /**
             * 筛选器.
             */
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('log_id');
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                $url = admin_route('import_logs.index');
                $tools->append("<a class='btn btn-warning' href='$url'>返回导入日志</a>");
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
