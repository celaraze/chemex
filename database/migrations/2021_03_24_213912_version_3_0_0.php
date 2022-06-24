<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version300 extends Migration
{
    public function getConnection()
    {
        return $this->config('database.connection') ?: config('database.default');
    }

    public function config($key)
    {
        return config('admin.' . $key);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->integer('department_id')->default(0);
            $table->char('gender')->default('无');
            $table->string('title')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('ad_tag')->default(0);
            $table->string('extended_fields')->nullable();
            $table->softDeletes();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('software_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //软件名称
            $table->string('description')->nullable();  //描述
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('vendor_records', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); //厂商名称
            $table->string('description')->nullable();  //描述
            $table->string('location')->nullable(); //所在国家、地区
            $table->longText('contacts')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('software_records', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //软件名称
            $table->string('description')->nullable();  //软件描述
            $table->integer('category_id'); //软件分类
            $table->string('version');  //版本
            $table->integer('vendor_id');   //厂商
            $table->string('sn')->nullable();   //软件序列号
            $table->double('price')->nullable();    //价格
            $table->date('purchased')->nullable();   //购买日
            $table->date('expired')->nullable(); //有效期
            $table->char('distribution')->default('u');   //分发方式,u未知，o开源，f免费，b商业
            $table->integer('counts')->default(1);  //授权数量
            $table->integer('purchased_channel_id')->nullable();
            $table->string('asset_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('part_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('depreciation_rule_id')->nullable();
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('part_records', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //软件名称
            $table->string('description')->nullable();
            $table->integer('category_id');
            $table->integer('vendor_id');
            $table->string('specification');
            $table->string('sn')->nullable();   //配件序列号
            $table->double('price')->nullable();
            $table->date('purchased')->nullable();
            $table->date('expired')->nullable();
            $table->integer('purchased_channel_id')->nullable();
            $table->integer('depreciation_rule_id')->nullable();
            $table->string('asset_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('depreciation_rule_id')->nullable();
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_records', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //设备名称
            $table->string('description')->nullable();
            $table->integer('category_id');
            $table->integer('vendor_id');
            $table->string('mac')->nullable();
            $table->string('ip')->nullable();
            $table->string('photo')->nullable();
            $table->double('price')->nullable();
            $table->date('purchased')->nullable();
            $table->date('expired')->nullable();
            $table->integer('purchased_channel_id')->nullable();
            $table->integer('depreciation_rule_id')->nullable();
            $table->string('asset_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('software_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('software_id');
            $table->integer('device_id');
            $table->dateTime('lend_time')->nullable();
            $table->string('lend_description')->nullable();
            $table->dateTime('plan_return_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->string('return_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('part_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('part_id');
            $table->integer('device_id');
            $table->dateTime('lend_time')->nullable();
            $table->string('lend_description')->nullable();
            $table->dateTime('plan_return_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->string('return_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->integer('ad_tag')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('staff_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('department_id');
            $table->char('gender')->default('无');
            $table->string('title')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('ad_tag')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id');
            $table->integer('user_id');
            $table->dateTime('lend_time')->nullable();
            $table->string('lend_description')->nullable();
            $table->dateTime('plan_return_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->string('return_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('check_records', function (Blueprint $table) {
            $table->id();
            $table->string('check_item');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('user_id');
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('check_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('check_id');
            $table->integer('item_id');
            $table->integer('status');
            $table->integer('checker');
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('status');
            $table->double('price')->nullable();
            $table->date('purchased')->nullable();
            $table->date('expired')->nullable();
            $table->integer('purchased_channel_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('service_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->integer('device_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('service_issues', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->string('issue');
            $table->integer('status');
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->string('description')->nullable();
            $table->integer('checker')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->integer('item_id');
            $table->string('ng_description');
            $table->string('ok_description')->nullable();
            $table->dateTime('ng_time');
            $table->dateTime('ok_time')->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('purchased_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('chart_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('depreciation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->longText('rules');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('specification');
            $table->integer('category_id');
            $table->integer('vendor_id');
            $table->double('price')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('consumable_id');
            $table->integer('user_id')->default(0);
            $table->double('number');
            $table->date('purchased')->nullable();
            $table->date('expired')->nullable();
            $table->double('change');
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('todo_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->string('priority')->default('normal');
            $table->integer('user_id')->nullable();
            $table->string('tags')->nullable();
            $table->text('done_description')->nullable();
            $table->string('emoji')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('todo_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('todo_id');
            $table->text('origin_json_string');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('custom_columns', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('name');
            $table->string('nick_name');
            $table->string('type');
            $table->integer('must');
            $table->longText('select_options')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('column_sorts', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('field');
            $table->integer('order');
            $table->timestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('gender');
            $table->dropColumn('title');
            $table->dropColumn('mobile');
            $table->dropColumn('email');
            $table->dropColumn('ad_tag');
            $table->dropColumn('extended_fields');
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('software_categories');
        Schema::dropIfExists('vendor_records');
        Schema::dropIfExists('software_records');
        Schema::dropIfExists('part_categories');
        Schema::dropIfExists('part_records');
        Schema::dropIfExists('device_categories');
        Schema::dropIfExists('device_records');
        Schema::dropIfExists('software_tracks');
        Schema::dropIfExists('part_tracks');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('staff_records');
        Schema::dropIfExists('device_tracks');
        Schema::dropIfExists('check_records');
        Schema::dropIfExists('check_tracks');
        Schema::dropIfExists('service_records');
        Schema::dropIfExists('service_tracks');
        Schema::dropIfExists('service_issues');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('purchased_channels');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('chart_records');
        Schema::dropIfExists('depreciation_rules');
        Schema::dropIfExists('consumable_records');
        Schema::dropIfExists('consumable_categories');
        Schema::dropIfExists('consumable_tracks');
        Schema::dropIfExists('todo_records');
        Schema::dropIfExists('todo_histories');
        Schema::dropIfExists('custom_columns');
        Schema::dropIfExists('column_sorts');
        Schema::dropIfExists('jobs');
    }
}
