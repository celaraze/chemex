<?php

namespace App\Admin\Forms;

use App\Models\ConsumableRecord;
use App\Models\ConsumableTrack;
use App\Support\Support;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Exception;

class ConsumableRecordOutForm extends Form
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
        $user_id = $input['user_id'] ?? null;
        $description = $input['description'] ?? null;
        if (empty($consumable_record_id) || empty($number) || empty($user_id)) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $consumable_track = ConsumableTrack::where('consumable_id', $consumable_record_id)->first();
            if (empty($consumable_track)) {
                return $this->response()
                    ->error(trans('main.record_none'));
            } else {
                if ($number > $consumable_track->number) {
                    return $this->response()
                        ->error(trans('main.shortage_in_number'));
                }
                $new_consumable_track = $consumable_track->replicate();
                $new_consumable_track->number -= $number;
                $new_consumable_track->change = $number;
                $new_consumable_track->user_id = $user_id;
                $new_consumable_track->description = $description;
                $new_consumable_track->save();
                $consumable_track->delete();
            }
            $return = $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $e) {
            $return = $this
                ->response()
                ->error(trans('main.fail') . $e->getMessage());
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
        $this->select('user_id', trans('main.user_id'))
            ->options(Support::selectUsers('id'))
            ->required();
        $this->textarea('description');
    }
}
