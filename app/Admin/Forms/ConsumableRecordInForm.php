<?php

namespace App\Admin\Forms;

use App\Models\ConsumableRecord;
use App\Models\ConsumableTrack;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Exception;

class ConsumableRecordInForm extends Form
{
    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     * @return JsonResponse
     */
    public function handle(array $input): JsonResponse
    {
        $consumable_record_id = $input['consumable_id'] ?? null;
        $number = $input['number'] ?? null;
        $purchased = $input['purchased'] ?? null;
        $expired = $input['expired'] ?? null;
        if (empty($consumable_record_id) || empty($number)) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $consumable_track = ConsumableTrack::where('consumable_id', $consumable_record_id)->first();
            if (empty($consumable_track)) {
                $consumable_track = new ConsumableTrack();
                $consumable_track->consumable_id = $consumable_record_id;
                $consumable_track->number = $number;
                $consumable_track->change = $number;
                $consumable_track->purchased = $purchased;
                $consumable_track->expired = $expired;
                $consumable_track->save();
            } else {
                $new_consumable_track = $consumable_track->replicate();
                $new_consumable_track->number += $number;
                $new_consumable_track->change = $number;
                $new_consumable_track->purchased = $purchased;
                $new_consumable_track->expired = $expired;
                $new_consumable_track->save();
                $consumable_track->delete();
            }
            $return = $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $e) {
            $return = $this
                ->response()
                ->error(trans('main.fail').$e->getMessage());
        }

        return $return;
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->select('consumable_id')
            ->options(ConsumableRecord::pluck('name', 'id'))
            ->required();
        $this->currency('number')
            ->symbol('')
            ->required();
        $this->date('purchased');
        $this->date('expired');
    }
}
