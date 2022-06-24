<?php

namespace Tests\Feature;

use App\Models\DeviceRecord;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class DeviceCategoryTest extends TestCase
{
    /**
     * @test
     * 测试用户是否可以访问设备清单
     */
    public function test_device_records_index()
    {
        $user = $this->factoryAdminUser();
        $response = $this->actingAs($user, 'admin')
            ->get(admin_route('device.records.index'));
        $response->assertStatus(200);
    }

    /**
     * 为测试用例创建一个管理员权限的用户.
     *
     * @return Collection|Model|mixed
     */
    public function factoryAdminUser()
    {
        return User::factory()
            ->has(RoleUser::factory()->count(1), 'roleUser')
            ->create();
    }

    /**
     * @test
     * 测试用户是否可以访问设备详情
     */
    public function test_device_records_show()
    {
        $user = $this->factoryAdminUser();
        $device_record = DeviceRecord::factory()->create();
        $response = $this->actingAs($user, 'admin')
            ->get(admin_route('device.records.show', [$device_record->id]));
        $response->assertStatus(200);
    }

    /**
     * @test
     * 测试用户是否可以访问设备编辑
     */
    public function test_device_records_edit()
    {
        $user = $this->factoryAdminUser();
        $device_record = DeviceRecord::factory()->create();
        $response = $this->actingAs($user, 'admin')
            ->get(admin_route('device.records.edit', [$device_record->id]));
        $response->assertStatus(200);
    }
}
