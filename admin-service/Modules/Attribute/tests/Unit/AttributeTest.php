<?php

namespace Modules\Attribute\Tests\Unit;

use Tests\TestCase;
use Modules\Attribute\Models\Attribute;

class AttributeTest extends TestCase
{
    
    public function test_can_list_attribute(): void
    {
        $response = $this->getJson(route('api.admin.catalog.attributes.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_should_returns_attributes_options(): void
    {
        $attribute = Attribute::factory()->create();
        $response = $this->getJson(route('api.admin.catalog.attributes.options' , ["id" => $attribute->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);
    }

    public function test_should_store_newly_created_attribute(): void
    {
        $response = $this->postJson(route('api.admin.catalog.attributes.store') , [
            'admin_name'    => fake()->name(),
            'code'          => fake()->numerify('code########'),
            'type'          => 'text',
            'default_value' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message'
            ]);
    }


    public function test_should_ail_the_validation_with_errors_when_certain_inputs_are_not_provided_when_store_in_attribute(): void
    {
        $this->postJson(route('api.admin.catalog.attributes.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('admin_name')
        ->assertJsonValidationErrorFor('type')
        ->assertUnprocessable();
    }

}
