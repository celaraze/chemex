<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\ToolAction\VendorRecordImportAction;
use App\Admin\Repositories\VendorRecord;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Form\NestedForm;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Illuminate\Http\Request;

class VendorRecordController extends AdminController
{
    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body($this->grid());
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
        return Grid::make(new VendorRecord(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name');
            $grid->column('description');
            $grid->column('location');

            /**
             * 快速搜索.
             */
            $grid->quickSearch('id', 'name', 'description', 'location')
                ->placeholder(trans('main.quick_search'))
                ->auto(false);

            /**
             * 工具按钮.
             */
            $grid->tools(function (Grid\Tools $tools) {
                // @permissions
                if (Admin::user()->can('vendor.record.import')) {
                    $tools->append(new VendorRecordImportAction());
                }
            });

            /**
             * 按钮控制.
             */
            // @permissions
            if (!Admin::user()->can('vendor.record.create')) {
                $grid->disableCreateButton();
            }
            // @permissions
            if (!Admin::user()->can('vendor.record.update')) {
                $grid->disableEditButton();
            }
            // @permissions
            if (!Admin::user()->can('vendor.record.delete')) {
                $grid->disableDeleteButton();
            }
            // @permissions
            if (!Admin::user()->can('vendor.record.batch.delete')) {
                $grid->disableBatchDelete();
            }
            $grid->enableDialogCreate();
            $grid->toolsWithOutline(false);
            // @permissions
            if (Admin::user()->can('vendor.record.import')) {
                $grid->export();
            }
        });
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function selectList(Request $request)
    {
        $q = $request->get('q');

        return \App\Models\VendorRecord::where('name', 'like', "%$q%")
            ->paginate(null, ['id', 'name as text']);
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
        return Show::make($id, new VendorRecord(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
            $show->field('location');
            $show->field('contacts')->view('vendor_records.contacts');
            $show->field('created_at');
            $show->field('updated_at');

            // @permissions
            if (!Admin::user()->can('vendor.record.update')) {
                $show->disableEditButton();
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
        return Form::make(new VendorRecord(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('description');
            $form->text('location');
            $form->table('contacts', function (NestedForm $table) {
                $table->text('contact_name');
                $table->mobile('phone');
                $table->email('email');
                $table->text('title');
            });
            $form->display('created_at');
            $form->display('updated_at');

            /**
             * 按钮控制.
             */
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}
