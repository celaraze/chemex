<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\DeviceRecordBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\DeviceRecordBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\DeviceRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\DeviceRecordDeleteAction;
use App\Admin\Actions\Grid\RowAction\MaintenanceRecordCreateAction;
use App\Admin\Actions\Grid\ToolAction\DevicePrintListAction;
use App\Admin\Actions\Grid\ToolAction\DevicePrintTagAction;
use App\Admin\Actions\Grid\ToolAction\DeviceRecordImportAction;
use App\Admin\Actions\Show\DeviceRecordDeleteTrackAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\DeviceRecord;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\Department;
use App\Models\DepreciationRule;
use App\Models\DeviceCategory;
use App\Models\VendorRecord;
use App\Services\DeviceService;
use App\Services\ExpirationService;
use App\Services\ExportService;
use App\Show;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use App\Traits\ControllerHasDeviceRelatedGrid;
use App\Traits\ControllerHasTab;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

/**
 * @property int id
 * @property float price
 * @property string purchased
 * @property int depreciation_rule_id
 * @property string deleted_at
 * @property  string asset_number
 *
 * @method isLend()
 * @method track()
 * @method status()
 */
class DeviceRecordController extends AdminController
{
    use ControllerHasDeviceRelatedGrid;
    use ControllerHasCustomColumns;
    use ControllerHasTab;

    /**
     * 标签布局.
     * @return Row
     */
    public function tab(): Row
    {
        $row = new Row();
        $tab = new Tab();
        $tab->add(Data::icon('record') . trans('main.record'), $this->renderGrid(), true);
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('device.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('device.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('device.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('device.columns.index'));
        $row->column(12, $tab);
        return $row;
    }

    /**
     * 详情页构建器.
     * 为了复写详情页的布局.
     *
     * @param mixed $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.show'))
            ->body(function (Row $row) use ($id) {
                $row->column(7, $this->detail($id));
                $row->column(5, function (Column $column) use ($id) {
                    // 处理设备使用人
                    $device = $this->detail($id)->model();
                    $column->row(Card::make()->content(admin_trans_label('Current User') . '：' . $device->userName()));

                    $related = Support::makeDeviceRelatedChartData($id);
                    $column->row(new Card(trans('main.related'), view('charts.device_related')->with('related', $related)));
                    $result = self::hasDeviceRelated($id);
                    $column->row(new Card(trans('main.part'), $result['part']));
                    $column->row(new Card(trans('main.software'), $result['software']));
                    $column->row(new Card(trans('main.service'), $result['service']));

                    // 处理设备履历
                    $history = DeviceService::history($id);
                    $card = new Card(trans('main.history'), view('history')->with('data', $history));
                    // @permissions
                    if (Admin::user()->can('device.record.history.export')) {
                        $card->tool('<a class="btn btn-primary btn-xs" href="' . admin_route('export.device.history', ['device_id' => $id]) . '" target="_blank">' . admin_trans_label('Export To Excel') . '</a>');
                    }
                    $column->row($card);
                });
            });
    }

    /**
     * Make a show builder.
     *
     * @param int $id
     *
     * @return Show
     */
    protected function detail(int $id): Show
    {
        return Show::make($id, new DeviceRecord(['category', 'vendor', 'admin_user', 'admin_user.department', 'depreciation']), function (Show $show) {
            $sort_columns = $this->sortColumns();
            $show->field('id', '', $sort_columns);
            $show->field('name', '', $sort_columns);
            $show->field('asset_number', '', $sort_columns);
            $show->field('description', '', $sort_columns);
            $show->field('category.name', '', $sort_columns);
            $show->field('vendor.name', '', $sort_columns);
            $show->field('mac', '', $sort_columns);
            $show->field('ip', '', $sort_columns);
            $show->field('photo', '', $sort_columns)->image();
            $show->field('price', '', $sort_columns);
            $show->field('depreciation_price', '', $sort_columns)->as(function () {
                $device_record = \App\Models\DeviceRecord::where('id', $this->id)->first();
                if (!empty($device_record)) {
                    $depreciation_rule_id = Support::getDepreciationRuleId($device_record);
                    return Support::depreciationPrice($this->price, $this->purchased, $depreciation_rule_id);
                }
            });
            $show->field('purchased', '', $sort_columns);
            $show->field('expired', '', $sort_columns);
            $show->field('admin_user.name', '', $sort_columns);
            $show->field('admin_user.department.name', '', $sort_columns);
            $show->field('depreciation.name', '', $sort_columns);
            $show->field('depreciation.termination', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new DeviceRecord())->getTable(), $show, $sort_columns);

            $show->field('created_at', '', $sort_columns);
            $show->field('updated_at', '', $sort_columns);

            /**
             * 自定义按钮.
             */
            $show->tools(function (\Dcat\Admin\Show\Tools $tools) {
                // @permissions
                if (Admin::user()->can('device.track.delete') && !empty($this->track()->first())) {
                    $tools->append(new DeviceRecordDeleteTrackAction());
                }
                $is_lend = $this->isLend();
                // @permissions
                if (Admin::user()->can('device.record.track.create_update') && empty($this->track()->first()) && !$is_lend) {
                    $tools->append(new \App\Admin\Actions\Show\DeviceRecordCreateUpdateTrackAction($is_lend));
                }
                $tools->append('&nbsp;');
            });

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('device.record.update')) {
                $show->disableEditButton();
            }
        });
    }

