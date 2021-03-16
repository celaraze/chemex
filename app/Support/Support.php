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
use Dcat\Admin\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Support
{
    /**
     * 获取所有物品的类型和ID，并且以 device:1 形式加入到数组中返回.
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
            array_push($data, 'device:'.$device_record->id.'&#10;');
        }
        foreach ($part_records as $part_record) {
            array_push($data, 'part:'.$part_record->id.'&#10;');
        }
        foreach ($software_records as $software_record) {
            array_push($data, 'software:'.$software_record->id.'&#10;');
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
        array_push($data, $template);
        if (!empty($item_track->deleted_at)) {
            $template['status'] = '-';
            $template['datetime'] = json_decode($item_track, true)['deleted_at'];
            array_push($data, $template);
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

        switch ($type) {
            // 盘盈
            case 'Y':
                $count = CheckTrack::where('check_id', $check_id)
                    ->where('status', 1)
                    ->count();
                break;
            // 盘亏
            case 'N':
                $count = CheckTrack::where('check_id', $check_id)
                    ->where('status', 2)
                    ->count();
                break;
            // 剩余
            case 'L':
                $count = CheckTrack::where('check_id', $check_id)
                    ->where('status', 0)
                    ->count();
                break;
            default:
                $count = CheckTrack::where('check_id', $check_id)
                    ->withTrashed()
                    ->count();

        }

        return $count;
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
     * 物品id换取物品名称.
     *
     * @param $item
     * @param $item_id
     *
     * @return string
     */
    public static function itemIdToItemName($item, $item_id): string
    {
        $item_record = self::getItemRecordByClass($item, $item_id);
        if (empty($item_record)) {
            return '失踪了';
        } else {
            return $item_record->name;
        }
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
        $item_record = null;
        switch ($item) {
            case 'part':
                $item_record = PartRecord::where('id', $item_id)->first();
                break;
            case 'software':
                $item_record = SoftwareRecord::where('id', $item_id)->first();
                break;
            default:
                $item_record = DeviceRecord::where('id', $item_id)->first();
        }

        return $item_record;
    }

    /**
     * 获取折旧后的价格
     *
     * @param $price
     * @param $date
     * @param $depreciation_rule_id
     *
     * @return float|int
     */
    public static function depreciationPrice($price, $date, $depreciation_rule_id)
    {
        $depreciation = DepreciationRule::where('id', $depreciation_rule_id)->first();
        if (empty($depreciation)) {
            return $price;
        } else {
            $purchased_timestamp = strtotime($date);
            $now_timestamp = time();

            $diff = $now_timestamp - $purchased_timestamp;
            if ($diff < 0) {
                return $price;
            }

            $data = $depreciation['rules'];

            // 数组过滤器
            $return = array_filter($data, function ($item) use ($diff) {
                switch ($item['scale']) {
                    case 'month':
                        $number = (int) $item['number'] * 24 * 60 * 60 * 30;
                        break;
                    case 'year':
                        $number = (int) $item['number'] * 24 * 60 * 60 * 365;
                        break;
                    default:
                        $number = (int) $item['number'] * 24 * 60 * 60;
                }

                return $diff >= $number;
            });

            if (!empty($return)) {
                array_multisort(array_column($return, 'number'), SORT_DESC, $return);
                $price = $price * (float) $return[0]['ratio'];
            }

            return $price;
        }
    }

    /**
     * 根据模型查找折旧规则的id（记录的优先级>分类的优先级）.
     *
     * @param Model $model
     *
     * @return mixed|null
     */
    public static function getDepreciationRuleId(Model $model)
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
    public static function getServiceIssueStatus()
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
                $service->device_name = $service_track->device->name;
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
                    $issue = $service_issue->issue.'<br>';
                    array_push($issues, $issue);
                }
                // 如果是修复的
                if ($service_issue->status == 2) {
                    $service->status = 0;
                    $issue = '<span class="status-recovery">[已修复最近一个问题]</span> '.$service_issue->issue.'<br>';
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
                    array_push($issues, $issue);
                }
            }
            // 如果暂停了
            if ($service_status == 1) {
                $service->status = 3;
                $service->start = date('Y-m-d H:i:s', strtotime($service->updated_at));
            }
            $service->issues = $issues;
        }
        $services = json_decode($services, true);

        return $services;
    }

    /**
     * 返回某一年的开始时间和结束时间.
     *
     * @param $year
     * @param string $field
     *
     * @return false|string
     */
    public static function makeYearDate($year, $field = 'from')
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
    public static function makeDeviceRelatedChartData($device_id)
    {
        $return = [];
        $device_record = DeviceRecord::where('id', $device_id)->first();
        if (!empty($device_record)) {
            $return = [
                'name'     => $device_record->name,
                'children' => [
                    [
                        'name'     => trans('main.part'),
                        'children' => [],
                    ],
                    [
                        'name'     => trans('main.software'),
                        'children' => [],
                    ],
                    [
                        'name'     => trans('main.service'),
                        'children' => [],
                    ],
                ],
            ];
            foreach ($device_record->part as $part) {
                array_push($return['children'][0]['children'], ['name' => $part->name]);
            }
            foreach ($device_record->software as $software) {
                array_push($return['children'][1]['children'], ['name' => $software->name]);
            }
            foreach ($device_record->service as $service) {
                array_push($return['children'][2]['children'], ['name' => $service->name]);
            }
        }

        return json_encode($return);
    }
}
