<?php

namespace Modules\Category\Tests\Unit;

use Tests\TestCase;

use Modules\Category\Models\Category;
use Modules\Category\Database\factories\CategoryFactory;
use Illuminate\Http\UploadedFile;
use Modules\Attribute\Models\Attribute;

class CategoryTest extends TestCase
{
    public function test_user_can_view_categories()
    {
        // Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.admin.catalog.categories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }




    public function test_fails_image_validation_error_when_provided_tempered_logo_and_banner()
    {
        // Arrange
        $attributes = Attribute::where('is_filterable', 1)->pluck('id')->toArray();
        

        // Act
        $response = $this->postJson(route('api.admin.catalog.categories.store'), [
            'slug'        => fake()->slug(),
            'name'        => fake()->name(),
            'position'    => rand(1, 5),
            'description' => substr(fake()->paragraph(), 0, 50),
            'attributes'  => $attributes,
            'logo_path'   => [
                UploadedFile::fake()->image('logo.php'),
            ],
            'banner_path' => [
                UploadedFile::fake()->image('banner.js'),
            ],
        ]);


        $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'error',
        ]);
    }




    public function testـadmin_can_create_category()
    {
        // آماده‌سازی داده‌ها
        $attributes = Attribute::factory()->count(3)->create(['is_filterable' => 1])->pluck('id')->toArray();

        
        // داده‌های ارسال‌شده در درخواست
        $data = [
            'slug'        => fake()->slug(),
            'name'        => fake()->name(),
            'position'    => rand(1, 5),
            'description' => substr(fake()->paragraph(), 0, 50),
            'attributes'  => $attributes,
            'logo_path'   => [UploadedFile::fake()->image('logo.png')],
            'banner_path' => [UploadedFile::fake()->image('banner.png')],
        ];

        // ارسال درخواست و بررسی ریدایرکت
        $response = $this->postJson(route('api.admin.catalog.categories.store'), $data);

        // dd($response);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'category',
        ]);

    }


    public function test_admin_cannot_create_category_without_required_fields()
    {
        $response = $this->postJson(route('api.admin.catalog.categories.store'));

        $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'error',
        ]);
    }



    public function test_admin_can_mass_update_categories()
    {
        // آماده‌سازی داده‌ها: ایجاد ۵ دسته‌بندی تستی
        $categories = Category::factory()->count(5)->create();


        // ارسال درخواست برای بروزرسانی گروهی
        $response = $this->postJson(route('api.admin.catalog.categories.mass_update'), [
            'indices' => $categories->pluck('id')->toArray(),
            'value'   => 1,
        ]);

        // بررسی پاسخ موفق
        $response->assertOk()
         ->assertSeeText(trans('category::messages.update-success'));
            

        // بررسی دیتابیس که مقدار status برای همه‌ی دسته‌ها برابر ۱ شده باشد
        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', [
                'id'     => $category->id,
                'status' => 1,
            ]);
        }
    }


    public function test_admin_can_mass_delete_categories()
    {
        // آماده‌سازی داده‌ها: ایجاد ۵ دسته‌بندی تستی
        $categories = Category::factory()->count(5)->create();


        // ارسال درخواست برای حذف گروهی دسته‌بندی‌ها
        $response = $this->postJson(route('api.admin.catalog.categories.mass_delete'), [
            'indices' => $categories->pluck('id')->toArray(),
        ]);

        // بررسی پاسخ موفق
        $response->assertOk()
            ->assertSeeText(trans('category::messages.delete-success'));

        // بررسی دیتابیس که دسته‌ها حذف شده باشند
        foreach ($categories as $category) {
            $this->assertDatabaseMissing('categories', [
                'id' => $category->id,
            ]);
        }
    }


    public function test_admin_can_delete_a_category()
    {
        // آماده‌سازی داده‌ها: ایجاد یک دسته‌بندی تستی
        $category = Category::factory()->create();

        // ارسال درخواست حذف
        $response = $this->deleteJson(route('api.admin.catalog.categories.delete', $category->id));

        // بررسی موفق بودن پاسخ
        $response->assertOk()
            ->assertSeeText(trans('category::messages.delete-success'));

        // بررسی اینکه دسته‌بندی از دیتابیس حذف شده باشد
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }


    public function test_admin_can_update_a_category()
    {
        // آماده‌سازی داده‌ها: ایجاد یک دسته‌بندی تستی
        $category = Category::factory()->create();
        
        // دریافت ویژگی‌های قابل فیلتر شدن
        $attributes = Attribute::where('is_filterable', 1)->pluck('id')->toArray();

        // داده‌های جدید برای به‌روزرسانی
        $data = [
            'name'        => fake()->name(),
            'description' => substr(fake()->paragraph(), 0, 50),
            'slug'        => fake()->slug(),
        ];



        // ارسال درخواست PUT برای به‌روزرسانی دسته‌بندی
        $response = $this->putJson(route('api.admin.catalog.categories.update', $category->id), [
            'fa'          => $data,
            'locale'      => config('app.locale'),
            'attributes'  => $attributes,
            'position'    => rand(1, 5),
            'logo_path'   => [
                UploadedFile::fake()->image('logo.png'),
            ],
            'banner_path' => [
                UploadedFile::fake()->image('banner.png'),
            ],
        ]);

        // dd($response);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'category',
        ]);

    }






}
