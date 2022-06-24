<?php

namespace App\Admin\Controllers;

use Ace\Uni;
use App\Http\Controllers\Controller;
use App\Services\VersionService;
use App\Support\Version;
use Celaraze\Response;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\ArrayShape;

class VersionController extends Controller
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
        $version = config('admin.chemex_version');
        $description = Version::list()['geisha'];

        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) use ($version, $description) {
                $row->column(3, function (Column $column) use ($version) {
                    $column->row(new Card(view('version.upgrade')));
                    $column->row(new Card(admin_trans_label('Current Version'), $version));
                });
                $row->column(9, function (Column $column) use ($description) {
                    $column->row(new Card($description['name'], $description['description']));
                });
            });
    }/**/

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }

    /**
     * 版本升级.
     *
     * @return array|JsonResponse
     */
    #[ArrayShape(['code' => "int", 'message' => "string", "data" => "mixed"])]
    public function upgrade(): JsonResponse|array
    {
        $result = VersionService::upgrade();
        return Uni::response(200, '更新成功！', [$result]);
    }
}
