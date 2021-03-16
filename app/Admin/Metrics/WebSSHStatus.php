<?php

namespace App\Admin\Metrics;

use App\Services\SSHService;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class WebSSHStatus extends Line
{
    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $web_ssh_installed = SSHService::checkWebSSHServiceInstalled();
        $web_ssh_service = SSHService::checkWebSSHServiceStatus('http://127.0.0.1:8222');
        if ($web_ssh_service == 200) {
            $text = admin_trans_label('Normal');
            $color = '#00c054';
        } else {
            $text = admin_trans_label('Stopped');
            $color = '#997643';
        }
        if ($web_ssh_installed == 0) {
            $text = admin_trans_label('Not Installed');
            $color = '#9f1447';
        }

        $this->withContent($text, $color);
    }

    /**
     * 设置卡片内容.
     *
     * @param $text
     * @param $color
     *
     * @return $this
     */
    public function withContent($text, $color): WebSSHStatus
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1" style="color: $color;">{$text}</h2>
</div>
HTML
        );
    }

    /**
     * 初始化卡片内容.
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title(admin_trans_label('Web SSH Service'))->height(120);
    }
}
