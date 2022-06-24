<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Version;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Contracts\Translation\Translator;

class SiteVersionController extends Controller
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
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(function (Row $row) {
                $tab = new Tab();
                $tab->addLink(trans('main.site_setting'), admin_route('site.setting.index'));
                $tab->addLink(trans('main.site_ui'), admin_route('site.ui.index'));
                $tab->addLink(trans('main.site_ldap'), admin_route('site.ldap.index'));
                $tab->add(trans('main.site_version'), $this->render(), true);
                $row->column(12, $tab->withCard());
            });
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }

    /**
     * 重写渲染为自定义.
     * @return Row
     */
    public function render(): Row
    {
        return new Row(function (Column $column) {
            $version = config('admin.chemex_version');
            $description = Version::list()['geisha'];
//            $column->row(new Card(view('version.upgrade')));
            $column->row(new Card(admin_trans_label('Current Version'), $version));
            $column->row(new Card($description['name'], $description['description']));
        });
    }
}
