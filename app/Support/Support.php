<?php

namespace App\Support;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Models\DepreciationRule;
use App\Models\DeviceCategory;
use App\Models\DeviceRecord;
use App\Models\PartCategory;
use App\Models\PartRecord;
use App\Models\ServiceIssue;
use App\Models\ServiceRecord;
use App\Models\ServiceTrack;
use App\Models\SoftwareRecord;
use App\Models\SoftwareTrack;
use App\Models\User;
use Dcat\Admin\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Support
{
    /**
     * 获取所有物品的资产编号，加入到数组中返回.
     *
     * @return array
     */
    public static function getAllItemTypeAndId(): array
    {
        $data = [];
        $device_records = DeviceRecord::all();
        $part_records = PartRecord::all();
        $software_records = SoftwareRecord::all();

        foreach ($device_records as $device_record) {
            if (!empty($device_record->asset_number)) {
                $data[] = 'device:' . $device_record->asset_number . '&#10;';
            }
        }
        foreach ($part_records as $part_record) {
            if (!empty($part_record->asset_number)) {
                $data[] = 'part:' . $part_record->asset_number . '&#10;';
            }
        }
        foreach ($software_records as $software_record) {
            if (!empty($software_record->asset_number)) {
                $data[] = 'soft:' . $software_record->asset_number . '&#10;';
            }
        }

        return $data;
    }

    /**
     * 物品履历 形成清单数组（未排序）.
     *
     * @param $template
     * @param $item_track
     * @param array $data
     *
     * @return array
     */
    public static function itemTrack($template, $item_track, $data = []): array
    {
        $template['status'] = '+';
        $template['datetime'] = json_decode($item_track, true)['created_at'];
        $data[] = $template;
        if (!empty($item_track->deleted_at)) {
            $template['status'] = '-';
            $template['datetime'] = json_decode($item_track, true)['deleted_at'];
            $data[] = $template;
        }

        return $data;
    }

    /**
     * 计算盘点任务记录的数量.
     *
     * @param $check_id
     * @param string $type
     *
     * @return int
     */
    public static function checkTrackCounts($check_id, $type = 'A'): int
    {
        $check_record = CheckRecord::where('id', $check_id)->first();
        if (empty($check_record)) {
            return 0;
        }

        return match ($type) {
            'Y' => CheckTrack::where('check_id', $check_id)
                ->where('status', 1)
                ->count(),
            'N' => CheckTrack::where('check_id', $check_id)
                ->where('status', 2)
                ->count(),
            'L' => CheckTrack::where('check_id', $check_id)
                ->where('status', 0)
                ->count(),
            default => CheckTrack::where('check_id', $check_id)
                ->withTrashed()
                ->count(),
        };
    }

    /**
     * 设备id获取操作系统标识.
     *
     * @param $device_id
     *
     * @return string
     */
    public static function getSoftwareIcon($device_id): string
    {
        $software_tracks = SoftwareTrack::where('device_id', $device_id)
            ->get();
        $tags = Data::softwareTags();
        $keys = array_keys($tags);
        foreach ($software_tracks as $software_track) {
            $name = trim($software_track->software()->withTrashed()->first()->name);
            for ($n = 0; $n < count($tags); $n++) {
                for ($i = 0; $i < count($tags[$keys[$n]]); $i++) {
                    if (stristr($name, $tags[$keys[$n]][$i]) != false) {
                        return $keys[$n];
                    }
                }
            }
        }

        return '';
    }

    /**
     * 构造WebSSH连接字符串.
     *
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     *
     * @return string
     */
    public static function getSSHBaseUrl($host, $port, $username, $password): string
    {
        return "http://127.0.0.1:8222/?hostname=$host&port=$port&username=$username&password=$password";
    }

    /**
     * 通过类名获取对应物资的模型.
     *
     * @param $item
     * @param $item_id
     *
     * @return null
     */
    public static function getItemRecordByClass($item, $item_id)
    {
        return match ($item) {
            'part' => PartRecord::where('id', $item_id)->first(),
            'software' => SoftwareRecord::where('id', $item_id)->first(),
            default => DeviceRecord::where('id', $item_id)->first(),
        };
    }

    /**
     * 获取折旧后的价格
     *
     * @param $price
     * @param $date
     * @param $depreciation_rule_id
     *
     * @return float|int|null
     */
    public static function depreciationPrice($price, $date, $depreciation_rule_id): float|int|null
    {
        $depreciation = DepreciationRule::where('id', $depreciation_rule_id)->first();
        if (!empty($depreciation)) {
            $purchased_timestamp = strtotime($date);
            $now_timestamp = time();

            $diff = $now_timestamp - $purchased_timestamp;
            if ($diff < 0) {
                return $price;
            }

            $data = $depreciation['rules'];

            // 数组过滤器
            $return = array_filter($data, function ($item) use ($diff) {
                $number = match ($item['scale']) {
                    'month' => (int)$item['number'] * 24 * 60 * 60 * 30,
                    'year' => (int)$item['number'] * 24 * 60 * 60 * 365,
                    default => (int)$item['number'] * 24 * 60 * 60,
                };

                return $diff >= $number;
            });

            if (!empty($return)) {
                array_multisort(array_column($return, 'number'), SORT_DESC, $return);
                $price = $price * (float)$return[0]['ratio'];
            }

        }
        return $price;
    }

    /**
     * 根据模型查找折旧规则的id（记录的优先级>分类的优先级）.
     *
     * @param Model $model
     *
     * @return int|null
     */
    public static function getDepreciationRuleId(Model $model): ?int
    {
        $depreciation_rule_id = null;
        if (empty($model->depreciation_rule_id)) {
            $category = null;
            if ($model instanceof DeviceRecord) {
                $category = DeviceCategory::where('id', $model->category_id)->first();
            }
            if ($model instanceof PartRecord) {
                $category = PartCategory::where('id', $model->category_id)->first();
            }
            if (!empty($category) && !empty($category->depreciation_rule_id)) {
                $depreciation_rule_id = $category->depreciation_rule_id;
            }
        } else {
            $depreciation_rule_id = $model->depreciation_rule_id;
        }

        return $depreciation_rule_id;
    }

    /**
     * 判断是否切换到selectCreate.
     *
     * @return bool
     */
    public static function ifSelectCreate(): bool
    {
        if (admin_setting('switch_to_select_create') && Admin::extension()->enabled('celaraze.dcat-extension-plus')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取服务异常总览（看板）.
     *
     * @return ServiceRecord[]|Collection
     */
    public static function getServiceIssueStatus(): Collection|array
    {
        $services = ServiceRecord::all();
        foreach ($services as $service) {
            $service_status = $service->status;
            $service->start = null;
            $service->end = null;
            $service_track = ServiceTrack::where('service_id', $service->id)
                ->first();
            if (empty($service_track) || empty($service_track->device)) {
                $service->device_name = '未知';
            } else {
                $service->device_name = $service_track->device->asset_number;
            }
            $issues = [];
            $service_issues = ServiceIssue::where('service_id', $service->id)
                ->get();
            foreach ($service_issues as $service_issue) {
                if (empty($service->start)) {
                    $service->start = $service_issue->start;
                }
                if (strtotime($service_issue->start) < strtotime($service->start)) {
                    $service->start = $service_issue->start;
                }
                // 如果异常待修复
                if ($service_issue->status == 1) {
                    $service->status = 1;
                    $issue = $service_issue->issue . '<br>';
                    $issues[] = $issue;
                }
                // 如果是修复的
                if ($service_issue->status == 2) {
                    $service->status = 0;
                    $issue = '<span class="status-recovery">[已修复最近一个问题]</span> ' . $service_issue->issue . '<br>';
                    if ((time() - strtotime($service_issue->end)) > (24 * 60 * 60)) {
                        $issue = '';
                        $service->start = '';
                    } else {
                        // 如果结束时间是空，还没修复
                        if (empty($service->end)) {
                            $service->end = $service_issue->end;
                        }
                        // 如果结束时间大于开始时间，修复了
                        if (strtotime($service_issue->end) > strtotime($service->end)) {
                            $service->end = $service_issue->end;
                        }
                    }
                    $issues[] = $issue;
                }
            }
            // 如果暂停了
            if ($service_status == 1) {
                $service->status = 3;
                $service->start = date('Y-m-d H:i:s', strtotime($service->updated_at));
            }
            $service->issues = $issues;
        }
        return json_decode($services, true);
    }

    /**
     * 返回某一年的开始时间和结束时间.
     *
     * @param $year
     * @param string $field
     *
     * @return string
     */
    public static function makeYearDate($year, string $field = 'from'): string
    {
        $from = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $to = date('Y-m-d', mktime(23, 59, 59, 12, 31, $year));
        if ($field == 'to') {
            return $to;
        }

        return $from;
    }

    /**
     * 设备详情页的归属关系拓扑图的数据.
     *
     * @param $device_id
     *
     * @return array|false|string
     */
    public static function makeDeviceRelatedChartData($device_id): bool|array|string
    {
        $return = [];
        $device_record = DeviceRecord::where('id', $device_id)->first();
        if (!empty($device_record)) {
            $return = [
                'name' => $device_record->asset_number,
                'children' => [
                    [
                        'name' => trans('main.part'),
                        'children' => [],
                    ],
                    [
                        'name' => trans('main.software'),
                        'children' => [],
                    ],
                    [
                        'name' => trans('main.service'),
                        'children' => [],
                    ],
                ],
            ];
            foreach ($device_record->part as $part) {
                $return['children'][0]['children'][] = ['name' => $part->asset_number];
            }
            foreach ($device_record->software as $software) {
                $return['children'][1]['children'][] = ['name' => $software->name];
            }
            foreach ($device_record->service as $service) {
                $return['children'][2]['children'][] = ['name' => $service->name];
            }
        }

        return json_encode($return);
    }

    /**
     * 判断当前资产编号是否被用过
     * @param $asset_number
     * @return bool
     */
    public static function ifAssetNumberUsed($asset_number): bool
    {
        $device_record = DeviceRecord::where('asset_number', $asset_number)->withTrashed()->first();
        if (!empty($device_record)) {
            return true;
        }
        $part_record = PartRecord::where('asset_number', $asset_number)->withTrashed()->first();
        if (!empty($part_record)) {
            return true;
        }
        $software_record = SoftwareRecord::where('asset_number', $asset_number)->withTrashed()->first();
        if (!empty($software_record)) {
            return true;
        }
        return false;
    }

    /**
     * 单选选择用户格式定制.
     * @param $key
     * @return array
     */
    public static function selectUsers($key): array
    {
        $users = User::all();
        $return = [];
        foreach ($users as $user) {
            $return[$user->$key] = $user->name . ' - ' . $user->username;
        }
        return $return;
    }
}
