<?php

namespace App\Admin\Metrics;

use App\Models\MaintenanceRecord;
use App\Models\ServiceIssue;
use App\Support\Support;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class DefectTrend extends Line
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
            $year = (int) $year - 1;
        }
        $from = Support::makeYearDate($year);
        $to = Support::makeYearDate($year, 'to');

        $maintenance_records = MaintenanceRecord::whereBetween('ng_time', [$from, $to])->get();
        $service_issues = ServiceIssue::whereBetween('start', [$from, $to])->get();

        $data = [];
        $data['maintenance'] = [];
        $data['issue'] = [];

        $year_all = 0;

        for ($i = 1; $i <= 12; $i++) {
            $maintenance = 0;
            $issue = 0;
            foreach ($maintenance_records as $maintenance_record) {
                $month = date('m', strtotime($maintenance_record->ng_time));
                if ($i == $month) {
                    $maintenance++;
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12) {
                    $year_all++;
                }
            }
            foreach ($service_issues as $service_issue) {
                $month = date('m', strtotime($service_issue->start));
                if ($i == $month) {
                    $issue++;
                }
                // 全年数据，以最后一个月来计算，这里12目的是让循环只执行一次
                if ($i == 12) {
                    $year_all++;
                }
            }
            array_push($data['maintenance'], $maintenance);
            array_push($data['issue'], $issue);
        }

        // 图表数据
        $this->withContent();
        $this->withChart($data);
    }

    /**
     * 设置卡片内容.
     *
     * @return DefectTrend
     */
    public function withContent(): DefectTrend
    {
        return $this->content(
            <<<'HTML'
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h4 class="ml-1"></h4>
</div>
HTML
        );
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return DefectTrend
     */
    public function withChart(array $data): DefectTrend
    {
        return $this->chart([
            'series' => [
                [
                    'name' => trans('main.maintenance_times'),
                    'data' => $data['maintenance'],
                ],
                [
                    'name' => trans('main.issue_times'),
                    'data' => $data['issue'],
                ],
            ],
            'tooltip' => [
                'x' => [
                    'show' => true,
                ],
            ],
            'colors' => [
                '#F48684',
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

        $this->title(trans('main.defect_trend'));
        $this->dropdown([
            'current_year' => trans('main.current_year'),
            'pre_year'     => trans('main.last_year'),
        ]);
    }
}
