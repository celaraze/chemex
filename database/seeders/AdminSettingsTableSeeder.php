<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('admin_settings')->delete();

        \DB::table('admin_settings')->insert(array(
            0 =>
                array(
                    'slug' => 'field_select_create',
                    'value' => '1',
                    'created_at' => '2021-01-28 16:47:28',
                    'updated_at' => '2021-01-28 16:47:28',
                ),
            1 =>
                array(
                    'slug' => 'footer_remove',
                    'value' => '1',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-01-28 16:47:25',
                ),
            2 =>
                array(
                    'slug' => 'grid_row_actions_right',
                    'value' => '0',
                    'created_at' => '2021-02-09 14:16:58',
                    'updated_at' => '2021-02-17 16:49:13',
                ),
            3 =>
                array(
                    'slug' => 'header_blocks',
                    'value' => '1',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-01-28 16:47:25',
                ),
            4 =>
                array(
                    'slug' => 'header_padding_fix',
                    'value' => '1',
                    'created_at' => '2021-02-22 08:47:24',
                    'updated_at' => '2021-02-22 08:47:24',
                ),
            5 =>
                array(
                    'slug' => 'sidebar_indentation',
                    'value' => '1',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-01-28 16:47:25',
                ),
            6 =>
                array(
                    'slug' => 'sidebar_style',
                    'value' => 'horizontal_menu',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-02-22 08:47:24',
                ),
            7 =>
                array(
                    'slug' => 'site_debug',
                    'value' => '1',
                    'created_at' => '2021-01-28 16:47:03',
                    'updated_at' => '2021-01-28 16:47:17',
                ),
            8 =>
                array(
                    'slug' => 'site_lang',
                    'value' => 'zh_CN',
                    'created_at' => '2021-01-28 16:47:03',
                    'updated_at' => '2021-02-08 08:12:47',
                ),
            9 =>
                array(
                    'slug' => 'site_logo',
                    'value' => '',
                    'created_at' => '2020-12-20 13:57:11',
                    'updated_at' => '2020-12-20 13:57:11',
                ),
            10 =>
                array(
                    'slug' => 'site_logo_mini',
                    'value' => '',
                    'created_at' => '2020-12-20 13:57:11',
                    'updated_at' => '2020-12-20 13:57:11',
                ),
            11 =>
                array(
                    'slug' => 'site_logo_text',
                    'value' => '资产管理系统',
                    'created_at' => '2020-12-20 13:57:11',
                    'updated_at' => '2021-01-28 20:34:45',
                ),
            12 =>
                array(
                    'slug' => 'site_title',
                    'value' => '资产管理系统',
                    'created_at' => '2020-12-20 13:57:11',
                    'updated_at' => '2021-01-28 20:34:45',
                ),
            13 =>
                array(
                    'slug' => 'site_url',
                    'value' => '',
                    'created_at' => '2021-01-28 16:47:03',
                    'updated_at' => '2021-01-28 16:47:03',
                ),
            14 =>
                array(
                    'slug' => 'switch_to_select_create',
                    'value' => '0',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-02-08 08:48:00',
                ),
            15 =>
                array(
                    'slug' => 'theme_color',
                    'value' => 'blue-light',
                    'created_at' => '2021-01-28 16:47:25',
                    'updated_at' => '2021-01-28 16:47:25',
                ),
        ));


    }
}
