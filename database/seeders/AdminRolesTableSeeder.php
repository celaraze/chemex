<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_roles')->delete();

        DB::table('admin_roles')->insert([
            0 => [
                'id'         => 1,
                'name'       => '超级管理员',
                'slug'       => 'administrator',
                'created_at' => '2020-09-18 09:45:49',
                'updated_at' => '2020-11-18 17:45:16',
            ],
            1 => [
                'id'         => 2,
                'name'       => '观察者',
                'slug'       => 'observer',
                'created_at' => '2020-11-19 09:25:18',
                'updated_at' => '2020-11-19 14:09:37',
            ],
        ]);
    }
}
