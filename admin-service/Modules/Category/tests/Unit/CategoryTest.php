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
        // dd(Category::all());
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

        dd($response);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'status',
            'data',
        ]);

        // $this->assertDatabaseHas('category_translations', [
        //     'slug'        => $data['slug'],
        //     'name'        => $data['name'],
        //     'description' => $data['description'],
        // ]);
    }

}
