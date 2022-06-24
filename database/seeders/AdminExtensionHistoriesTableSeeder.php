<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminExtensionHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('admin_extension_histories')->delete();

        \DB::table('admin_extension_histories')->insert(array(
            0 =>
                array(
                    'id' => '1',
                    'name' => 'celaraze.dcat-extension-plus',
                    'type' => '1',
                    'version' => '1.0.7',
                    'detail' => '支持DcatAdmin 2.0.18beta。',
                    'created_at' => '2021-02-22 11:59:03',
                    'updated_at' => '2021-02-22 11:59:03',
                ),
            1 =>
                array(
                    'id' => '2',
                    'name' => 'celaraze.dcat-extension-plus',
                    'type' => '1',
                    'version' => '1.0.7',
                    'detail' => '暂时移除侧栏菜单子菜单缩进（不兼容）。',
                    'created_at' => '2021-02-22 11:59:03',
                    'updated_at' => '2021-02-22 11:59:03',
                ),
            2 =>
                array(
                    'id' => '3',
                    'name' => 'celaraze.dcat-extension-plus',
                    'type' => '1',
                    'version' => '1.0.7',
                    'detail' => '增加水平菜单选项。',
                    'created_at' => '2021-02-22 11:59:03',
                    'updated_at' => '2021-02-22 11:59:03',
                ),
            3 =>
                array(
                    'id' => '4',
                    'name' => 'celaraze.dcat-extension-plus',
                    'type' => '1',
                    'version' => '1.0.7',
                    'detail' => '原先的头部块状显示改为边距优化',
                    'created_at' => '2021-02-22 11:59:03',
                    'updated_at' => '2021-02-22 11:59:03',
                ),
        ));


    }
}
