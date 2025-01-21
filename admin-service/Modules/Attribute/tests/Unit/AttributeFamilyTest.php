<?php

namespace Modules\Attribute\Tests\Unit;

use Tests\TestCase;
use Modules\Attribute\Models\AttributeFamily;
use Illuminate\Support\Str;


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


    public function test_can_create_attribute_families(): void
    {
        $code = Str::random(5);
        $name = Str::random(5);
        $response = $this->postJson(route('api.admin.catalog.families.store') ,  [
            'code' => $code,
            'name' => $name,
            'attribute_groups' => [
                [
                    'code'     => $code,
                    'name'     => $name,
                    'column'   => 1,
                    'position' => 1
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message'
            ]);
    }


    public function test_can_get_attribute_families_for_edit_page(): void
    {
        $response = $this->getJson(route('api.admin.catalog.families.edit' , ['id' => 1]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'attributeFamily',
                'customAttributes',
            ]);
    }

    public function test_can_update_attribute_families(): void
    {
        $attributeFamily = AttributeFamily::factory()->create();
        $code = $attributeFamily->code.'_Updated';
        $name = $attributeFamily->name.'_Updated';
        $response = $this->putJson(route('api.admin.catalog.families.update' , ['id' => $attributeFamily->id]) ,  [
            'code' => $code,
            'name' => $name,
        ]);



        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_can_destroy_attribute_families(): void 
    {
        $attributeFamily = AttributeFamily::factory()->create();

        $response = $this->deleteJson(route('api.admin.catalog.families.delete' , ['id' => $attributeFamily->id]));
        // dd($response);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
