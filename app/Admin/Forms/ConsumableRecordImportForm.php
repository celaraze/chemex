<?php

namespace App\Admin\Forms;

use App\Models\ConsumableCategory;
use App\Models\ConsumableRecord;
use App\Models\ImportLog;
use App\Models\ImportLogDetail;
use App\Models\VendorRecord;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Dcat\EasyExcel\Excel;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ConsumableRecordImportForm extends Form
{
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
        $import_log->item = get_class(new ConsumableRecord());
        $import_log->operator = Admin::user()->id;
        $import_log->save();

        try {
            $rows = Excel::import($file_path)->first()->toArray();
            foreach ($rows as $row) {
                try {
                    if (!empty($row['名称']) && !empty($row['规格'])) {
                        $consumable_category = ConsumableCategory::query()
                            ->where('name', $row['分类'])
                            ->first();
                        if (empty($consumable_category)) {
                            $consumable_category = new ConsumableCategory();
                            $consumable_category->name = $row['分类'];
                            $consumable_category->save();
                        }
                        $vendor_record = VendorRecord::where('name', $row['厂商'])->first();
                        if (empty($vendor_record)) {
                            $vendor_record = new VendorRecord();
                            $vendor_record->name = $row['厂商'];
                            $vendor_record->save();
                        }

                        $consumable_record = new ConsumableRecord();
                        $consumable_record->name = $row['名称'];
                        $consumable_record->specification = $row['规格'];
                        $consumable_record->category_id = $consumable_category->id;
                        $consumable_record->vendor_id = $vendor_record->id;
                        if (empty($row['描述'])) {
                            $consumable_record->description = $row['描述'];

                        }
                        if (!empty($row['价格'])) {
                            $consumable_record->price = $row['价格'];
                        }
                        $consumable_record->save();
                        $success++;
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
                            'log' => $row['名称'] ?? '未知' . '：导入失败，缺少必要的字段：名称、规格！'
                        ]);
                    }
                } catch (Exception $exception) {
                    $fail++;
                    // 导入日志写入
                    ImportLogDetail::query()->create([
                        'log_id' => $import_log->id,
                        'log' => $row['名称'] ?? '未知' . '：导入失败，' . $exception->getMessage()
                    ]);
                }
            }

            return $this->response()
                ->success(trans('main.success') . ': ' . $success . ' ; ' . trans('main.fail') . ': ' . $fail . '，导入结果详情请至导入日志查看。')
                ->refresh();
        } catch (IOException $exception) {
            return $this->response()
                ->error(trans('main.file_io_error') . $exception->getMessage());
        } catch (UnsupportedTypeException $exception) {
            return $this->response()
                ->error(trans('main.file_format') . $exception->getMessage());
        } catch (FileNotFoundException $exception) {
            return $this->response()
                ->error(trans('main.file_none') . $exception->getMessage());
        }
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

