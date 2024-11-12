<?php

namespace Modules\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('admins')->delete();

        DB::table('roles')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('roles')->insert([
            'id'              => 1,
            'name'            => 'Administrator',
            'description'     => 'This role users will have all the access',
            'permission_type' => 'all',
        ]);
    }
}
