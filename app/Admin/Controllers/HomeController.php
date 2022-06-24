<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\AllWorth;
use App\Admin\Metrics\AssetsWorthTrend;
use App\Admin\Metrics\BannerMaintenanceRecordCounts;
use App\Admin\Metrics\BannerMyAssetsWorth;
use App\Admin\Metrics\BannerMyTodoCounts;
use App\Admin\Metrics\BannerServiceIssueCounts;
use App\Admin\Metrics\DefectTrend;
use App\Admin\Metrics\DeviceWorth;
use App\Admin\Metrics\PartWorth;
use App\Admin\Metrics\ServiceWorth;
use App\Admin\Metrics\SoftwareWorth;
use App\Admin\Metrics\WorthTrend;
use App\Http\Controllers\Controller;
use App\Support\Support;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;

class HomeController extends Controller
{
    public function index(Content $content): Content
    {
        return $content
            ->title(admin_trans_label('title'))
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $row->column(3, new BannerMyAssetsWorth());
                // @permissions
                if (Admin::user()->can('home.asset.admin')) {
                    $row->column(3, new BannerMyTodoCounts());
                    $row->column(3, new BannerMaintenanceRecordCounts());
                    $row->column(3, new BannerServiceIssueCounts());
                }
                // @permissions
                if (Admin::user()->can('home.dashboard')) {
                    $row->column(12, '<hr>');
                    $row->column(12, function (Column $column) {
                        $column->row(function (Row $row) {
                            $row->column(3, function (Column $column) {
                                $column->row(new WorthTrend());
                                $column->row(new DefectTrend());
                            });
                            $row->column(9, function (Column $column) {
                                $column->row(function (Row $row) {
                                    $row->column(7, new AssetsWorthTrend());
                                    $row->column(5, function (Column $column) {
                                        $column->row(new AllWorth());
                                        $column->row(function (Row $row) {
                                            $row->column(6, new DeviceWorth());
                                            $row->column(6, new PartWorth());
                                        });
                                        $column->row(function (Row $row) {
                                            $row->column(6, new SoftwareWorth());
                                            $row->column(6, new ServiceWorth());
                                        });
                                    });
                                });
                            });
                        });
                    });
                    $services = Support::getServiceIssueStatus();
                    $row->column(12, new Card(trans('main.service_status'), view('services_dashboard')
                        ->with('services', $services)));
                }
            });
    }
}
