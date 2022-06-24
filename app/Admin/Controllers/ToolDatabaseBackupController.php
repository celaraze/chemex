<?php

namespace App\Admin\Controllers;

use Ace\Uni;
use App\Http\Controllers\Controller;
use Celaraze\Response;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;
use Exception;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use JetBrains\PhpStorm\ArrayShape;

class ToolDatabaseBackupController extends Controller
{
    /**
     * 页面.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->header($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->row(new Card(admin_trans_label('Step One'), view('tool_database_backup.backup')));
                    $column->row(new Card(admin_trans_label('Step Two'), view('tool_database_backup.data')));
                    $column->row(new Card(admin_trans_label('Step Three'), view('tool_database_backup.restore')));
                });
            });
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }

    /**
     * 备份数据.
     * @return \Illuminate\Http\JsonResponse|array
     */
    #[ArrayShape(['code' => "int", 'message' => "string", "data" => "mixed"])]
    public function backup(): JsonResponse|array
    {
        $data = [];
        try {
            Artisan::call('chemex:db-backup');
        } catch (Exception $exception) {
            $data = $exception;
        }
        return Uni::response(200, '备份成功', $data);
    }
}
