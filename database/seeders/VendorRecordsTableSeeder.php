<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class VendorRecordsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendor_records')->delete();

        DB::table('vendor_records')->insert([
            0 => [
                'id'          => 1,
                'name'        => '微软 Microsoft',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:57:11',
                'updated_at'  => '2021-01-19 11:58:03',
                'contacts'    => null,
            ],
            1 => [
                'id'          => 2,
                'name'        => '英特尔 Intel',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:57:31',
                'updated_at'  => '2021-01-19 11:58:10',
                'contacts'    => null,
            ],
            2 => [
                'id'          => 3,
                'name'        => 'AMD',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:57:36',
                'updated_at'  => '2021-01-19 11:57:36',
                'contacts'    => null,
            ],
            3 => [
                'id'          => 4,
                'name'        => '苹果 Apple',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:57:41',
                'updated_at'  => '2021-01-19 11:58:16',
                'contacts'    => null,
            ],
            4 => [
                'id'          => 5,
                'name'        => '英伟达 Nvidia',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:57:50',
                'updated_at'  => '2021-01-19 11:58:23',
                'contacts'    => null,
            ],
            5 => [
                'id'          => 6,
                'name'        => '微星 MSI',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:58:32',
                'updated_at'  => '2021-01-19 11:58:32',
                'contacts'    => null,
            ],
            6 => [
                'id'          => 7,
                'name'        => '金士顿 Kingston',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:58:40',
                'updated_at'  => '2021-01-19 11:58:40',
                'contacts'    => null,
            ],
            7 => [
                'id'          => 8,
                'name'        => '西部数据 WD',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:58:47',
                'updated_at'  => '2021-01-19 11:58:47',
                'contacts'    => null,
            ],
            8 => [
                'id'          => 9,
                'name'        => '希捷 Seagate',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:59:18',
                'updated_at'  => '2021-01-19 11:59:18',
                'contacts'    => null,
            ],
            9 => [
                'id'          => 10,
                'name'        => '华硕 ASUS',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:59:40',
                'updated_at'  => '2021-01-19 11:59:40',
                'contacts'    => null,
            ],
            10 => [
                'id'          => 11,
                'name'        => '联想 Lenovo',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:59:48',
                'updated_at'  => '2021-01-19 11:59:48',
                'contacts'    => null,
            ],
            11 => [
                'id'          => 12,
                'name'        => '惠普 HP/HPE',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 11:59:57',
                'updated_at'  => '2021-01-19 11:59:57',
                'contacts'    => null,
            ],
            12 => [
                'id'          => 13,
                'name'        => '华为 Huawei',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 12:00:18',
                'updated_at'  => '2021-01-19 12:00:18',
                'contacts'    => null,
            ],
            13 => [
                'id'          => 14,
                'name'        => '小米 MI',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 12:00:27',
                'updated_at'  => '2021-01-19 12:00:27',
                'contacts'    => null,
            ],
            14 => [
                'id'          => 15,
                'name'        => '荣耀 Honor',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 17:00:41',
                'updated_at'  => '2021-01-19 17:00:41',
                'contacts'    => null,
            ],
            15 => [
                'id'          => 16,
                'name'        => '七彩虹 Colorful',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 17:01:00',
                'updated_at'  => '2021-01-19 17:01:00',
                'contacts'    => null,
            ],
            16 => [
                'id'          => 17,
                'name'        => '影驰 Galaxy',
                'description' => null,
                'location'    => null,
                'deleted_at'  => null,
                'created_at'  => '2021-01-19 17:01:31',
                'updated_at'  => '2021-01-19 17:01:31',
                'contacts'    => null,
            ],
        ]);
    }
}
