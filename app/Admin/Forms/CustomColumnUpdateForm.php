<?php

namespace App\Admin\Forms;

use App\Models\ColumnSort;
use App\Models\CustomColumn;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Form\NestedForm;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomColumnUpdateForm extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     * @return JsonResponse
     */
    public function handle(array $input): JsonResponse
    {
        try {
            $table_name = $this->payload['table_name'];
            $name = $this->payload['name'];
            $new_name = $input['new_name'] ?? null;
            $new_nick_name = $input['new_nick_name'] ?? null;
            $new_select_options = $input['new_select_options'] ?? null;

            if (!$table_name || !$name) {
                return $this->response()
                    ->error(trans('main.parameter_missing'));
            }

            $custom_column = CustomColumn::where('table_name', $table_name)
                ->where('name', $name)
                ->first();

            if (empty($custom_column)) {
                return $this->response()
                    ->error(trans('main.record_none'));
            }

            if (!empty($new_name)) {
                $exist_name = CustomColumn::where('table_name', $table_name)
                    ->where('name', $new_name)
                    ->first();
                if (!empty($exist_name)) {
                    return $this->response()
                        ->error(trans('main.record_same'));
                }
                $custom_column->name = $new_name;
            } else {
                $custom_column->name = $name;
            }

            if (!empty($new_nick_name)) {
                $custom_column->nick_name = $new_nick_name;
            }

            if (!empty($new_select_options)) {
                $custom_column->select_options = $new_select_options;
            }

            /**
             * 更新自定义字段的迁移动作.
             */
            if ($custom_column->save()) {
                Schema::table($table_name, function (Blueprint $table) use ($name, $new_name) {
                    $table->renameColumn($name, $new_name);
                });
                // 排序表跟随
                $column_sort = ColumnSort::where('table_name', $table_name)
                    ->where('name', $name)
                    ->first();
                if (empty($column_sort)) {
                    $column_sort = new ColumnSort();
                    $column_sort->table_name = $table_name;
                    $column_sort->name = $new_name;
                    $column_sort->order = 99;
                } else {
                    $column_sort->name = $new_name;
                }
                $column_sort->save();
                return $this->response()
                    ->success(trans('main.success'))
                    ->refresh();
            } else {
                return $this->response()
                    ->error(trans('main.fail'));
            }
        } catch (Exception $exception) {
            return $this->response()
                ->refresh();
        }
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $data = $this->payload;
        $this->text('name')
            ->readOnly()
            ->default($data['name']);
        $this->text('new_name');
        $this->text('new_nick_name');

        $custom_column = CustomColumn::where('table_name', $data['table_name'])
            ->where('name', $data['name'])
            ->first();
        if (!empty($custom_column) && $custom_column->type == 'select') {
            $this->table('new_select_options', function (NestedForm $table) {
                $table->text('item');
            })->default($custom_column->select_options);
        }
    }
}
