<?php

namespace App\Admin\Forms;

use App\Models\VendorRecord;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Dcat\EasyExcel\Excel;
use Exception;
use League\Flysystem\FileNotFoundException;

class VendorRecordImportForm extends Form
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
        $file_path = public_path('uploads/'.$file);

        try {
            $rows = Excel::import($file_path)->first()->toArray();
            foreach ($rows as $row) {
                try {
                    if (!empty($row['名称'])) {
                        $vendor_record = new VendorRecord();
                        $vendor_record->name = $row['名称'];
                        // 这里导入判断空值，不能使用 ?? null 或者 ?? '' 的方式，写入数据库的时候
                        // 会默认为插入''而不是null，这会导致像price这样的double也是插入''，就会报错
                        // 其实price应该插入null
                        if (!empty($row['描述'])) {
                            $vendor_record->description = $row['描述'];
                        }
                        if (!empty($row['所在地'])) {
                            $vendor_record->location = $row['所在地'];
                        }
                        $vendor_record->save();
                    } else {
                        return $this->response()
                            ->error(trans('main.parameter_missing'));
                    }
                } catch (Exception $exception) {
                    return $this->response()->error($exception->getMessage());
                }
            }
            $return = $this
                ->response()
                ->success(trans('main.upload_success'))
                ->refresh();
        } catch (IOException $e) {
            $return = $this
                ->response()
                ->error(trans('main.file_io_error').$e->getMessage());
        } catch (UnsupportedTypeException $e) {
            $return = $this
                ->response()
                ->error(trans('main.file_format').$e->getMessage());
        } catch (FileNotFoundException $e) {
            $return = $this
                ->response()
                ->error(trans('main.file_none').$e->getMessage());
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
