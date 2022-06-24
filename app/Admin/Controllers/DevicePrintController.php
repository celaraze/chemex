<?php

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\DeviceRecord;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class DevicePrintController extends Controller
{
    /**
     * 页面.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */


    public function tag(Request $request): View|Factory|Application
    {
        $ids = $request->input('ids');
        $ids = explode('-', $ids);
        if (count($ids) > 0) {
            $data = DeviceRecord::find($ids);
            return view("print_tag", ['item' => 'device', 'data' => $data]);
        }
    }

    public function list(Request $request)
    {
        $ids = $request->input('ids');
        $ids = explode('-', $ids);
        if (count($ids) > 0) {
            $data = DeviceRecord::find($ids);
            return view('print_list', ['item' => 'device', 'data' => $data]);
        }
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }
}


