<?php

namespace Modules\Installer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Installer\Database\Seeders\Attribute\DatabaseSeeder as AttributeSeeder;
use Modules\Installer\Database\Seeders\User\DatabaseSeeder as UserSeeder;

class InstallerDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = []): void
    {
        $this->call(AttributeSeeder::class, false, ['parameters' => $parameters]);
        $this->call(UserSeeder::class, false, ['parameters' => $parameters]);
    }
}
