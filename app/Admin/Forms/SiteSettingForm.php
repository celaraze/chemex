<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;

class SiteSettingForm extends Form
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
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->url('site_url')
            ->help(admin_trans_label('Site Url Help'))
            ->default(admin_setting('site_url'));
        $this->text('site_title')
            ->default(admin_setting('site_title'));
        $this->text('site_logo_text')
            ->help(admin_trans_label('Site Logo Text'))
            ->default(admin_setting('site_logo_text'));
        $this->image('site_logo')
            ->autoUpload()
            ->uniqueName()
            ->default(admin_setting('site_logo'));
        $this->image('site_logo_mini')
            ->autoUpload()
            ->uniqueName()
            ->default(admin_setting('site_logo_mini'));
        $this->switch('site_debug')
            ->help(admin_trans_label('Site Debug Help'))
            ->default(admin_setting('site_debug'));
        $this->radio('site_lang')
            ->options([
                'zh_CN' => '中文（简体）',
                'en' => 'English',
            ])
            ->default(admin_setting('site_lang'));
    }
}
