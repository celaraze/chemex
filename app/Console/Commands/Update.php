<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chemex:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '对Chemex进行升级操作';

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
        $this->call('migrate');
        $this->info('数据库迁移完成！');
        // 填充菜单
        $this->call('db:seed', ['--class' => 'AdminMenuTableSeeder']);
        // 填充扩展
        $this->call('db:seed', ['--class' => 'AdminExtensionsTableSeeder']);
        // 填充扩展历史
        $this->call('db:seed', ['--class' => 'AdminExtensionHistoriesTableSeeder']);
        // 填充权限
        $this->call('db:seed', ['--class' => 'AdminPermissionsTableSeeder']);
        // 填充权限-菜单
        $this->call('db:seed', ['--class' => 'AdminPermissionMenuTableSeeder']);
        $this->info('升级完成！');

        return 0;
    }
}
