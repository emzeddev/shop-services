<?php

namespace Modules\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
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

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('admins')->insert([
            'name'       => 'Example',
            'email'      => 'admin@example.com',
            'password'   => bcrypt('admin123'),
            'api_token'  => Str::random(80),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'status'     => 1,
            'role_id'    => 1,
        ]);
    }
}
