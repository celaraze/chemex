<?php


namespace App\Services;


use App\Models\ConsumableRecord;

class ConsumableService
{
    /**
     * 配件删除.
     *
     * @param $consumable_id
     */
    public static function consumableDelete($consumable_id)
    {
        $consumable = ConsumableRecord::where('id', $consumable_id)->first();
        if (!empty($consumable)) {
            $consumable->delete();
        }
    }

    /**
     * 删除配件（强制）.
     *
     * @param $consumable_id
     */
    public static function consumableForceDelete($consumable_id)
    {
        $consumable = ConsumableRecord::where('id', $consumable_id)
            ->withTrashed()
            ->first();
        if (!empty($consumable)) {
            $consumable->forceDelete();
        }
    }
}
