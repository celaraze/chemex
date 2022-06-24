<?php

namespace App\Admin\Metrics;

use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\ServiceRecord;
use App\Models\SoftwareRecord;
use App\Support\Support;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class WorthTrend extends Line
{
    /**
     * 图表默认高度.
     *
     * @var int
     */
    protected $chartHeight = 53;
    protected $height = 53;
    protected $chartMarginRight = 1;

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $year = date('Y', time());
        if ($request->get('option') == 'pre_year') {
            $year = (int)$year - 1;
        }
        $from = Support::makeYearDate($year);
        $to = Support::makeYearDate($year, 'to');

        $device_records = DeviceRecord::whereBetween('purchased', [$from, $to])->get();
        $part_records = PartRecord::whereBetween('purchased', [$from, $to])->get();
        $software_records = SoftwareRecord::whereBetween('purchased', [$from, $to])->get();
        $service_records = ServiceRecord::whereBetween('purchased', [$from, $to])->get();

        $data = [];

        $year_all = 0;
        for ($i = 1; $i <= 12; $i++) {
            $item = 0;
            foreach ($device_records as $device_record) {
                $month = date('m', strtotime($device_record->purchased));
                if ($i == $month) {
                    if (!empty($device_record->price)) {
                        $item += $device_record->price;
                    }
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12 && !empty($device_record->price)) {
                    $year_all += $device_record->price;
                }
            }

            foreach ($part_records as $part_record) {
                $month = date('m', strtotime($part_record->purchased));
                if ($i == $month) {
                    if (!empty($part_record->price)) {
                        $item += $part_record->price;
                    }
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12 && !empty($part_record->price)) {
                    $year_all += $part_record->price;
                }
            }

            foreach ($software_records as $software_record) {
                $month = date('m', strtotime($software_record->purchased));
                if ($i == $month) {
                    if (!empty($software_record->price)) {
                        $item += $software_record->price;
                    }
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12 && !empty($software_record->price)) {
                    $year_all += $software_record->price;
                }
            }

            foreach ($service_records as $service_record) {
                $month = date('m', strtotime($service_record->purchased));
                if ($i == $month) {
                    if (!empty($service_record->price)) {
                        $item += $service_record->price;
                    }
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12 && !empty($service_record->price)) {
                    $year_all += $service_record->price;
                }
            }

            array_push($data, $item);
        }

        $this->withContent(trans('main.all_year') . $year_all);
        // 图表数据
        $this->withChart($data);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return WorthTrend
     */
    public function withContent(string $content): WorthTrend
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h4 class="ml-1">{$content}</h4>
</div>
HTML
        );
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return WorthTrend
     */
    public function withChart(array $data): WorthTrend
    {
        $this->chartOptions['tooltip']['x']['show'] = true;

        return $this->chart([
            'series' => [
                [
                    'name' => trans('main.worth'),
                    'data' => $data,
                ],
            ],
            'tooltip' => [
                'x' => [
                    'show' => true,
                ],
            ],
        ]);
    }

    /**
     * 初始化卡片内容.
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title(trans('main.worth_trend'));
        $this->dropdown([
            'current_year' => trans('main.current_year'),
            'pre_year' => trans('main.last_year'),
        ]);
    }
}
