<?php

namespace App\Providers;

use App\Models\ApprovalRecord;
use App\Models\CheckRecord;
use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\ServiceRecord;
use App\Models\SoftwareRecord;
use App\Models\TodoRecord;
use App\Models\User;
use App\Observers\ApprovalRecordObserver;
use App\Observers\CheckRecordObserver;
use App\Observers\CustomColumnObserver;
use App\Observers\DeviceRecordObserver;
use App\Observers\PartRecordObserver;
use App\Observers\ServiceRecordObserver;
use App\Observers\SoftwareRecordObserver;
use App\Observers\TodoRecordObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // 盘点任务的观察者
        CheckRecord::observe(CheckRecordObserver::class);
        // 设备的观察者
        DeviceRecord::observe(DeviceRecordObserver::class);
        // 配件的观察者
        PartRecord::observe(PartRecordObserver::class);
        // 软件的观察者
        SoftwareRecord::observe(SoftwareRecordObserver::class);
        // 服务的观察者
        ServiceRecord::observe(ServiceRecordObserver::class);
        // 用户的观察者
        User::observe(UserObserver::class);
        // 待办的观察者
        TodoRecord::observe(TodoRecordObserver::class);
    }
}
