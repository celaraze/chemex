<?php

namespace App\Traits;

use App\Grid;
use App\Models\CustomColumn;
use App\Show;
use Dcat\Admin\Form\Field;
use Dcat\Admin\Form\Row;

trait ControllerHasCustomColumns
{
    /**
     * 构建自定义字段Form结构.
     *
     * @param $custom_column
     * @param \Dcat\Admin\Form\Row $row
     * @return \Dcat\Admin\Form\Field
     */
    public static function makeForm($custom_column, Row $row): Field
    {
        switch ($custom_column->type) {
            case 'date':
                $row = $row->date($custom_column->name, $custom_column->nick_name);
                break;
            case 'dateTime':
                $row = $row->datetime($custom_column->name, $custom_column->nick_name);
                break;
            case 'integer':
                $row = $row->number($custom_column->name, $custom_column->nick_name);
                break;
            case 'double':
            case 'float':
                $row = $row->currency($custom_column->name, $custom_column->nick_name);
                break;
            case 'longText':
                $row = $row->textarea($custom_column->name, $custom_column->nick_name);
                break;
            case 'select':
                $options = [];
                foreach ($custom_column->select_options as $select_option) {
                    $options[$select_option['item']] = $select_option['item'];
                }
                $row = $row->select($custom_column->name, $custom_column->nick_name)
                    ->options($options);
                break;
            default:
                $row = $row->text($custom_column->name, $custom_column->nick_name);
        }
        if ($custom_column->must == 1) {
            $row->width()->required();
        }

        return $row;
    }

    /**
     * 构建自定义字段Detail结构.
     *
     * @param $table_name
     * @param Show $show
     * @param $column_sorts
     * @return Show
     */
    public static function makeDetail($table_name, Show $show, $column_sorts): Show
    {
        foreach (self::getCustomColumns($table_name) as $custom_column) {
            $show->field($custom_column->name, $custom_column->nick_name, $column_sorts);
        }

        return $show;
    }

    /**
     * 获取自定义字段.
     *
     * @param $table_name
     *
     * @return mixed
     */
    public static function getCustomColumns($table_name): mixed
    {
        return CustomColumn::where('table_name', $table_name)->get();
    }

    /**
     * 构建自定义字段Grid结构.
     *
     * @param $table_name
     * @param Grid $grid
     * @param $column_sorts
     *
     * @return Grid
     */
    public static function makeGrid($table_name, Grid $grid, $column_sorts): Grid
    {
        foreach (self::getCustomColumns($table_name) as $custom_column) {
            $grid->column($custom_column->name, $custom_column->nick_name, $column_sorts);
        }

        return $grid;
    }

    /**
     * 构建自定义字段QuickSearch结构.
     *
     * @param $table_name
     *
     * @return array
     */
    public static function makeQuickSearch($table_name): array
    {
        $keys = [];
        foreach (self::getCustomColumns($table_name) as $custom_column) {
            array_push($keys, $custom_column->name);
        }

        return $keys;
    }

    /**
     * 构建自定义字段Filter结构.
     *
     * @param $table_name
     * @param $filter
     */
    public static function makeFilter($table_name, $filter)
    {
        foreach (self::getCustomColumns($table_name) as $custom_column) {
            if ($custom_column->type == 'select') {
                $options = [];
                foreach ($custom_column->select_options as $select_option) {
                    $options[$select_option['item']] = $select_option['item'];
                }
                $filter->equal($custom_column->name, $custom_column->nick_name)->select($options);
            } else {
                $filter->equal($custom_column->name, $custom_column->nick_name);
            }
        }
    }
}
