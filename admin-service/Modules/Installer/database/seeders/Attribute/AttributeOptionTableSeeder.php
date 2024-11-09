<?php

namespace Modules\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('attribute_options')->delete();

        DB::table('attribute_option_translations')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_options')->insert([
            [
                'id'           => 1,
                'admin_name'   => 'red',
                'sort_order'   => 1,
                'attribute_id' => 23,
            ], [
                'id'           => 2,
                'admin_name'   => 'green',
                'sort_order'   => 2,
                'attribute_id' => 23,
            ], [
                'id'           => 3,
                'admin_name'   => 'yellow',
                'sort_order'   => 3,
                'attribute_id' => 23,
            ], [
                'id'           => 4,
                'admin_name'   => 'black',
                'sort_order'   => 4,
                'attribute_id' => 23,
            ], [
                'id'           => 5,
                'admin_name'   => 'white',
                'sort_order'   => 5,
                'attribute_id' => 23,
            ], [
                'id'           => 6,
                'admin_name'   => 's',
                'sort_order'   => 1,
                'attribute_id' => 24,
            ], [
                'id'           => 7,
                'admin_name'   => 'm',
                'sort_order'   => 2,
                'attribute_id' => 24,
            ], [
                'id'           => 8,
                'admin_name'   => 'l',
                'sort_order'   => 3,
                'attribute_id' => 24,
            ], [
                'id'           => 9,
                'admin_name'   => 'xl',
                'sort_order'   => 4,
                'attribute_id' => 24,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('attribute_option_translations')->insert([
                [
                    'locale'              => $locale,
                    'label'               => 'red',
                    'attribute_option_id' => 1,
                ], [
                    'locale'              => $locale,
                    'label'               => 'green',
                    'attribute_option_id' => 2,
                ], [
                    'locale'              => $locale,
                    'label'               => 'yellow',
                    'attribute_option_id' => 3,
                ], [
                    'locale'              => $locale,
                    'label'               => 'black',
                    'attribute_option_id' => 4,
                ], [
                    'locale'              => $locale,
                    'label'               => 'white',
                    'attribute_option_id' => 5,
                ], [
                    'locale'              => $locale,
                    'label'               => 's',
                    'attribute_option_id' => 6,
                ], [
                    'locale'              => $locale,
                    'label'               => 'm',
                    'attribute_option_id' => 7,
                ], [
                    'locale'              => $locale,
                    'label'               => 'l',
                    'attribute_option_id' => 8,
                ], [
                    'locale'              => $locale,
                    'label'               => 'xl',
                    'attribute_option_id' => 9,
                ],
            ]);
        }
    }
}
