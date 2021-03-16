<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\AllWorth;
use App\Admin\Metrics\DefectTrend;
use App\Admin\Metrics\DeviceWorth;
use App\Admin\Metrics\ItemWorthTrend;
use App\Admin\Metrics\PartWorth;
use App\Admin\Metrics\ServiceWorth;
use App\Admin\Metrics\SoftwareWorth;
use App\Admin\Metrics\WorthTrend;
use App\Http\Controllers\Controller;
use App\Models\User;
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
                $user = User::find(auth('admin')->user()->id);
                $row->column(2, new Card(trans('main.my_all_worth'), $user->itemsPrice()));
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
                                    $row->column(7, new ItemWorthTrend());
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
