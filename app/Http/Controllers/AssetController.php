<?php

namespace App\Http\Controllers;

use Ace\Uni;
use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\SoftwareRecord;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Http\Response
     */
    public function index(): array|JsonResponse
    {
        try {
            $device_records = DeviceRecord::where('asset_number', '!=', null)->get()->toArray();
            $part_records = PartRecord::where('asset_number', '!=', null)->get()->toArray();
            $software_records = SoftwareRecord::where('asset_number', '!=', null)->get()->toArray();
            foreach ($device_records as $device_record) {
                $device_record['asset_type'] = 'device';
            }
            foreach ($part_records as $part_record) {
                $part_record['asset_type'] = 'part';
            }
            foreach ($software_records as $software_record) {
                $software_record['asset_type'] = 'software';
            }
            $records = array_merge($device_records, $part_records, $software_records);
            return Uni::response(200, '查询成功', $records);
        } catch (Exception $exception) {
            return Uni::response(500, $exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $asset_number
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function show(string $asset_number): array|JsonResponse
    {
        // 分割类型和编码
        [$type, $asset_number] = explode(':', $asset_number);
        switch ($type) {
            case 'part':
                $part = PartRecord::where('asset_number', $asset_number)->first();
                if (!empty($part)) {
                    $part->asset_type = 'part';
                    return Uni::response(200, '查询成功', $part);
                }
                break;
            case 'software':
                $software = SoftwareRecord::where('asset_number', $asset_number)->first();
                if (!empty($software)) {
                    $software->asset_type = 'soft';
                    return Uni::response(200, '查询成功', $software);
                }
                break;
            default:
                $device = DeviceRecord::with([
                    'category', 'vendor', 'admin_user', 'admin_user.department', 'depreciation',
                ])->where('asset_number', $asset_number)->first();
                if (!empty($device)) {
                    $device->asset_type = 'device';
                    return Uni::response(200, '查询成功', $device);
                }
        }

        return Uni::response(404, '无法查询到相关信息');
    }

    /**的
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 页面.
     *
     * @param string $asset_number
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function assetCardDevice(string $asset_number): View|Factory|Application
    {
        if (!empty($asset_number)) {
            $data = DeviceRecord::where('asset_number', $asset_number)->first();
            if (!empty($data)) {
                return view("asset_card_device", ["data" => $data]);
            }
        }
        abort(404);
    }
}
