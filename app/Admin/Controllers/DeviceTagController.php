<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class DeviceTagController
{
    /**
     * 页面.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */


    public function checktag(Request $request): View|Factory|Application
    {
        $assetnumber = $request->input("assetnumber");
        if (!empty($assetnumber)) {
			
			$datas = DB::table('device_records')
			   ->select([
					'device_records.asset_number AS asset_number',
					'device_records.name AS name',
					'device_categories.name AS categories',
					'device_records.description AS description',
					'device_records.ip AS ip',
					'departments.name AS departments',
					'admin_users.name AS admin_users',
					'device_records.expired AS expired',
					'device_records.purchased AS purchased',
					'device_records.model AS model' 
			   ])
			   ->join('device_categories', 'device_records.category_id', '=', 'device_categories.id')
			   ->join('device_tracks', 'device_records.id', '=', 'device_tracks.device_id')
			   ->join('admin_users', 'admin_users.id', '=', 'device_tracks.user_id')
			   ->join('departments', 'admin_users.department_id', '=', 'departments.id')
			   ->where('device_records.asset_number', $assetnumber)
			   ->get();
			
            return view("check_tag", ["data" => $datas[0]]);
        }
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }
}