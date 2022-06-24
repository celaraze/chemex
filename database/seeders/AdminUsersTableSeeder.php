<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('admin_users')->delete();

        \DB::table('admin_users')->insert(array(
            0 =>
                array(
                    'id' => '1',
                    'username' => 'admin',
                    'password' => '$2y$10$YN9id6cFwvdfkSTyxfc.qu4ZTJ3yk0.poPLZRHb8xfVR05HAqcYOa',
                    'name' => 'Administrator',
                    'avatar' => NULL,
                    'remember_token' => NULL,
                    'department_id' => '1',
                    'status' => '1',
                    'gender' => '男',
                    'title' => NULL,
                    'mobile' => NULL,
                    'email' => NULL,
                    'ad_tag' => '0',
                    'extended_fields' => NULL,
                    'deleted_at' => NULL,
                    'created_at' => '2020-11-30 09:58:49',
                    'updated_at' => '2021-04-01 18:01:47',
                ),
        ));


    }
}
