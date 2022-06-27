<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DeviceRecord;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class DeviceTagController extends Controller
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
			$data = DeviceRecord::where('asset_number',$assetnumber)->first();
            if(!empty($data)){
                return view("check_tag", ["data" => $data]);
            }
        }
        abort(404);
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }
}