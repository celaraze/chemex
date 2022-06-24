<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminExtensionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('admin_extensions')->delete();

        \DB::table('admin_extensions')->insert(array(
            0 =>
                array(
                    'id' => '1',
                    'name' => 'celaraze.dcat-extension-plus',
                    'version' => '1.0.8',
                    'is_enabled' => '1',
                    'options' => NULL,
                    'created_at' => '2021-01-28 16:39:46',
                    'updated_at' => '2021-02-24 08:33:19',
                ),
        ));


    }
}
