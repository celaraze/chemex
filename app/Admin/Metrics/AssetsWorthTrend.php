<?php

namespace App\Admin\Metrics;

use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\ServiceRecord;
use App\Models\SoftwareRecord;
use App\Support\Support;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class AssetsWorthTrend extends Line
{
    /**
     * 图表默认高度.
     *
     * @var int
     */
    protected $chartHeight = 230;
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
        $data['worth'] = [];
        $data['device'] = [];
        $data['part'] = [];
        $data['software'] = [];
        $data['service'] = [];

        $year_all = 0;
        for ($i = 1; $i <= 12; $i++) {
            $item = 0;
            $device = 0;
            $part = 0;
            $software = 0;
            $service = 0;
            foreach ($device_records as $device_record) {
                $month = date('m', strtotime($device_record->purchased));
                if ($i == $month) {
                    if (!empty($device_record->price)) {
                        $item += $device_record->price;
                        $device = $device_record->price;
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
                        $part += $part_record->price;
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
                        $software = $software_record->price;
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
                        $service = $service_record->price;
                    }
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12 && !empty($service_record->price)) {
                    $year_all += $service_record->price;
                }
            }

            array_push($data['worth'], $item);
            array_push($data['device'], $device);
            array_push($data['part'], $part);
            array_push($data['software'], $software);
            array_push($data['service'], $service);
        }

        $this->withContent();
        // 图表数据
        $this->withChart($data);
    }

    /**
     * 设置卡片内.
     *
     * @return AssetsWorthTrend
     */
    public function withContent(): AssetsWorthTrend
    {
        return $this->content(
            <<<'HTML'
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
</div>
HTML
        );
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return AssetsWorthTrend
     */
    public function withChart(array $data): AssetsWorthTrend
    {
        $this->chartOptions['tooltip']['x']['show'] = true;

        return $this->chart([
            'series' => [
                [
                    'name' => trans('main.device'),
                    'data' => $data['device'],
                ],
                [
                    'name' => trans('main.part'),
                    'data' => $data['part'],
                ],
                [
                    'name' => trans('main.software'),
                    'data' => $data['software'],
                ],
                [
                    'name' => trans('main.service'),
                    'data' => $data['service'],
                ],
            ],
            'tooltip' => [
                'x' => [
                    'show' => true,
                ],
            ],
            'colors' => [
                '#9475CC',
                '#63B5F7',
                '#4CB5AB',
                '#FF994C',
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

        $this->title(trans('main.item_worth_trend'));
        $this->dropdown([
            'current_year' => trans('main.current_year'),
            'pre_year' => trans('main.last_year'),
        ]);
    }
}
