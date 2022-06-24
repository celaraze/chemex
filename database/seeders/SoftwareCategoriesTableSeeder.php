<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SoftwareCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('software_categories')->delete();

        \DB::table('software_categories')->insert(array(
            0 =>
                array(
                    'id' => '1',
                    'name' => '操作系统',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:22:31',
                    'updated_at' => '2021-01-19 19:22:36',
                ),
            1 =>
                array(
                    'id' => '2',
                    'name' => '办公应用',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:22:53',
                    'updated_at' => '2021-01-19 19:22:53',
                ),
            2 =>
                array(
                    'id' => '3',
                    'name' => '图像处理',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:22:59',
                    'updated_at' => '2021-01-19 19:22:59',
                ),
            3 =>
                array(
                    'id' => '4',
                    'name' => '网络工具',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:23:04',
                    'updated_at' => '2021-01-19 19:23:10',
                ),
            4 =>
                array(
                    'id' => '5',
                    'name' => '影音工具',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:23:35',
                    'updated_at' => '2021-01-19 19:23:35',
                ),
            5 =>
                array(
                    'id' => '6',
                    'name' => '系统工具',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:23:47',
                    'updated_at' => '2021-01-19 19:23:47',
                ),
            6 =>
                array(
                    'id' => '7',
                    'name' => '设计工具',
                    'description' => NULL,
                    'parent_id' => NULL,
                    'order' => '0',
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 19:24:05',
                    'updated_at' => '2021-01-19 19:24:05',
                ),
        ));


    }
}
