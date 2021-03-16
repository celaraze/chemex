<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\BatchAction\SoftwareRecordBatchDeleteAction;
use App\Admin\Actions\Grid\RowAction\SoftwareRecordCreateUpdateTrackAction;
use App\Admin\Actions\Grid\RowAction\SoftwareRecordDeleteAction;
use App\Admin\Actions\Grid\RowAction\SoftwareTrackDeleteAction;
use App\Admin\Actions\Grid\ToolAction\SoftwareRecordImportAction;
use App\Admin\Grid\Displayers\RowActions;
use App\Admin\Repositories\SoftwareRecord;
use App\Admin\Repositories\SoftwareTrack;
use App\Grid;
use App\Models\ColumnSort;
use App\Models\DeviceRecord;
use App\Models\PurchasedChannel;
use App\Models\SoftwareCategory;
use App\Models\VendorRecord;
use App\Services\ExpirationService;
use App\Services\SoftwareService;
use App\Support\Data;
use App\Support\Support;
use App\Traits\ControllerHasCustomColumns;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Tools;
use Dcat\Admin\Grid\Tools\BatchActions;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Tab;

/**
 * @property  DeviceRecord device
 * @property  int id
 * @property  string deleted_at
 *
 * @method leftCounts()
 */
class SoftwareRecordController extends AdminController
{
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->add(Data::icon('record').trans('main.record'), $this->grid(), true);
                $tab->addLink(Data::icon('category').trans('main.category'), admin_route('software.categories.index'));
                $tab->addLink(Data::icon('track').trans('main.track'), admin_route('software.tracks.index'));
                $tab->addLink(Data::icon('statistics').trans('main.statistics'), admin_route('software.statistics'));
                $tab->addLink(Data::icon('column').trans('main.column'), admin_route('software.columns.index'));
                $row->column(12, $tab);
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new SoftwareRecord(['category', 'vendor']), function (Grid $grid) {
            $column_sort = ColumnSort::where('table_name', (new SoftwareRecord())->getTable())
                ->get(['field', 'order'])
                ->toArray();
            $grid->column('id', '', $column_sort);
            $grid->column('qrcode', '', $column_sort)->qrcode(function () {
                return 'software:'.$this->id;
            }, 200, 200);
            $grid->column('name', '', $column_sort);
            $grid->column('description', '', $column_sort);
            $grid->column('asset_number', '', $column_sort);
            $grid->column('category.name', '', $column_sort);
            $grid->column('version', '', $column_sort);
            $grid->column('vendor.name', '', $column_sort);
            $grid->column('price', '', $column_sort);
            $grid->column('purchased', '', $column_sort);
            $grid->column('expired', '', $column_sort);
            $grid->column('distribution', '', $column_sort)->using(Data::distribution());
            $grid->column('counts', '', $column_sort);
            $grid->column('left_counts', '', $column_sort)->display(function () {
                return $this->leftCounts();
            });
            $grid->column('expiration_left_days', '', $column_sort)->display(function () {
                return ExpirationService::itemExpirationLeftDaysRender('software', $this->id);
            });
            $grid->column('created_at', '', $column_sort);
            $grid->column('updated_at', '', $column_sort);

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeGrid((new SoftwareRecord())->getTable(), $grid, $column_sort);

            /**
             * 行操作按钮.
             */
            $grid->actions(function (RowActions $actions) {
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
                    $actions->append("<a href='$tracks_route'>💿 ".admin_trans_label('Manage Track').'</a>');
                }
            });

            /**
             * 字段过滤.
             */
            $grid->showColumnSelector();
            $grid->hideColumns([
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
            $grid->toolsWithOutline(false);
            // @permissions
            if (!Admin::user()->can('software.record.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('software.record.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (Admin::user()->can('software.record.export')) {
                $grid->export();
            }
        });
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
                        $grid = Grid::make(new SoftwareTrack(['software', 'device', 'device.user']), function (Grid $grid) use ($id) {
                            $grid->model()->where('software_id', '=', $id);
                            $grid->tableCollapse(false);
                            $grid->withBorder();

                            $grid->column('id');
                            $grid->column('device.name')->link(function () {
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
                            $card->tool('<a class="btn btn-primary btn-xs" href="'.admin_route('export.software.history', [$id]).'" target="_blank">'.admin_trans_label('Export To Excel').'</a>');
                        }
                        $column->row($card);
                    });
                }
            });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new SoftwareRecord(['category', 'vendor', 'channel']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('asset_number');
            $show->field('description');
            $show->field('category.name');
            $show->field('version');
            $show->field('vendor.name');
            $show->field('channel.name');
            $show->field('price');
            $show->field('purchased');
            $show->field('expired');
            $show->field('distribution')->using(Data::distribution());
            $show->field('counts');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeDetail((new SoftwareRecord())->getTable(), $show);

            $show->field('created_at');
            $show->field('updated_at');

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
     * 履历导出.
     *
     * @param $software_id
     *
     * @return mixed
     */
    public function exportHistory($software_id)
    {
        return SoftwareService::exportHistory($software_id);
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new SoftwareRecord(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('version')->required();

            if (Support::ifSelectCreate()) {
                $form->selectCreate('category_id')
                    ->options(SoftwareCategory::class)
                    ->ajax(admin_route('selection.software.categories'))
                    ->url(admin_route('software.categories.create'))
                    ->required();
                $form->selectCreate('vendor_id')
                    ->options(VendorRecord::class)
                    ->ajax(admin_route('selection.vendor.records'))
                    ->url(admin_route('vendor.records.create'))
                    ->required();
                $form->selectCreate('purchased_channel_id')
                    ->options(VendorRecord::class)
                    ->ajax(admin_route('selection.purchased.channels'))
                    ->url(admin_route('purchased.channels.create'));
            } else {
                $form->select('category_id')
                    ->options(SoftwareCategory::selectOptions())
                    ->required();
                $form->select('vendor_id')
                    ->options(VendorRecord::pluck('name', 'id'))
                    ->required();
                $form->select('purchased_channel_id')
                    ->options(PurchasedChannel::pluck('name', 'id'));
            }

            $form->select('distribution')
                ->options(Data::distribution())
                ->default('u')
                ->required();
            $form->number('counts')
                ->default(-1)
                ->required()
                ->help(admin_trans_label('Counts Help'));
            $form->text('description');
            $form->text('asset_number');
            $form->currency('price')->default(0);
            $form->date('purchased');
            $form->date('expired');

            /**
             * 自定义字段.
             */
            ControllerHasCustomColumns::makeForm((new SoftwareRecord())->getTable(), $form);

            $form->display('created_at');
            $form->display('updated_at');

            /**
             * 按钮控制.
             */
            $form->disableDeleteButton();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}
