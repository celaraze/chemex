<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admin_settings')->delete();

        \DB::table('admin_settings')->insert([
            0 => [
                'slug'       => 'field_select_create',
                'value'      => '1',
                'created_at' => '2021-01-28 16:47:28',
                'updated_at' => '2021-01-28 16:47:28',
            ],
            1 => [
                'slug'       => 'footer_remove',
                'value'      => '1',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-01-28 16:47:25',
            ],
            2 => [
                'slug'       => 'grid_row_actions_right',
                'value'      => '0',
                'created_at' => '2021-02-09 14:16:58',
                'updated_at' => '2021-02-17 16:49:13',
            ],
            3 => [
                'slug'       => 'header_blocks',
                'value'      => '1',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-01-28 16:47:25',
            ],
            4 => [
                'slug'       => 'header_padding_fix',
                'value'      => '1',
                'created_at' => '2021-02-22 08:47:24',
                'updated_at' => '2021-02-22 08:47:24',
            ],
            5 => [
                'slug'       => 'sidebar_indentation',
                'value'      => '1',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-01-28 16:47:25',
            ],
            6 => [
                'slug'       => 'sidebar_style',
                'value'      => 'horizontal_menu',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-02-22 08:47:24',
            ],
            7 => [
                'slug'       => 'site_debug',
                'value'      => '1',
                'created_at' => '2021-01-28 16:47:03',
                'updated_at' => '2021-01-28 16:47:17',
            ],
            8 => [
                'slug'       => 'site_lang',
                'value'      => 'zh_CN',
                'created_at' => '2021-01-28 16:47:03',
                'updated_at' => '2021-02-08 08:12:47',
            ],
            9 => [
                'slug'       => 'site_logo',
                'value'      => '',
                'created_at' => '2020-12-20 13:57:11',
                'updated_at' => '2020-12-20 13:57:11',
            ],
            10 => [
                'slug'       => 'site_logo_mini',
                'value'      => '',
                'created_at' => '2020-12-20 13:57:11',
                'updated_at' => '2020-12-20 13:57:11',
            ],
            11 => [
                'slug'       => 'site_logo_text',
                'value'      => '咖啡壶',
                'created_at' => '2020-12-20 13:57:11',
                'updated_at' => '2021-01-28 20:34:45',
            ],
            12 => [
                'slug'       => 'site_title',
                'value'      => '咖啡壶',
                'created_at' => '2020-12-20 13:57:11',
                'updated_at' => '2021-01-28 20:34:45',
            ],
            13 => [
                'slug'       => 'site_url',
                'value'      => '',
                'created_at' => '2021-01-28 16:47:03',
                'updated_at' => '2021-01-28 16:47:03',
            ],
            14 => [
                'slug'       => 'switch_to_select_create',
                'value'      => '0',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-02-08 08:48:00',
            ],
            15 => [
                'slug'       => 'theme_color',
                'value'      => 'blue-light',
                'created_at' => '2021-01-28 16:47:25',
                'updated_at' => '2021-01-28 16:47:25',
            ],
        ]);
    }
}
