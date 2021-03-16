<?php

namespace App\Admin\Forms;

use App\Models\CustomColumn;
use App\Models\DeviceCategory;
use App\Models\DeviceRecord;
use App\Models\DeviceTrack;
use App\Models\PurchasedChannel;
use App\Models\User;
use App\Models\VendorRecord;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Dcat\EasyExcel\Excel;
use Exception;
use League\Flysystem\FileNotFoundException;

class DeviceRecordImportForm extends Form
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
                    if (!empty($row['名称']) && !empty($row['分类']) && !empty($row['厂商'])) {
                        $device_category = DeviceCategory::where('name', $row['分类'])->first();
                        $vendor_record = VendorRecord::where('name', $row['厂商'])->first();
                        $user = User::where('name', $row['用户'])->first();
                        if (empty($device_category)) {
                            $device_category = new DeviceCategory();
                            $device_category->name = $row['分类'];
                            $device_category->save();
                        }
                        if (empty($vendor_record)) {
                            $vendor_record = new VendorRecord();
                            $vendor_record->name = $row['厂商'];
                            $vendor_record->save();
                        }
                        $device_record = new DeviceRecord();
                        $device_record->name = $row['名称'];
                        $device_record->category_id = $device_category->id;
                        $device_record->vendor_id = $vendor_record->id;
                        // 这里导入判断空值，不能使用 ?? null 或者 ?? '' 的方式，写入数据库的时候
                        // 会默认为插入''而不是null，这会导致像price这样的double也是插入''，就会报错
                        // 其实price应该插入null
                        $exist = DeviceRecord::where('asset_number', $row['资产编号'])->withTrashed()->first();
                        if (!empty($exist) && !empty($exist->asset_number)) {
                            $exist->asset_number = $exist->asset_number.'_archive';
                            $exist->save();
                        }
                        if (!empty($row['资产编号'])) {
                            $device_record->asset_number = $row['资产编号'];
                        }
                        $device_record->mac = $row['MAC'];
                        $device_record->ip = $row['IP'];
                        if (!empty($row['描述'])) {
                            $device_record->description = $row['描述'];
                        }
                        if (!empty($row['价格'])) {
                            $device_record->price = $row['价格'];
                        }
                        if (!empty($row['购入日期'])) {
                            $device_record->purchased = $row['购入日期'];
                        }
                        if (!empty($row['过保日期'])) {
                            $device_record->expired = $row['过保日期'];
                        }
                        if (!empty($row['购入途径'])) {
                            $purchased_channel = PurchasedChannel::where('name', $row['购入途径'])->first();
                            if (empty($purchased_channel)) {
                                $purchased_channel = new PurchasedChannel();
                                $purchased_channel->name = $row['购入途径'];
                                $purchased_channel->save();
                            }
                            $device_record->purchased_channel_id = $purchased_channel->id;
                        }

                        /*
                         * 处理自定义字段的导入
                         */
                        $custom_fields = CustomColumn::where('table_name', $device_record->getTable())->get();
                        foreach ($custom_fields as $custom_field) {
                            $name = $custom_field->name;
                            $nick_name = $custom_field->nick_name;
                            $device_record->$name = $row[$nick_name];
                        }

                        $device_record->save();

                        if (!empty($user)) {
                            $device_track = new DeviceTrack();
                            $device_track->device_id = $device_record->id;
                            $device_track->user_id = $user->id;
                            $device_track->save();
                        }
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
                ->error(trans('file.file_none').$e->getMessage());
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
