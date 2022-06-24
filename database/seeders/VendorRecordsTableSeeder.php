<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendorRecordsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('vendor_records')->delete();

        \DB::table('vendor_records')->insert(array(
            0 =>
                array(
                    'id' => '1',
                    'name' => '微软 Microsoft',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:57:11',
                    'updated_at' => '2021-01-19 11:58:03',
                ),
            1 =>
                array(
                    'id' => '2',
                    'name' => '英特尔 Intel',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:57:31',
                    'updated_at' => '2021-01-19 11:58:10',
                ),
            2 =>
                array(
                    'id' => '3',
                    'name' => 'AMD',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:57:36',
                    'updated_at' => '2021-01-19 11:57:36',
                ),
            3 =>
                array(
                    'id' => '4',
                    'name' => '苹果 Apple',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:57:41',
                    'updated_at' => '2021-01-19 11:58:16',
                ),
            4 =>
                array(
                    'id' => '5',
                    'name' => '英伟达 Nvidia',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:57:50',
                    'updated_at' => '2021-01-19 11:58:23',
                ),
            5 =>
                array(
                    'id' => '6',
                    'name' => '微星 MSI',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:58:32',
                    'updated_at' => '2021-01-19 11:58:32',
                ),
            6 =>
                array(
                    'id' => '7',
                    'name' => '金士顿 Kingston',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:58:40',
                    'updated_at' => '2021-01-19 11:58:40',
                ),
            7 =>
                array(
                    'id' => '8',
                    'name' => '西部数据 WD',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:58:47',
                    'updated_at' => '2021-01-19 11:58:47',
                ),
            8 =>
                array(
                    'id' => '9',
                    'name' => '希捷 Seagate',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:59:18',
                    'updated_at' => '2021-01-19 11:59:18',
                ),
            9 =>
                array(
                    'id' => '10',
                    'name' => '华硕 ASUS',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:59:40',
                    'updated_at' => '2021-01-19 11:59:40',
                ),
            10 =>
                array(
                    'id' => '11',
                    'name' => '联想 Lenovo',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:59:48',
                    'updated_at' => '2021-01-19 11:59:48',
                ),
            11 =>
                array(
                    'id' => '12',
                    'name' => '惠普 HP/HPE',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 11:59:57',
                    'updated_at' => '2021-01-19 11:59:57',
                ),
            12 =>
                array(
                    'id' => '13',
                    'name' => '华为 Huawei',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 12:00:18',
                    'updated_at' => '2021-01-19 12:00:18',
                ),
            13 =>
                array(
                    'id' => '14',
                    'name' => '小米 MI',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 12:00:27',
                    'updated_at' => '2021-01-19 12:00:27',
                ),
            14 =>
                array(
                    'id' => '15',
                    'name' => '荣耀 Honor',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 17:00:41',
                    'updated_at' => '2021-01-19 17:00:41',
                ),
            15 =>
                array(
                    'id' => '16',
                    'name' => '七彩虹 Colorful',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 17:01:00',
                    'updated_at' => '2021-01-19 17:01:00',
                ),
            16 =>
                array(
                    'id' => '17',
                    'name' => '影驰 Galaxy',
                    'description' => NULL,
                    'location' => NULL,
                    'contacts' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2021-01-19 17:01:31',
                    'updated_at' => '2021-01-19 17:01:31',
                ),
        ));


    }
}
