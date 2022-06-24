<?php

namespace App\Support;

use App\Admin\Repositories\ConsumableRecord;
use App\Admin\Repositories\DeviceRecord;
use App\Admin\Repositories\PartRecord;
use App\Admin\Repositories\ServiceRecord;
use App\Admin\Repositories\SoftwareRecord;
use App\Models\ConsumableCategory;
use App\Models\Department;
use App\Models\DeviceCategory;
use App\Models\PartCategory;
use App\Models\SoftwareCategory;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Alert;
use JetBrains\PhpStorm\ArrayShape;

class Data
{
    /**
     * 发行方式.
     *
     * @return string[]
     */
    #[ArrayShape(['u' => "string", 'o' => "string", 'f' => "string", 'b' => "string"])]
    public static function distribution(): array
    {
        return [
            'u' => '未知',
            'o' => '开源',
            'f' => '免费',
            'b' => '商业授权',
        ];
    }

    /**
     * 性别.
     *
     * @return string[]
     */
    #[ArrayShape(['无' => "string", '男' => "string", '女' => "string"])]
    public static function genders(): array
    {
        return [
            '无' => '无',
            '男' => '男',
            '女' => '女',
        ];
    }

    /**
     * 物件.
     *
     * @return string[]
     */
    #[ArrayShape(['DeviceRecord' => "string", 'PartRecord' => "string", 'SoftwareRecord' => "string"])]
    public static function items(): array
    {
        return [
            get_class(new \App\Models\DeviceRecord()) => '设备',
            get_class(new \App\Models\PartRecord()) => '配件',
            get_class(new \App\Models\SoftwareRecord()) => '软件',
        ];
    }

    /**
     * 盘点任务状态
     *
     * @return string[]
     */
    public static function checkRecordStatus(): array
    {
        return [
            0 => '进行',
            1 => '完成',
            2 => '中止',
        ];
    }

    /**
     * 维修状态
     *
     * @return string[]
     */
    public static function maintenanceStatus(): array
    {
        return [
            0 => '等待处理',
            1 => '处理完毕',
            2 => '取消',
        ];
    }

    /**
     * 盘点追踪状态
     *
     * @return string[]
     */
    public static function checkTrackStatus(): array
    {
        return [
            0 => '未盘点',
            1 => '盘到',
            2 => '未盘到',
        ];
    }

    /**
     * 服务异常状态
     *
     * @return string[]
     */
    public static function serviceIssueStatus(): array
    {
        return [
            0 => '正常',
            1 => '故障',
            2 => '恢复',
            3 => '暂停',
        ];
    }

    /**
     * 软件标签.
     *
     * @return array
     */
    #[ArrayShape(['windows' => "string[]", 'macos' => "string[]", 'linux' => "string[]", 'android' => "string[]", 'ios' => "string[]"])]
    public static function softwareTags(): array
    {
        return [
            'windows' => [
                'windows',
                'win10',
                'win8',
                'win7',
            ],
            'macos' => [
                'mac',
                'cheetah',
                'puma',
                'jaguar',
                'panther',
                'tiger',
                'leopard',
                'lion',
                'mavericks',
                'yosemite',
                'capitan',
                'sierra',
                'mojave',
                'catalina',
                'bigsur',
            ],
            'linux' => [
                'linux',
                'centos',
                'ubuntu',
                'kali',
                'debian',
                'arch',
                'deepin',
            ],
            'android' => [
                'cupcake',
                'donut',
                'eclair',
                'froyo',
                'gingerbread',
                'honeycomb',
                'icecreamsandwich',
                'jellybean',
                'kitkat',
                'lollipop',
                'marshmallow',
                'nougat',
                'oreo',
                'pie',
            ],
            'ios' => [
                'ios',
            ],
        ];
    }

    /**
     * 返回不支持操作的错误信息 warning.
     *
     * @return Alert
     */
    public static function unsupportedOperationWarning(): Alert
    {
        $alert = Alert::make('此功能不允许通过此操作实现。', '未提供的操作');
        $alert->warning();
        $alert->icon('feather icon-alert-triangle');

        return $alert;
    }

    /**
     * 返回控制器图标.
     *
     * @param $string
     *
     * @return string
     */
    public static function icon($string): string
    {
        $array = [
            'record' => '<i class="fa feather icon-list"></i> ',
            'category' => '<i class="fa feather icon-pie-chart"></i> ',
            'track' => '<i class="fa feather icon-archive"></i> ',
            'issue' => '<i class="fa feather icon-alert-triangle"></i> ',
            'user' => '<i class="fa feather icon-users"></i> ',
            'department' => '<i class="fa feather icon-copy"></i> ',
            'role' => '<i class="fa feather icon-users"></i> ',
            'permission' => '<i class="fa feather icon-lock"></i> ',
            'statistics' => '<i class="fa feather icon-bar-chart-2"></i> ',
            'column' => '<i class="fa feather icon-edit-2"></i> ',
            'history' => '<i class="fa feather icon-clock"></i> ',
        ];

        return $array[$string];
    }

