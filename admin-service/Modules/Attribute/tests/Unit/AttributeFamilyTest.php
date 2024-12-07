<?php

namespace Modules\Attribute\Tests\Unit;

use Tests\TestCase;

class AttributeFamilyTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_can_list_attribute_families(): void
    {
        $response = $this->getJson(route('api.admin.catalog.families.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }
}
