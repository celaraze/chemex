<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminRoleMenuTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_role_menu')->delete();
    }
}
