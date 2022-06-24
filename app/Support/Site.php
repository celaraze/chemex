<?php

namespace App\Support;


use Dcat\Admin\Admin;
use Illuminate\Support\Facades\Storage;

class Site
{
    /**
     * 初始化配置注入.
     */
    public function initConfig()
    {
        /**
         * 处理站点LOGO自定义.
         */
        if (empty(admin_setting('site_logo'))) {
            $logo = admin_setting('site_logo_text');
        } else {
            $logo = Storage::disk(config('admin.upload.disk'))->url(admin_setting('site_logo'));
            $logo = "<img src='$logo' height='40' width='100%' />";
        }

        /**
         * 处理站点LOGO-MINI自定义.
         */
        if (empty(admin_setting('site_logo_mini'))) {
            $logo_mini = admin_setting('site_logo_text');
        } else {
            $logo_mini = Storage::disk(config('admin.upload.disk'))->url(admin_setting('site_logo_mini'));
            $logo_mini = "<img src='$logo_mini'>";
        }

        /**
         * 处理站点名称.
         */
        if (empty(admin_setting('site_url'))) {
            $site_url = 'http://localhost';
        } else {
            $site_url = admin_setting('site_url');
        }

        if (empty(admin_setting('site_debug'))) {
            $site_debug = true;
        } else {
            $site_debug = admin_setting('site_debug');
        }

        if (empty(admin_setting('theme_color'))) {
            $theme_color = 'blue-light';
        } else {
            $theme_color = admin_setting('theme_color');
        }

        /**
         * 处理AD HOSTS到数组.
         */
        $ad_hosts = [
            admin_setting('ad_host_primary'),
        ];
        if (!empty(admin_setting('ad_host_secondary'))) {
            $ad_hosts[] = admin_setting('ad_host_secondary');
        }

        /**
         * 处理AD端口号.
         */
        $ad_port = admin_setting('ad_port_primary');
        $ad_port = (int)$ad_port;

        /**
         * 处理AD SSL 和 TLS 协议，如果没填这个配置，就为false，否则就是本身设置的值
         */
        $ad_use_ssl = admin_setting('ad_use_ssl');
        $ad_use_ssl = !empty($ad_use_ssl);
        $ad_use_tls = admin_setting('ad_use_tls');
        $ad_use_tls = !empty($ad_use_tls);

        /**
         * 复写admin站点配置.
         */
        config([
            'app.url' => $site_url,
            'app.debug' => $site_debug,
            'app.locale' => admin_setting('site_lang'),
            'app.fallback_locale' => admin_setting('site_lang'),

            'admin.title' => admin_setting('site_title'),
            'admin.logo' => $logo,
            'admin.logo-mini' => $logo_mini,
            'admin.layout.color' => $theme_color,

            'filesystems.disks.admin.url' => config('app.url') . '/uploads',

            'ldap.connections.default.settings.hosts' => $ad_hosts,
            'ldap.connections.default.settings.port' => $ad_port,
            'ldap.connections.default.settings.base_dn' => admin_setting('ad_base_dn'),
            'ldap.connections.default.settings.username' => admin_setting('ad_username'),
            'ldap.connections.default.settings.password' => admin_setting('ad_password'),
            'ldap.connections.default.settings.use_ssl' => $ad_use_ssl,
            'ldap.connections.default.settings.use_tls' => $ad_use_tls,
        ]);
    }

    /**
     * 注入字段.
     */
    public function injectFields()
    {
//        Form::extend('selectCreate', SelectCreate::class);
    }

    /**
     * 底部授权移除.
     */
    public function footerRemove()
    {
        if (admin_setting('footer_remove')) {
            Admin::style(
                <<<'CSS'
.main-footer {
    display: none;
}
CSS
            );
        }
    }

    /**
     * 头部边距优化.
     */
    public function headerPaddingFix()
    {
        if (admin_setting('header_padding_fix')) {
            Admin::style(
                <<<'CSS'
.navbar{
    margin: 0 35px !important;
}

.main-horizontal-sidebar{
    box-sizing: border-box !important;
    padding: 0 35px !important;
    background-color: transparent !important;
}

.nav-link {
    padding: 0;
}

.empty-data {
    text-align: center;
    color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: left;
}

.font-grey {
    color: white;
}

CSS
            );
        }
    }

    /**
     * 行操作按钮最右.
     */
    public function gridRowActionsRight()
    {
        if (admin_setting('grid_row_actions_right')) {
            Admin::style(
                <<<'CSS'
.grid__actions__{
    width: 20%;
    text-align: right;
}
CSS
            );
        }
    }

    /**
     * 引入自定义CSS
     */
    public function customCSS()
    {
        Admin::css('static/css/main.css');
    }
}
