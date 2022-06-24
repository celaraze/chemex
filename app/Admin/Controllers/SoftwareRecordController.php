<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\SoftwareRecordBatchDeleteAction;
use App\Admin\Actions\Grid\BatchAction\SoftwareRecordBatchForceDeleteAction;
use App\Admin\Actions\Grid\RowAction\SoftwareRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\SoftwareRecordDeleteAction;
use App\Admin\Actions\Grid\RowAction\SoftwareTrackDeleteAction;
use App\Admin\Actions\Grid\ToolAction\SoftwareRecordImportAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\SoftwareRecord;
use App\Admin\Repositories\SoftwareTrack;
use App\Form;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DeviceRecord;
use App\Models\SoftwareCategory;
use App\Models\VendorRecord;
use App\Services\ExpirationService;
use App\Services\SoftwareService;
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

/**
 * @property  DeviceRecord device
 * @property  int id
 * @property  string deleted_at
 * @property  string asset_number
 *
 * @method leftCounts()
 */
class SoftwareRecordController extends AdminController
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
        $tab->addLink(Data::icon('category') . trans('main.category'), admin_route('software.categories.index'));
        $tab->addLink(Data::icon('track') . trans('main.track'), admin_route('software.tracks.index'));
        $tab->addLink(Data::icon('statistics') . trans('main.statistics'), admin_route('software.statistics'));
        $tab->addLink(Data::icon('column') . trans('main.column'), admin_route('software.columns.index'));
        $row->column(12, $tab);
        return $row;
    }

    public function show($id, Content $content): Content
    {
        $history = SoftwareService::history($id);

        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.show'))
            ->body(function (Row $row) use ($id, $history) {
                // 判断权限
                if (!Admin::user()->can('software.track.list')) {
                    $row->column(12, $this->detail($id));
                } else {
                    $row->column(6, $this->detail($id));
                    $row->column(6, function (Column $column) use ($id, $history) {
                        $grid = Grid::make(new SoftwareTrack(['software', 'device', 'device.admin_user']), function (Grid $grid) use ($id) {
                            $grid->model()->where('software_id', '=', $id);
                            $grid->tableCollapse(false);
                            $grid->withBorder();

                            $grid->column('id');
                            $grid->column('device.asset_number')->link(function () {
                                if (!empty($this->device)) {
                                    return admin_route('device.records.show', [$this->device['id']]);
                                }
                            });
                            $grid->column('device.user.name');

                            $grid->disableToolbar();
                            $grid->disableBatchDelete();
                            $grid->disableRowSelector();
                            $grid->disableViewButton();
                            $grid->disableEditButton();
                            $grid->disableDeleteButton();

                            $grid->actions(function (RowActions $actions) {
                                // @permissions
                                if (Admin::user()->can('software.record.track.delete') && $this->deleted_at == null) {
                                    $actions->append(new SoftwareTrackDeleteAction());
                                }
                            });
                        });
                        $column->row(new Card(trans('main.related'), $grid));
                        $card = new Card(trans('main.history'), view('history')->with('data', $history));
                        // @permissions
                        if (Admin::user()->can('software.record.history.export')) {
                            $card->tool('<a class="btn btn-primary btn-xs" href="' . admin_route('export.software.history', [$id]) . '" target="_blank">' . admin_trans_label('Export To Excel') . '</a>');
                        }
                        $column->row($card);
                    });
                }
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
        return Show::make($id, new SoftwareRecord(['category', 'vendor']), function (Show $show) {
            $sort_columns = $this->sortColumns();
            $show->field('id', '', $sort_columns);
            $show->field('name', '', $sort_columns);
            $show->field('asset_number', '', $sort_columns);
            $show->field('description', '', $sort_columns);
            $show->field('category.name', '', $sort_columns);
            $show->field('version', '', $sort_columns);
            $show->field('sn', '', $sort_columns);
            $show->field('vendor.name', '', $sort_columns);
            $show->field('price', '', $sort_columns);
            $show->field('purchased', '', $sort_columns);
            $show->field('expired', '', $sort_columns);
            $show->field('distribution', '', $sort_columns)->using(Data::distribution());
            $show->field('counts', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new SoftwareRecord())->getTable(), $show, $sort_columns);

            $show->field('created_at', '', $sort_columns);
            $show->field('updated_at', '', $sort_columns);

            /**
             * 按钮控制.
             */
            $show->disableDeleteButton();
            // @permissions
            if (!Admin::user()->can('software.record.update')) {
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
        return ColumnSort::where('table_name', (new SoftwareRecord())->getTable())
            ->get(['name', 'order'])
            ->toArray();
    }

    /**
     * 履历导出.
     *
     * @param $software_id
     *
     * @return mixed
     */
    public function exportHistory($software_id): mixed
    {
        return SoftwareService::exportHistory($software_id);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new SoftwareRecord(['category', 'vendor']), function (Grid $grid) {
            $sort_columns = $this->sortColumns();
            $grid->column('id', '', $sort_columns);
            $grid->column('asset_number', '', $sort_columns)->display(function ($asset_number) {
                return "<span class='badge badge-secondary'>$asset_number</span>";
            });
            $grid->column('asset_number_qrcode', '', $sort_columns)->qrcode(function () {
                return 'software:' . $this->asset_number;
            });
            $grid->column('name', '', $sort_columns);
            $grid->column('description', '', $sort_columns);
            $grid->column('category.name', '', $sort_columns);
            $grid->column('version', '', $sort_columns);
            $grid->column('sn', '', $sort_columns);
            $grid->column('vendor.name', '', $sort_columns);
            $grid->column('price', '', $sort_columns);
            $grid->column('purchased', '', $sort_columns);
            $grid->column('expired', '', $sort_columns);
            $grid->column('distribution', '', $sort_columns)->using(Data::distribution());
            $grid->column('counts', '', $sort_columns);
            $grid->column('left_counts', '', $sort_columns)->display(function () {
                return $this->leftCounts();
            });
            $grid->column('expiration_left_days', '', $sort_columns)->display(function () {
                return ExpirationService::itemExpirationLeftDaysRender('software', $this->id);
            });
            $grid->column('created_at', '', $sort_columns);
            $grid->column('updated_at', '', $sort_columns);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new SoftwareRecord())->getTable(), $grid, $sort_columns);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
                if ($this->deleted_at == null) {
                    // @permissions
                    if (Admin::user()->can('software.record.delete')) {
                        $actions->append(new SoftwareRecordDeleteAction());
                    }
                    // @permissions
                    if (Admin::user()->can('software.record.track.create_update')) {
                        $actions->append(new SoftwareRecordCreateUpdateTrackAction());
                    }
                    // @permissions
                    if (Admin::user()->can('software.record.track.list')) {
                        $tracks_route = admin_route('software.tracks.index', ['_search_' => $this->id]);
                        $actions->append("<a href='$tracks_route'>💿 " . admin_trans_label('Manage Track') . '</a>');
                    }
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns([
                'id',
                'description',
                'price',
                'expired',
                'expiration_left_days',
            ]);

            /**
             * 快速搜索.
             */
            $grid->quickSearch(
                array_merge([
                    'id',
                    'name',
                    'sn',
                    'asset_number',
                    'category.name',
                    'version',
                    'price',
                ], ControllerHasCustomColumns::makeQuickSearch((new SoftwareRecord())->getTable()))
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
                $filter->equal('category_id')->select(SoftwareCategory::pluck('name', 'id'));
                $filter->equal('vendor_id')->select(VendorRecord::pluck('name', 'id'));
                /**
                 * 自定义按钮.
                 */
                ControllerHasCustomColumns::makeFilter((new SoftwareRecord())->getTable(), $filter);
            });

            /**
             * 批量操作按钮.
             */
            $grid->batchActions(function (BatchActions $batchActions) {
                // @permissions
                if (Admin::user()->can('software.record.batch.delete')) {
                    $batchActions->add(new SoftwareRecordBatchDeleteAction());
                }
                // @permissions
                if (Admin::user()->can('software.record.batch.force.delete')) {
                    $batchActions->add(new SoftwareRecordBatchForceDeleteAction());
                }
            });

            /**
             * 工具按钮.
             */
            $grid->tools(function (Tools $tools) {
                // @permissions
                if (Admin::user()->can('software.record.import')) {
                    $tools->append(new SoftwareRecordImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            $grid->enableDialogCreate();
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();
            $grid->disableEditButton();
            $grid->toolsWithOutline(false);
            if (!request('_scope_')) {
                // @permissions
                if (!Admin::user()->can('software.record.create')) {
                    $grid->disableCreateButton();
                }
                // @permissions
                if (Admin::user()->can('software.record.update')) {
                    $grid->showQuickEditButton();
                }
            }
            // @permissions
            if (Admin::user()->can('software.record.export')) {
                $grid->export()->rows(function ($rows) {
                    foreach ($rows as $row) {
                        $software = \App\Models\SoftwareRecord::query()
                            ->where('id', $row['id'])
                            ->first();
                        //导出分类定义
                        $row['category.name'] = SoftwareCategory::query()
                            ->where('id', $row['category_id'])
                            ->value('name');
                        //导出厂商定义
                        $row['vendor.name'] = VendorRecord::query()
                            ->where('id', $row['vendor_id'])
                            ->value('name');
                        //导出发行方式定义
                        $row['distribution'] = Data::distribution()[$software['distribution']] ?? '未知';
                        //导出剩余授权数量定义
                        $row['left_counts'] = $software->leftCounts() ?? '未知';
                        //导出保固剩余天数定义
                        $row['expiration_left_days'] = ExpirationService::itemExpirationLeftDays('software', $software->id) ?? '未知';
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
        return Form::make(new SoftwareRecord(), function (Form $form) {
            $form->row(function (\Dcat\Admin\Form\Row $row) use ($form) {
                if ($form->isCreating() || empty($form->model()->asset_number)) {
                    $row->width(6)->text('asset_number')->required();
                } else {
                    $row->display('asset_number')->required();
                }
                $row->width(6)
                    ->text('name')->required();
                $row->width(6)
                    ->text('version')->required();
                $row->width(6)
                    ->select('distribution')
                    ->options(Data::distribution())
                    ->default('u')
                    ->required();
                $row->width(6)
                    ->number('counts')
                    ->default(-1)
                    ->required()
                    ->help(admin_trans_label('Counts Help'));
                $row->width(6)
                    ->select('category_id')
                    ->options(SoftwareCategory::selectOptions())
                    ->required();
                $row->width(6)
                    ->select('vendor_id')
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
                $row->width(6)
                    ->text('description');
                $row->width(6)
                    ->text('sn');
                $row->width(6)
                    ->currency('price')->default(0);
                $row->width(6)
                    ->date('purchased');
                $row->width(6)
                    ->date('expired');

                /**
                 * 自定义字段
                 */
                foreach (ControllerHasCustomColumns::getCustomColumns((new SoftwareRecord())->getTable()) as $custom_column) {
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
