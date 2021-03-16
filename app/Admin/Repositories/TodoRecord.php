<?php

namespace App\Admin\Repositories;

use App\Models\ColumnSort;
use App\Models\TodoRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Schema;

class TodoRecord extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public static function getTable(): string
    {
        $model = new Model();

        return $model->getTable();
    }

    public function toTree(): array
    {
        $array = [];
        $model = new Model();
        $table_name = $model->getTable();
        $db_columns = Schema::getColumnListing($table_name);
        $model_columns = ColumnSort::where('table_name', $table_name)->get()->toArray();
        if (empty($model_columns)) {
            foreach ($db_columns as $key => $db_column) {
                $model = new Model();
                $model->id = $key;
                $model->title = admin_trans_field($db_column);
                $model->parent_id = 0;
                $model->order = $key;
                array_push($array, $model);
            }
        } else {
            foreach ($db_columns as $key => $db_column) {
                foreach ($model_columns as $model_column) {
                    // 如果表字段在排序表中有了
                    if ($db_column == $model_column['field']) {
                        $model = new Model();
                        $model->id = $key;
                        $model->title = admin_trans_field($db_column);
                        $model->parent_id = 0;
                        $model->order = $model_column['order'];
                        array_push($array, $model);
                    }
                }
            }
        }
        usort($array, function ($a, $b) {
            return $a->order < $b->order ? -1 : 1;
        });

        return $array;
    }
}
