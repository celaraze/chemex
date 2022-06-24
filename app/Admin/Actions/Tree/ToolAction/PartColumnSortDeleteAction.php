<?php

namespace App\Admin\Actions\Tree\ToolAction;

use App\Models\ColumnSort;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Tree\AbstractTool;
use Illuminate\Http\Request;

class PartColumnSortDeleteAction extends AbstractTool
{
    public function __construct()
    {
        parent::__construct();
        $this->title = admin_trans_label('Custom Column Sort Delete');
    }

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $column_sorts = ColumnSort::where('table_name', 'part_records')
            ->get();
        foreach ($column_sorts as $column_sort) {
            $column_sort->delete();
        }
        return $this->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * @return array
     */
    public function confirm(): array
    {
        return [admin_trans_label('Custom Column Delete Confirm')];
    }
}
