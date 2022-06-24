<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chemex:db-fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '为 Chemex 导入预填充数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('db:seed', ['--class' => 'DeviceCategoriesTableSeeder']);
        $this->call('db:seed', ['--class' => 'VendorRecordsTableSeeder']);
        $this->call('db:seed', ['--class' => 'PartCategoriesTableSeeder']);
        $this->call('db:seed', ['--class' => 'SoftwareCategoriesTableSeeder']);

        return 0;
    }
}
