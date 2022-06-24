<?php

namespace App\Traits;

use App\Admin\Repositories\PartTrack;
use App\Admin\Repositories\ServiceTrack;
use App\Admin\Repositories\SoftwareTrack;
use App\Support\Data;
use Dcat\Admin\Grid;

trait ControllerHasDeviceRelatedGrid
{
    /**
     * 查看某个设备下面的全部关联配件&软件&服务程序，并且返回它们的渲染集合.
     *
     * @param $device_id
     *
     * @return array
     */
    public static function hasDeviceRelated($device_id): array
    {
        $result = [];
        // 配件
        $grid = Grid::make(new PartTrack(['part', 'part.category', 'part.vendor']), function (Grid $grid) use ($device_id) {
            $grid->model()->where('device_id', '=', $device_id);
            $grid->tableCollapse(false);
            $grid->withBorder();

            $grid->column('id');
            $grid->column('part.category.name');
            $grid->column('part.asset_number')->link(function () {
                if (!empty($this->part)) {
                    return admin_route('part.records.show', [$this->part['id']]);
                }
            });
            $grid->column('part.specification');
            $grid->column('part.sn');
            $grid->column('part.vendor.name');

            $grid->disableToolbar();
            $grid->disableBatchActions();
            $grid->disableRowSelector();
            $grid->disableActions();
        });
        $result['part'] = $grid;

        // 软件
        $grid = Grid::make(new SoftwareTrack(['software', 'software.category', 'software.vendor']), function (Grid $grid) use ($device_id) {
            $grid->model()->where('device_id', '=', $device_id);
            $grid->tableCollapse(false);
            $grid->withBorder();

            $grid->column('id');
            $grid->column('software.category.name');
            $grid->column('software.name')->link(function () {
                if (!empty($this->software)) {
                    return admin_route('software.records.show', [$this->software['id']]);
                }
            });
            $grid->column('software.version');
            $grid->column('software.distribution')->using(Data::distribution());
            $grid->column('software.vendor.name');

            $grid->disableToolbar();
            $grid->disableBatchActions();
            $grid->disableRowSelector();
            $grid->disableActions();
        });
        $result['software'] = $grid;

        // 服务
        $grid = Grid::make(new ServiceTrack(['service']), function (Grid $grid) use ($device_id) {
            $grid->model()->where('device_id', '=', $device_id);
            $grid->tableCollapse(false);
            $grid->withBorder();

            $grid->column('id');
            $grid->column('service.name')->link(function () {
                if (!empty($this->service)) {
                    return admin_route('service.records.show', [$this->service['id']]);
                }
            });

            $grid->disableToolbar();
            $grid->disableBatchActions();
            $grid->disableRowSelector();
            $grid->disableActions();
        });
        $result['service'] = $grid;

        return $result;
    }
}
