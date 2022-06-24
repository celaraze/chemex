<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AdminReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chemex:admin-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置Admin账户';

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
     * @return int|void
     */
    public function handle(): int
    {
        $user = User::where('username', 'admin')->first();
        if (empty($user)) {
            $user = new User();
            $user->username = 'admin';
        }
        $user->password = bcrypt('admin');
        $user->name = 'Administrator';
        $user->save();
        $this->info('Admin账户已成功重置为 admin/admin');

        return 0;
    }
}
