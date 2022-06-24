<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @test
     * 测试登陆页面路由
     */
    public function test_route()
    {
        $test = $this->get('/auth/login');
        $test->assertStatus(200);
    }

    /**
     * @test
     * 测试用户是否可以登陆
     */
    public function user_can_login()
    {
        $user = User::factory()->create();
        $response = $this->post('/auth/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     * 测试用户是否可以注销
     */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'admin')
            ->get('/auth/logout');
        $response->assertLocation('/auth/login');
    }
}
