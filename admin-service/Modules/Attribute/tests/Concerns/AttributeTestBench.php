<?php

namespace Modules\Attribute\Tests\Concerns;

use Modules\User\Contracts\Admin as AdminContract;
use Modules\User\Models\Admin as AdminModel;

trait AttributeTestBench
{
    /**
     * Login as customer.
     */
    public function loginAsAdmin(?AdminContract $admin = null): AdminContract
    {
        $admin = $admin ?? AdminModel::factory()->create();

        $this->actingAs($admin, 'admin');

        return $admin;
    }
}
