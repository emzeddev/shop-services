<?php

namespace Modules\Attribute\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
    use RefreshDatabase; // در صورتی که نیاز به ریست کردن دیتابیس دارید

    /**
     * Test to check if attribute data is returned.
     *
     * @return void
    */
    public function test_should_return_attribute_data()
    {
        // ارسال درخواست به URL API
        $response = $this->getJson('api/catalog/attributes');

        // بررسی وضعیت پاسخ
        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertIsArray($responseData['original']);
    }
}
