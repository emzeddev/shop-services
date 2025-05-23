<?php

namespace Modules\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeFamilyTableSeeder extends Seeder
{
   /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {

        DB::table('attribute_families')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_families')->insert([
            [
                'id'              => 1,
                'code'            => 'default',
                'name'            => 'default',
                'status'          => 0,
                'is_user_defined' => 1,
            ],
        ]);

    }
}