    /**
     * 返回字段排序.
     *
     * @return array
     */
    public function sortColumns(): array
    {
        return ColumnSort::where('table_name', (new DeviceRecord())->getTable())
            ->get(['name', 'order'])
            ->toArray();
    }

    /**
     * 提供selectCreate的ajax请求.
     *
     * @param Request $request
     * @return mixed
     */
    public function selectList(Request $request): mixed
    {
        $q = $request->get('q');

        return \App\Models\DeviceRecord::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
    }

    /**
     * 履历导出.
     *
     * @param $device_id
     *
     * @return mixed
     */
    public function exportHistory($device_id): mixed
    {
        return ExportService::deviceHistory($device_id);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new DeviceRecord(['category', 'vendor', 'admin_user', 'admin_user.department', 'depreciation']), function (Grid $grid) {
            $sort_columns = $this->sortColumns();
            $grid->column('id', '', $sort_columns);
            $grid->column('name', '', $sort_columns);
            $grid->column('photo', '', $sort_columns)->image('', 50, 50);
            $grid->column('asset_number_qrcode', '', $sort_columns)->qrcode(function () {
                return 'device:' . $this->asset_number;
            });
            $grid->column('asset_number', '', $sort_columns)->display(function ($asset_number) {
                $asset_number = "<span class='badge badge-secondary'>$asset_number</span>";
                $tag = Support::getSoftwareIcon($this->id);
                if (!empty($tag)) {
                    $asset_number = "<img alt='$tag' src='/static/images/icons/$tag.png' style='width: 25px;height: 25px;margin-right: 10px'/>$asset_number";
                }
                return $asset_number;
            });
            $grid->column('device_status', '', $sort_columns)->display(function () {
                return $this->status()[0];
            });
            $grid->column('description', '', $sort_columns);
            $grid->column('category.name', '', $sort_columns);
            $grid->column('vendor.name', '', $sort_columns);
            $grid->column('mac', '', $sort_columns);
            $grid->column('ip', '', $sort_columns);
            $grid->column('price', '', $sort_columns);
            $grid->column('purchased', '', $sort_columns);
            $grid->column('expired', '', $sort_columns);
            $grid->column('admin_user.name', '', $sort_columns)->display(function ($name) {
                if ($this->isLend()) {
                    return '<span style="color: rgba(178,68,71,1);font-weight: 600;">[' . trans('main.lend') . '] </span>' . $name;
                }
                return $name;
            });
            $grid->column('admin_user.department.name', '', $sort_columns);
            $grid->column('expiration_left_days', '', $sort_columns)->display(function () {
                return ExpirationService::itemExpirationLeftDaysRender('device', $this->id);
            });
            $grid->column('depreciation.name', '', $sort_columns);
            $grid->column('created_at', '', $sort_columns);
            $grid->column('updated_at', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new DeviceRecord())->getTable(), $grid, $sort_columns);

            /**
             * 批量操作.
             */
            $grid->batchActions(function (BatchActions $batchActions) {
                // @permissions
//                if (Admin::user()->can('device.record.batch.track.creat_update')) {
//                    $batchActions->add(new DeviceRecordBatchCreateUpdateTrackAction());
//                }
                // @permissions
                if (Admin::user()->can('device.record.batch.delete')) {
                    $batchActions->add(new DeviceRecordBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('device.record.batch.force.delete')) {
                    $batchActions->add(new DeviceRecordBatchForceDeleteAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('device.print.tag')) {
                    $tools->append(new DevicePrintTagAction());
                }
                if (Admin::user()->can('device.print.list')) {
                    $tools->append(new DevicePrintListAction());
                }
                if (Admin::user()->can('device.record.import')) {
                    $tools->append(new DeviceRecordImportAction());
                }
            });

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                if ($this->deleted_at == null) {
                    // @permissions
                    if (Admin::user()->can('device.record.delete')) {
                        $actions->append(new DeviceRecordDeleteAction());
                    }
                    $is_lend = $this->isLend();
                    // @permissions
                    if (Admin::user()->can('device.record.track.create_update') && !$is_lend) {
                        $actions->append(new DeviceRecordCreateUpdateTrackAction($is_lend));
                    }
                    // @permissions
                    if (Admin::user()->can('device.maintenance.create')) {
                        $actions->append(new MaintenanceRecordCreateAction($this->asset_number));
                    }
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns([
                'id',
                'photo',
                'vendor.name',
                'description',
                'price',
                'expired',
                'depreciation.name',
                'expiration_left_days',
                'admin_user.department.name',
                'created_at',
                'updated_at',
                'asset_number_qrcode'
            ]);

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'asset_number',
                    'description',
                    'category.name',
                    'vendor.name',
                    'name',
                    'mac',
                    'ip',
                    'price',
                    'purchased',
                    'expired',
                    'admin_user.name',
                    'admin_user.department.name',
                ], ControllerHasCustomColumns::makeQuickSearch((new DeviceRecord())->getTable()))
            )
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 筛选.
             */
            $grid->filter(function ($filter) {
                if (admin_setting('switch_to_filter_panel')) {
                    $filter->panel();
                }
                $filter->scope('history', admin_trans_label('Deleted'))->onlyTrashed();
                $filter->scope('lend', trans('main.lend'))->whereHas('track', function ($query) {
                    $query->whereNotNUll('lend_time');
                });
                $filter->scope('using', trans('main.using'))->has('admin_user');
                $filter->scope('dead', trans('main.dead'))->doesntHave('admin_user')->where('expired', '<', now());
                $filter->scope('idle', trans('main.idle'))
                    ->doesntHave('admin_user')
                    ->whereNull('expired');
                $filter->equal('category_id')->select(DeviceCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                $filter->equal('admin_user.name')->select(Support::selectUsers('name'));
                $filter->equal('admin_user.department_id')->select(Department::pluck('name', 'id'));
                $filter->equal('depreciation_id')->select(DepreciationRule::pluck('name', 'id'));
                $filter->date('purchased');
                $filter->date('expired');
                /**
                 * 自定义字段.
                 */
                ControllerHasCustomColumns::makeFilter((new DeviceRecord())->getTable(), $filter);
            });

            /**
             * 按钮控制.
             */
            // 如果数据库表中没有name,title,username，启用行选择器后需要指定默认值
            $grid->rowSelector()->titleColumn('asset_number');
            $grid->disableBatchDelete();
            $grid->disableDeleteButton();
            $grid->enableDialogCreate();
            $grid->disableEditButton();
            $grid->toolsWithOutline(false);
            if (!request('_scope_')) {
                // @permissions
                if (!Admin::user()->can('device.record.create')) {
                    $grid->disableCreateButton();
                }
                // @permissions
                if (Admin::user()->can('device.record.update')) {
                    $grid->showQuickEditButton();
                }
            }
            // @permissions
            if (Admin::user()->can('device.record.export')) {
                $grid->export()->rows(function ($rows) {
                    foreach ($rows as $row) {
                        $device = \App\Models\DeviceRecord::query()
                            ->where('id', $row['id'])
                            ->first();
                        //导出分类定义
                        $row['category.name'] = DeviceCategory::query()
                            ->where('id', $row['category_id'])
                            ->value('name');
                        //导出厂商定义
                        $row['vendor.name'] = VendorRecord::query()
                            ->where('id', $row['vendor_id'])
                            ->value('name');
                        //导出用户定义
                        $row['admin_user.name'] = $device?->admin_user->name ?? '未知';
                        //导出部门定义
                        $row['admin_user.department.name'] = $device?->admin_user?->department->name ?? '未知';
                        //导出折旧规则定义
                        $row['depreciation.name'] = $device?->depreciation->name ?? '未知';
                        //导出设备状态定义
                        $row['device_status'] = $device?->status()[1] ?? '未知';
                        //导出保固剩余天数定义
                        $row['expiration_left_days'] = ExpirationService::itemExpirationLeftDays('device', $device->id);
                    }
                    return $rows;
                });
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new DeviceRecord(), function (Form $form) {
            $form->row(function (\Dcat\Admin\Form\Row $row) use ($form) {
                $row->width(6)->text('name')->required();
                if ($form->isCreating() || empty($form->model()->asset_number)) {
                    $row->text('asset_number')->required();
                } else {
                    $row->display('asset_number')->required();
                }

                $row->width(6)
                    ->select('category_id')
                    ->options(DeviceCategory::pluck('name', 'id'))
                    ->required();
                $row->width(6)
                    ->select('vendor_id')
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
                $row->width(6)
                    ->text('mac');
                $row->width(6)
                    ->text('ip');
                $row->width(6)
                    ->currency('price');
                $row->width(6)
                    ->select('depreciation_rule_id')
                    ->options(DepreciationRule::pluck('name', 'id'))
                    ->help(admin_trans_label('Depreciation Rule Help'));
                $row->width(6)
                    ->date('purchased');
                $row->width(6)
                    ->date('expired')
                    ->attribute('autocomplete', 'off');

                $row->width(6)
                    ->text('description');
                $row->width()
                    ->image('photo')
                    ->autoUpload()
                    ->uniqueName()
                    ->help(admin_trans_label('Photo Help'));

                /**
                 * 自定义字段
                 */
                foreach (ControllerHasCustomColumns::getCustomColumns((new DeviceRecord())->getTable()) as $custom_column) {
                    ControllerHasCustomColumns::makeForm($custom_column, $row->width(6));
                }
            });

            /**
             * 按钮控制.
             */
            $form->disableDeleteButton();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();

            $form->saving(function (Form $form) {
                if ($form->isCreating() || empty($form->model()->asset_number)) {
                    $return = Support::ifAssetNumberUsed($form->input('asset_number'));
                    if ($return) {
                        return $form->response()
                            ->error(trans('main.asset_number_exist'));
                    }
                }
            });
        });
    }
}
