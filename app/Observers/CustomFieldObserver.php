<?php

namespace App\Observers;

use App\Models\CustomColumn;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomFieldObserver
{
    /**
     * Handle the CustomColumn "created" event.
     *
     * @param CustomColumn $customColumn
     *
     * @return void
     */
    public function created(CustomColumn $customColumn)
    {
        try {
            Schema::table($customColumn->table_name, function (Blueprint $table) use ($customColumn) {
                $type = $customColumn->type;
                if ($customColumn->is_nullable == 0) {
                    $nullable = false;
                } else {
                    $nullable = true;
                }
                if ($type == 'date' || $type == 'dateTime') {
                    $nullable = true;
                }
                if ($type == 'select') {
                    $type = 'string';
                }
                $table->$type($customColumn->name)->nullable($nullable);
            });
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }

    /**
     * Handle the CustomColumn "updated" event.
     *
     * @param CustomColumn $customColumn
     *
     * @return void
     */
    public function updated(CustomColumn $customColumn)
    {
        //
    }

    /**
     * Handle the CustomColumn "deleted" event.
     *
     * @param CustomColumn $customColumn
     *
     * @return void
     */
    public function deleted(CustomColumn $customColumn)
    {
        try {
            Schema::table($customColumn->table_name, function (Blueprint $table) use ($customColumn) {
                $table->dropColumn($customColumn->name);
            });
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }

    /**
     * Handle the CustomColumn "restored" event.
     *
     * @param CustomColumn $customColumn
     *
     * @return void
     */
    public function restored(CustomColumn $customColumn)
    {
        //
    }

    /**
     * Handle the CustomColumn "force deleted" event.
     *
     * @param CustomColumn $customColumn
     *
     * @return void
     */
    public function forceDeleted(CustomColumn $customColumn)
    {
        //
    }
}
