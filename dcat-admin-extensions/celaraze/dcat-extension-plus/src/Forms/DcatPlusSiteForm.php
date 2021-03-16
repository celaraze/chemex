<?php

namespace Celaraze\DcatPlus\Forms;

use Celaraze\DcatPlus\Support;
use Dcat\Admin\Widgets\Form;

class DcatPlusSiteForm extends Form
{
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        admin_setting($input);

        return $this
            ->response()
            ->success('站点配置更新成功！')
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->url('site_url', Support::trans('main.site_url'))
            ->help('站点域名决定了静态资源（头像、图片等）的显示路径，可以包含端口号，例如 http://chemex.it:8000 。')
            ->default(admin_setting('site_url'));
        $this->text('site_title', Support::trans('main.site_title'))
            ->default(admin_setting('site_title'));
        $this->text('site_logo_text', Support::trans('main.site_logo_text'))
            ->help('文本LOGO显示的优先度低于图片，当没有上传图片作为LOGO时，此项将生效。')
            ->default(admin_setting('site_logo_text'));
        $this->image('site_logo', Support::trans('main.site_logo'))
            ->autoUpload()
            ->uniqueName()
            ->default(admin_setting('site_logo'));
        $this->image('site_logo_mini', Support::trans('main.site_logo_mini'))
            ->autoUpload()
            ->uniqueName()
            ->default(admin_setting('site_logo_mini'));
        $this->switch('site_debug', Support::trans('main.site_debug'))
            ->help('开启 debug 模式后将会显示异常捕获信息，关闭则只返回 500 状态码。')
            ->default(admin_setting('site_debug'));
        $this->radio('site_lang', Support::trans('main.site_lang'))
            ->options([
                'zh_CN' => '中文（简体）',
                'en'    => 'English',
            ])
            ->default(admin_setting('site_lang'));
    }
}
