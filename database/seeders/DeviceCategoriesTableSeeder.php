<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DeviceCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_categories')->delete();

        DB::table('device_categories')->insert([
            0 => [
                'id'                   => 1,
                'name'                 => '台式机',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:18:51',
                'updated_at'           => '2021-01-19 19:18:51',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            1 => [
                'id'                   => 2,
                'name'                 => '笔记本',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:18:56',
                'updated_at'           => '2021-01-19 19:18:56',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            2 => [
                'id'                   => 3,
                'name'                 => '服务器',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:01',
                'updated_at'           => '2021-01-19 19:19:01',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            3 => [
                'id'                   => 4,
                'name'                 => '交换机',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:05',
                'updated_at'           => '2021-01-19 19:19:05',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            4 => [
                'id'                   => 5,
                'name'                 => '显示器',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:09',
                'updated_at'           => '2021-01-19 19:19:09',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            5 => [
                'id'                   => 6,
                'name'                 => '路由器',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:14',
                'updated_at'           => '2021-01-19 19:19:14',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            6 => [
                'id'                   => 7,
                'name'                 => '打印机',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:21',
                'updated_at'           => '2021-01-19 19:19:21',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            7 => [
                'id'                   => 8,
                'name'                 => '扫描仪',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:26',
                'updated_at'           => '2021-01-19 19:19:26',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            8 => [
                'id'                   => 9,
                'name'                 => '复印机',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:30',
                'updated_at'           => '2021-01-19 19:19:30',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            9 => [
                'id'                   => 10,
                'name'                 => '平板电脑',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:42',
                'updated_at'           => '2021-01-19 19:19:42',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
            10 => [
                'id'                   => 11,
                'name'                 => 'PDA',
                'description'          => null,
                'deleted_at'           => null,
                'created_at'           => '2021-01-19 19:19:48',
                'updated_at'           => '2021-01-19 19:19:48',
                'depreciation_rule_id' => null,
                'parent_id'            => null,
                'order'                => '0',
            ],
        ]);
    }
}
