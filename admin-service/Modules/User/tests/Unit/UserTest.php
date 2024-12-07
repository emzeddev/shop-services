<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Modules\User\Models\AdminProxy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
// use Illuminate\Foundation\Testing\RefreshDatabase;




class UserTest extends TestCase
{

    protected $admin; // متغیر گلوبال برای کاربر ادمین
    protected $token; // متغیر گلوبال برای توکن

    /**
     * تنظیمات اولیه قبل از اجرای هر تست.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // تولید کاربر و توکن

        $this->admin = AdminProxy::factory()->create();

        $this->token = JWTAuth::fromUser($this->admin);
    }

    /**
     * A basic test example.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $randomMailString = Str::random(6);

        $user = AdminProxy::factory()->create([
            'email' => $randomMailString.'@example.com',
            'password' => Hash::make('password123'), // پسورد هشی شده
            "status" => true
        ]);
        

        // ارسال درخواست لاگین با اطلاعات درست
        $response = $this->postJson(route('api.admin.account.login'), [
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
        $response = $this->postJson(route('api.admin.account.login'), [
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
        $response = $this->postJson(route('api.admin.account.login'), [
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
        $response = $this->postJson(route('api.admin.account.login'), [
            'email' => 'test@example.com',
            "password" => ""
        ]);

        // بررسی اینکه آیا خطای مربوط به پسورد ارسال می‌شود
        $response->assertStatus(422);
        $response->assertJson([
            'error' => trans('user::validation.password_required'),
        ]);
    }

    public function test_email_structure_for_login()
    {
        // ارسال درخواست بدون فیلد پسورد
        $response = $this->postJson(route('api.admin.account.login'), [
            'email' => '123',
            "password" => "password123"
        ]);

        // بررسی اینکه آیا خطای مربوط به پسورد ارسال می‌شود
        $response->assertStatus(422);
        $response->assertJson([
            'error' => trans('user::validation.email_invalid'),
        ]);
    }

    public function test_password_limitation_chracter_for_login()
    {
        // ارسال درخواست بدون فیلد پسورد
        $response = $this->postJson(route('api.admin.account.login'), [
            'email' => 'email@gmail.com',
            "password" => "123"
        ]);

        // بررسی اینکه آیا خطای مربوط به پسورد ارسال می‌شود
        $response->assertStatus(422);
        $response->assertJson([
            'error' => trans('user::validation.password_min' , ["min" => 6]),
        ]);
    }



    public function test_get_data_of_token_returns_authenticated_user()
    {

        // ارسال درخواست GET به متد موردنظر همراه با هدر Authorization
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->token", // ارسال توکن
        ])->postJson(route('api.admin.account.getdata')); // مسیر API خود را جایگزین کنید

        // بررسی موفقیت‌آمیز بودن پاسخ
        $response->assertStatus(200);

        // بررسی اینکه کاربر بازگشتی همان کاربر احراز هویت شده است
        $response->assertJson([
            'user' => [
                'id' => $this->admin->id,
                'name' => $this->admin->name, // فیلدهای موجود در مدل ادمین
                'email' => $this->admin->email,
            ],
        ]);
    }



    public function test_get_data_of_token_returns_unauthorized_for_missing_token()
    {
        // ارسال درخواست GET بدون توکن JWT
        $response = $this->postJson(route('api.admin.account.getdata')); // مسیر API خود را جایگزین کنید

        // بررسی وضعیت 401 (Unauthorized)
        $response->assertStatus(401);
    }

    /**
     * تست زمانی که توکن JWT نامعتبر است.
     */
    public function test_get_data_of_token_returns_unauthorized_for_invalid_token()
    {
        // ارسال درخواست GET با یک توکن جعلی
        $response = $this->withHeaders([
            'Authorization' => 'Bearer sdgfdgyytry', // ارسال توکن نامعتبر
        ])->postJson(route('api.admin.account.getdata')); // مسیر API خود را جایگزین کنید

        // بررسی وضعیت 401 (Unauthorized)
        $response->assertStatus(401);
    }

    public function test_admin_can_logout_successfully()
    {
        // ارسال درخواست logout با توکن معتبر
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson(route('api.admin.account.logout'));

        // بررسی موفقیت پاسخ
        $response->assertStatus(200);
        $response->assertJson([
            "message" => trans('user::validation.logout-success'),
        ]);

        // بررسی اینکه توکن دیگر معتبر نیست
        $this->assertFalse(auth()->guard('admin')->check());
    }

    public function test_admin_can_refresh_token_successfully()
    {
        // ارسال درخواست refresh با توکن معتبر
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson(route('api.admin.account.refresh'));

        // بررسی موفقیت پاسخ
        $response->assertStatus(200);

        // بررسی ساختار داده‌های برگشتی
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

        // بررسی نوع توکن برگشتی
        $this->assertEquals('bearer', $response->json('token_type'));

        // بررسی مقدار expires_in (باید برابر TTL تنظیم شده باشد)
        $this->assertEquals(
            auth()->guard('admin')->factory()->getTTL() * 60,
            $response->json('expires_in')
        );
    }
}
