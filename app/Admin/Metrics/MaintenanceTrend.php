<?php

namespace App\Admin\Metrics;

use App\Models\MaintenanceRecord;
use App\Support\Support;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class MaintenanceTrend extends Line
{
    /**
     * 图表默认高度.
     *
     * @var int
     */
    protected $chartHeight = 94;
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

        $records = MaintenanceRecord::whereBetween('ng_time', [$from, $to])->get();

        $data = [];

        $year_all = 0;

        for ($i = 1; $i <= 12; $i++) {
            $temp = 0;
            foreach ($records as $record) {
                $month = date('m', strtotime($record->ng_time));
                if ($i == $month) {
                    $temp++;
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12) {
                    $year_all++;
                }
            }
            array_push($data, $temp);
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
     * @return MaintenanceTrend
     */
    public function withContent(string $content): MaintenanceTrend
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
     * @return MaintenanceTrend
     */
    public function withChart(array $data): MaintenanceTrend
    {
        return $this->chart([
            'series' => [
                [
                    'name' => trans('main.maintenance_times'),
                    'data' => $data,
                ],
            ],
            'tooltip' => [
                'x' => [
                    'show' => true,
                ],
            ],
            'colors' => [
                '#B24447',
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

        $this->title(trans('main.maintenance_trend'));
        $this->dropdown([
            'current_year' => trans('main.current_year'),
            'pre_year' => trans('main.last_year'),
        ]);
    }
}
