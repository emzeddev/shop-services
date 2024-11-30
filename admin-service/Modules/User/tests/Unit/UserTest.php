<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Modules\User\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Illuminate\Foundation\Testing\RefreshDatabase;




class UserTest extends TestCase
{

    /**
     * A basic test example.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $randomMailString = Str::random(6);

        $user = Admin::factory()->create([
            'email' => $randomMailString.'@example.com',
            'password' => Hash::make('password123'), // پسورد هشی شده
            "status" => true
        ]);
        

        // ارسال درخواست لاگین با اطلاعات درست
        $response = $this->postJson('api/admin/account/login', [
            'email' => $randomMailString.'@example.com',
            'password' => 'password123',
            "remember" => true
        ]);



        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
        ]);

        // بررسی اینکه توکن JWT در پاسخ وجود دارد
        $this->assertArrayHasKey('access_token', $response->json());
    }


    public function test_user_cannot_login_with_invalid_credentials()
    {
        // ارسال درخواست لاگین با اطلاعات اشتباه
        $response = $this->postJson('api/admin/account/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        // بررسی اینکه آیا پاسخ با وضعیت خطا (401) است
        $response->assertStatus(401);
        $response->assertJson([
            'message' => trans('user::validation.admin-notfound'),
        ]);
    }


    public function test_email_is_required_for_login()
    {
        // ارسال درخواست بدون فیلد ایمیل
        $response = $this->postJson('api/admin/account/login', [
            "email" => "",
            'password' => 'password123',
        ]);

        // بررسی اینکه آیا خطای مربوط به ایمیل ارسال می‌شود
        $response->assertStatus(422);
        $response->assertJson([
            'error' => trans('user::validation.email_required'),
        ]);
    }


    public function test_password_is_required_for_login()
    {
        // ارسال درخواست بدون فیلد پسورد
        $response = $this->postJson('api/admin/account/login', [
            'email' => 'test@example.com',
            "password" => ""
        ]);

        // بررسی اینکه آیا خطای مربوط به پسورد ارسال می‌شود
        $response->assertStatus(422);
        $response->assertJson([
            'error' => trans('user::validation.password_required'),
        ]);
    }
}
