<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Models\Category;
use Modules\Category\Database\factories\CategoryFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    // use RefreshDatabase;

    public function test_user_can_view_categories()
    {
        // dd(Category::all());
        Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.admin.catalog.categories.index'));
dd($response);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    // public function test_user_can_create_category()
    // {
    //     $data = [
    //         'name' => 'Test Category',
    //         'image' => UploadedFile::fake()->image('category.jpg')
    //     ];

    //     $response = $this->post('/categories', $data);

    //     $response->assertStatus(201);
    //     $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    // }

    // public function test_name_is_required_to_create_category()
    // {
    //     $response = $this->post('/categories', ['name' => '']);

    //     $response->assertSessionHasErrors('name');
    // }

    // public function test_image_is_required_to_create_category()
    // {
    //     $response = $this->post('/categories', ['name' => 'Valid Name']);

    //     $response->assertSessionHasErrors('image');
    // }

    // public function test_user_can_update_category()
    // {
    //     $category = Category::factory()->create();

    //     $data = ['name' => 'Updated Name'];

    //     $response = $this->put("/categories/{$category->id}", $data);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('categories', ['name' => 'Updated Name']);
    // }

    // public function test_user_can_delete_category()
    // {
    //     $category = Category::factory()->create();

    //     $response = $this->delete("/categories/{$category->id}");

    //     $response->assertStatus(200);
    //     $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    // }
}
