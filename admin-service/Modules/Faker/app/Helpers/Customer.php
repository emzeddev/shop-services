<?php

namespace Modules\Faker\Helpers;

use Modules\Customer\Models\Customer as CustomerModel;

class Customer
{
    /**
     * Create a customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function create(int $count)
    {
        return $this->factory()
            ->count($count)
            ->create();
    }

    /**
     * Get a customer factory. This will provide a factory instance for
     * attaching additional features and taking advantage of the factory.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    public function factory()
    {
        return CustomerModel::factory();
    }
}
