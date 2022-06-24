<?php

namespace App\Admin\Actions\Tree\RowAction;

use App\Models\ColumnSort;
use App\Models\CustomColumn;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Tree\RowAction;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use JetBrains\PhpStorm\ArrayShape;

class CustomColumnDeleteAction extends RowAction
{
    protected ?string $table_name;
    protected ?string $custom_column_name;

    public function __construct(string $table_name = null, string $custom_column_name = null)
    {
        parent::__construct();
        $this->title = '<i class="fa fa-fw feather icon-trash"></i> ' . admin_trans_label('Delete');
        $this->table_name = $table_name;
        $this->custom_column_name = $custom_column_name;
    }

    /**
     * 处理动作逻辑.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        try {
            $table_name = $request->table_name;
            $name = $request->name;
            $custom_column = CustomColumn::where('table_name', $table_name)
                ->where('name', $name)
                ->first();
            if (empty($custom_column)) {
                return $this->response()
                    ->error(trans('main.record_none'));
            }

            Schema::table($table_name, function (Blueprint $table) use ($custom_column) {
                $table->dropColumn($custom_column->name);
            });

            // 排序表跟随
            $column_sort = ColumnSort::where('table_name', $table_name)
                ->where('name', $name)
                ->first();
            if (!empty($column_sort)) {
                $column_sort->delete();
            }
            $custom_column->delete();
            return $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $exception) {
            return $this->response()
                ->error(trans('main.fail') . '：' . $exception->getMessage());
        }
    }

    /**
     * 对话框.
     *
     * @return string[]
     */
    public function confirm(): array
    {
        return [admin_trans_label('Delete Confirm'), admin_trans_label('Delete Confirm Description')];
    }

    #[ArrayShape(['table_name' => "null|string", 'name' => "null|string"])]
    protected function parameters(): array
    {
        return [
            'table_name' => $this->table_name,
            'name' => $this->custom_column_name
        ];
    }
}
