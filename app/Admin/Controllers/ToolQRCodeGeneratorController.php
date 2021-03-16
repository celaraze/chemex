<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Support;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;

class ToolQRCodeGeneratorController extends Controller
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
                    $column->row(new Card(admin_trans_label('Step One'), view('tool_qrcode_generator.download')));
                    $data = Support::getAllItemTypeAndId();
                    $column->row(new Card(admin_trans_label('Step Two'), view('tool_qrcode_generator.data')->with('data', $data)));
                    $column->row(new Card(admin_trans_label('Step Three'), view('tool_qrcode_generator.run')));
                });
            });
    }

    public function title()
    {
        return admin_trans_label('title');
    }
}
