<?php

namespace Modules\Category\Tests\Unit;

use Tests\TestCase;

use Modules\Category\Models\Category;
use Modules\Category\Database\factories\CategoryFactory;
use Illuminate\Http\UploadedFile;

class CategoryTest extends TestCase
{
    public function test_user_can_view_categories()
    {
        // dd(Category::all());
        Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.admin.catalog.categories.index'));
// dd($response);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }
}
