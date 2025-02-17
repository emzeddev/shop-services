<?php

namespace Modules\Product\Tests\Unit;

use Tests\TestCase;
use Modules\Product\Models\Product;
use Modules\Faker\Helpers\Product as ProductFaker;

class ProductTest extends TestCase
{

    public function test_it_returns_success_message_when_syncing_products()
    {
        // Send a GET request to the sync endpoint
        $response = $this->getJson(route('api.admin.catalog.products.sync'));

        // Assert the status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the correct message
        $response->assertJson([
            'message' => 'Syncing products data...'
        ]);
    }


    public function test_it_returns_product_index_with_status_200()
    {
        // Arrange: ایجاد محصولات نمونه
        $product = (new ProductFaker)->getSimpleProductFactory()->create();

        // Act: ارسال درخواست GET به متد index
        $response = $this->getJson(route('api.admin.catalog.products.index'));

        // Assert: بررسی ساختار پاسخ و صحت داده‌ها
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'id', 'name', 'sku', 'price'
                        ]
                    ]
                 ])
                 ->assertJson(['status' => 200]);
    }
}
