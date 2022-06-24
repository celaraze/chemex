<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chemex:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '对Chemex初始化安装';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('正在优化配置！');
        $this->call('optimize:clear');
        $this->info('正在设置存储系统！');
        $this->call('storage:link');
        $this->info('正在配置APP密钥！');
        $this->call('key:generate');
        $this->call('jwt:secret');
        $this->info('正在处理数据库迁移！');
        $this->call('migrate');
        $this->info('正在初始化基础数据！');
        // 填充菜单
        $this->call('db:seed', ['--class' => 'AdminMenuTableSeeder']);
        // 填充扩展
        $this->call('db:seed', ['--class' => 'AdminExtensionsTableSeeder']);
        // 填充扩展历史
        $this->call('db:seed', ['--class' => 'AdminExtensionHistoriesTableSeeder']);
        // 填充配置
        $this->call('db:seed', ['--class' => 'AdminSettingsTableSeeder']);
        // 填充用户
        $this->call('db:seed', ['--class' => 'AdminUsersTableSeeder']);
        // 填充角色
        $this->call('db:seed', ['--class' => 'AdminRolesTableSeeder']);
        // 填充权限
        $this->call('db:seed', ['--class' => 'AdminPermissionsTableSeeder']);
        // 填充权限-菜单
        $this->call('db:seed', ['--class' => 'AdminPermissionMenuTableSeeder']);
        // 填充角色-菜单
        $this->call('db:seed', ['--class' => 'AdminRoleMenuTableSeeder']);
        // 填充角色-权限
        $this->call('db:seed', ['--class' => 'AdminRolePermissionsTableSeeder']);
        // 填充用户-角色
        $this->call('db:seed', ['--class' => 'AdminRoleUsersTableSeeder']);
        $this->call('chemex:db-fill');
        $this->call('chemex:admin-reset');
        $this->info('安装完成！');
        $this->warn('用户名密码都为：admin');

        return 0;
    }
}
