<?php

namespace App\Admin\Metrics;

use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\ServiceRecord;
use App\Models\SoftwareRecord;
use App\Support\Support;

class WorthHeatmap
{
    public static function handle()
    {
        $year = date('Y', time());
        $from = Support::makeYearDate($year);
        $to = Support::makeYearDate($year, 'to');
        $device_records = DeviceRecord::whereBetween('purchased', [$from, $to])->get();
        $part_records = PartRecord::whereBetween('purchased', [$from, $to])->get();
        $software_records = SoftwareRecord::whereBetween('purchased', [$from, $to])->get();
        $service_records = ServiceRecord::whereBetween('purchased', [$from, $to])->get();
        $all_year_worth = [];
        for ($m = 1; $m <= 12; $m++) {
            $days = [];
            for ($d = 1; $d <= 31; $d++) {
                $day_worth = 0;
                foreach ($device_records as $device_record) {
                    $month = date('m', strtotime($device_record->purchased));
                    $day = date('d', strtotime($device_record->purchased));
                    if ($m == $month && $d == $day) {
                        if (!empty($device_record->price)) {
                            $day_worth += $device_record->price;
                        }
                    }
                }
                foreach ($part_records as $part_record) {
                    $month = date('m', strtotime($part_record->purchased));
                    $day = date('d', strtotime($part_record->purchased));
                    if ($m == $month && $d == $day) {
                        if (!empty($part_record->price)) {
                            $day_worth += $part_record->price;
                        }
                    }
                }
                foreach ($software_records as $software_record) {
                    $month = date('m', strtotime($software_record->purchased));
                    $day = date('d', strtotime($software_record->purchased));
                    if ($m == $month && $d == $day) {
                        if (!empty($software_record->price)) {
                            $day_worth += $software_record->price;
                        }
                    }
                }
                foreach ($service_records as $service_record) {
                    $month = date('m', strtotime($service_record->purchased));
                    $day = date('d', strtotime($service_record->purchased));
                    if ($m == $month && $d == $day) {
                        if (!empty($service_record->price)) {
                            $day_worth += $service_record->price;
                        }
                    }
                }
                array_push($days, $day_worth);
            }
            array_push($all_year_worth, $days);
        }

        return json_encode($all_year_worth);
    }
}
