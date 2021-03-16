<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;

/**
 * 和安装、升级相关的功能服务
 * Class InstallService.
 */
class ConfigService
{
    /**
     * 执行数据库迁移，一般用在升级之后的数据库结构更新动作上.
     *
     * @return bool
     */
    public static function migrate(): bool
    {
        try {
            Artisan::call('migrate');

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * 清理全部已编译的缓存.
     *
     * @return bool
     */
    public static function clear(): bool
    {
        try {
            Artisan::call('optimize:clear');

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * 初始化配置（覆盖默认config）.
     */
    public static function init()
    {
        /**
         * 处理AD HOSTS到数组.
         */
        $ad_hosts = [
            admin_setting('ad_host_primary'),
        ];
        if (!empty(admin_setting('ad_host_secondary'))) {
            array_push($ad_hosts, admin_setting('ad_host_secondary'));
        }

        /**
         * 处理AD端口号.
         */
        $ad_port = admin_setting('ad_port_primary');
        $ad_port = (int) $ad_port;

        /**
         * 处理AD SSL 和 TLS 协议，如果没填这个配置，就为false，否则就是本身设置的值
         */
        $ad_use_ssl = admin_setting('ad_use_ssl');
        $ad_use_ssl = empty($ad_use_ssl) ? false : $ad_use_ssl;
        $ad_use_tls = admin_setting('ad_use_tls');
        $ad_use_tls = empty($ad_use_tls) ? false : $ad_use_tls;

        /**
         * 复写admin站点配置.
         */
        config([
            'filesystems.disks.admin.url' => config('app.url').'/uploads',

            'ldap.connections.default.settings.hosts'    => $ad_hosts,
            'ldap.connections.default.settings.port'     => $ad_port,
            'ldap.connections.default.settings.base_dn'  => admin_setting('ad_base_dn'),
            'ldap.connections.default.settings.username' => admin_setting('ad_username'),
            'ldap.connections.default.settings.password' => admin_setting('ad_password'),
            'ldap.connections.default.settings.use_ssl'  => $ad_use_ssl,
            'ldap.connections.default.settings.use_tls'  => $ad_use_tls,
        ]);
    }
}
