<?php

namespace App\Http\Controllers;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Support\Support;
use Illuminate\Http\JsonResponse;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * 盘点.
     *
     * @param $string
     *
     * @return JsonResponse
     */
    public function check($string): JsonResponse
    {
        $item = explode(':', $string)[0];
        $id = explode(':', $string)[1];
        if (!empty($item) && !empty($id)) {
            $check_record = CheckRecord::where('check_item', $item)
                ->where('status', 0)
                ->first();
            if (empty($check_record)) {
                $return = [
                    'code'    => 404,
                    'message' => '没有找到相对应的盘点任务',
                    'data'    => [],
                ];

                return response()->json($return);
            }
            $item = Support::getItemRecordByClass($item, $id);
            if (empty($item)) {
                $return = [
                    'code'    => 404,
                    'message' => '没有找到对应的物品',
                    'data'    => [],
                ];
            } else {
                $check_track = CheckTrack::where('check_id', $check_record->id)
                    ->where('item_id', $item->id)
                    ->first();
                $return = [
                    'code'    => 200,
                    'message' => '查询成功',
                    'data'    => $check_track,
                ];
            }
        } else {
            $return = [
                'code'    => 404,
                'message' => '参数不完整',
                'data'    => [],
            ];
        }

        return response()->json($return);
    }

    /**
     * 盘点动作.
     *
     * @return JsonResponse
     */
    public function checkDo(): JsonResponse
    {
        $track_id = request('track_id') ?? null;
        $check_option = request('option') ?? null;
        if (!empty($track_id) && !empty($check_option)) {
            $user = auth('api')->user();
            $check_track = CheckTrack::where('id', $track_id)->first();
            if (empty($check_track)) {
                $return = [
                    'code'    => 404,
                    'message' => '没有找到盘点内容',
                    'data'    => [],
                ];
            } else {
                $check_track->status = $check_option;
                $check_track->checker = $user->id;
                $check_track->save();
                $return = [
                    'code'    => 200,
                    'message' => '操作成功',
                    'data'    => [],
                ];
            }
        } else {
            $return = [
                'code'    => 404,
                'message' => '参数不完整',
                'data'    => [],
            ];
        }

        return response()->json($return);
    }
}
