<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SoftwareCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('software_categories')->delete();

        DB::table('software_categories')->insert([
            0 => [
                'id'          => 1,
                'name'        => '操作系统',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:22:31',
                'updated_at'  => '2021-01-19 19:22:36',
                'parent_id'   => null,
                'order'       => '0',
            ],
            1 => [
                'id'          => 2,
                'name'        => '办公应用',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:22:53',
                'updated_at'  => '2021-01-19 19:22:53',
                'parent_id'   => null,
                'order'       => '0',
            ],
            2 => [
                'id'          => 3,
                'name'        => '图像处理',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:22:59',
                'updated_at'  => '2021-01-19 19:22:59',
                'parent_id'   => null,
                'order'       => '0',
            ],
            3 => [
                'id'          => 4,
                'name'        => '网络工具',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:23:04',
                'updated_at'  => '2021-01-19 19:23:10',
                'parent_id'   => null,
                'order'       => '0',
            ],
            4 => [
                'id'          => 5,
                'name'        => '影音工具',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:23:35',
                'updated_at'  => '2021-01-19 19:23:35',
                'parent_id'   => null,
                'order'       => '0',
            ],
            5 => [
                'id'          => 6,
                'name'        => '系统工具',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:23:47',
                'updated_at'  => '2021-01-19 19:23:47',
                'parent_id'   => null,
                'order'       => '0',
            ],
            6 => [
                'id'          => 7,
                'name'        => '设计工具',
                'description' => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 19:24:05',
                'updated_at'  => '2021-01-19 19:24:05',
                'parent_id'   => null,
                'order'       => '0',
            ],
        ]);
    }
}
