<?php

namespace App\Console\Commands;

use App\Models\DeviceTrack;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Overtrue\LaravelPinyin\Facades\Pinyin;
use Pour\Base\Uni;

class RefreshUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chemex:refresh-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户表更新结构后对现有数据做迁移';

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
        $this->info('正在对用户数据做更新迁移');
        $staff_records = DB::table('staff_records')->get();
        if (!empty($staff_records)) {
            $device_tracks = DeviceTrack::all();
            foreach ($staff_records as $staff_record) {
                $user = new User();
                $user->username = Uni::trim(Pinyin::sentence($staff_record->name));
                $exist = User::where('username', $user->username)->first();
                if (!empty($exist)) {
                    $user->username = $user->username.Uni::randomNumberString(4);
                }
                $user->password = bcrypt($user->username);
                $user->name = $staff_record->name;
                $user->department_id = $staff_record->department_id;
                $user->gender = $staff_record->gender;
                $user->title = $staff_record->title;
                $user->mobile = $staff_record->mobile;
                $user->email = $staff_record->email;
                $user->ad_tag = $staff_record->ad_tag;
                $user->save();

                foreach ($device_tracks as $device_track) {
                    if ($staff_record->id == $device_track->user_id) {
                        $device_track->user_id = $user->id;
                        $device_track->save();
                    }
                }
            }
            Schema::rename('staff_records', 'backup_staff_records');
            $this->info('迁移完成！');
        }

        return 0;
    }
}
