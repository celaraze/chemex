<?php

namespace App\Admin\Forms;

use App\Models\ConsumableCategory;
use App\Models\ImportLog;
use App\Models\ImportLogDetail;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Admin;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Dcat\EasyExcel\Excel;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ConsumableCategoryImportForm extends Form implements LazyRenderable
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
        $file = $input['file'];
        $file_path = public_path('uploads/' . $file);

        $success = 0;
        $fail = 0;

        $import_log = new ImportLog();
        $import_log->item = get_class(new ConsumableCategory());
        $import_log->operator = Admin::user()->id;
        $import_log->save();

        try {
            $rows = Excel::import($file_path)->first()->toArray();
            foreach ($rows as $row) {
                $name = $row['名称'] ?? '未知';
                try {
                    if (!empty($row['名称'])) {
                        $consumable_category = new ConsumableCategory();
                        $consumable_category->name = $row['名称'];
                        // 这里导入判断空值，不能使用 ?? null 或者 ?? '' 的方式，写入数据库的时候
                        // 会默认为插入''而不是null，这会导致像price这样的double也是插入''，就会报错
                        // 其实price应该插入null
                        if (!empty($row['描述'])) {
                            $consumable_category->description = $row['描述'];
                        }
                        $consumable_category->save();
                        $success++;
                        // 导入日志写入
                        ImportLogDetail::query()->create([
                            'log_id' => $import_log->id,
                            'status' => 1,
                            'log' => $row['名称'] . '：导入成功！'
                        ]);
                    } else {
                        $fail++;
                        // 导入日志写入
                        ImportLogDetail::query()->create([
                            'log_id' => $import_log->id,
                            'log' => $name . '：导入失败，缺少必要的字段：名称！'
                        ]);
                    }
                } catch (Exception $exception) {
                    $fail++;
                    // 导入日志写入
                    ImportLogDetail::query()->create([
                        'log_id' => $import_log->id,
                        'log' => $name . '：导入失败，' . $exception->getMessage()
                    ]);
                }
            }
            $return = $this->response()
                ->success(trans('main.success') . ': ' . $success . ' ; ' . trans('main.fail') . ': ' . $fail . '，导入结果详情请至导入日志查看。')
                ->refresh();
        } catch (IOException $exception) {
            $return = $this->response()
                ->error(trans('main.file_io_error') . $exception->getMessage());
        } catch (UnsupportedTypeException $exception) {
            $return = $this->response()
                ->error(trans('main.file_format') . $exception->getMessage());
        } catch (FileNotFoundException $exception) {
            $return = $this->response()
                ->error(trans('main.file_none') . $exception->getMessage());
        }

        return $return;
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->file('file')
            ->accept('xlsx,csv')
            ->autoUpload()
            ->uniqueName()
            ->required()
            ->help(admin_trans_label('File Help'));
    }
}
