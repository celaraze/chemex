<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminRoleUsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_role_users')->delete();

        DB::table('admin_role_users')->insert([
            0 => [
                'role_id'    => 1,
                'user_id'    => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}