    /**
     * 保固状态
     *
     * @return string[]
     */
    #[ArrayShape(['one day' => "string", 'three day' => "string", 'one week' => "string", 'one month' => "string", 'normal' => "string", 'none' => "string", 'default' => "string"])]
    public static function expiredStatus(): array
    {
        return [
            'one day' => '一天内过期',
            'three day' => '三天内过期',
            'one week' => '一周内过期',
            'one month' => '一月内过期',
            'normal' => '正常',
            'none' => '无效的设备',
            'default' => '错误',
        ];
    }

    /**
     * 保固状态颜色.
     *
     * @return array
     */
    #[ArrayShape(['one day' => "string", 'three day' => "string", 'one week' => "string", 'one month' => "string", 'normal' => "string", 'none' => "string", 'default' => "string"])]
    public static function expiredStatusColors(): array
    {
        return [
            'one day' => 'danger',
            'three day' => 'danger',
            'one week' => 'warning',
            'one month' => 'warning',
            'normal' => 'success',
            'none' => 'primary',
            'default' => Admin::color()->gray(),
        ];
    }

    /**
     * 返回时间尺度.
     *
     * @return string[]
     */
    #[ArrayShape(['day' => "string", 'month' => "string", 'year' => "string"])]
    public static function timeScales(): array
    {
        return [
            'day' => '天',
            'month' => '月',
            'year' => '年',
        ];
    }

    /**
     * 返回emoji.
     *
     * @return string[]
     */
    #[ArrayShape(['happy' => "string", 'normal' => "string", 'sad' => "string"])]
    public static function emoji(): array
    {
        return [
            'happy' => '😀 愉快',
            'normal' => '😐 一般',
            'sad' => '😟 悲伤',
        ];
    }

    /**
     * 返回优先级的键值对.
     *
     * @return string[]
     */
    #[ArrayShape(['high' => "string", 'normal' => "string", 'low' => "string"])]
    public static function priority(): array
    {
        return [
            'high' => '高',
            'normal' => '普通',
            'low' => '低',
        ];
    }

    /**
     * 返回自定义字段的类型.
     *
     * @return string[]
     */
    #[ArrayShape(['string' => "string", 'date' => "string", 'dateTime' => "string", 'integer' => "string", 'float' => "string", 'double' => "string", 'longText' => "string", 'select' => "string"])]
    public static function customColumnTypes(): array
    {
        return [
            'string' => '字符串',
            'date' => '日期',
            'dateTime' => '日期时间',
            'integer' => '整数',
            'float' => '浮点',
            'double' => '双精度',
            'longText' => '长文本',
            'select' => '选项',
        ];
    }

    /**
     * 表名返回资产名.
     *
     * @return string[]
     */
    public static function itemNameByTableName(): array
    {
        return [
            (new DeviceRecord())->getTable() => trans('main.device'),
            (new PartRecord())->getTable() => trans('main.part'),
            (new SoftwareRecord())->getTable() => trans('main.software'),
            (new ConsumableRecord())->getTable() => trans('main.consumable'),
            (new ServiceRecord())->getTable() => trans('main.service'),
        ];
    }

    /**
     * 模型返回资产名.
     *
     * @return array
     */
    public static function itemNameByModel(): array
    {
        return [
            get_class(new \App\Models\DeviceRecord()) => trans('main.device'),
            get_class(new \App\Models\PartRecord()) => trans('main.part'),
            get_class(new \App\Models\SoftwareRecord()) => trans('main.software'),
            get_class(new \App\Models\ConsumableRecord()) => trans('main.consumable'),
            get_class(new \App\Models\ServiceRecord()) => trans('main.service'),
            get_class(new Department()) => trans('main.department'),
            get_class(new User()) => trans('main.user'),
            get_class(new DeviceCategory()) => trans('main.device_category'),
            get_class(new PartCategory()) => trans('main.part_category'),
            get_class(new SoftwareCategory()) => trans('main.software_category'),
            get_class(new ConsumableCategory()) => trans('main.consumable_category'),
        ];
    }

    /**
     * 成功或失败.
     *
     * @return string[]
     */
    public static function successOrFail(): array
    {
        return ['失败', '成功'];
    }
}
