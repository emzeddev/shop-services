<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_family_id')->unsigned();
            $table->string('name');
            $table->integer('column')->default(1);
            $table->integer('position');
            $table->boolean('is_user_defined')->default(1);

            $table->unique(['attribute_family_id', 'name']);
            $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');
        });

        Schema::create('attribute_group_mappings', function (Blueprint $table) {
            $table->integer('attribute_id')->unsigned();
            $table->integer('attribute_group_id')->unsigned();
            $table->integer('position')->nullable();

            $table->primary(['attribute_id', 'attribute_group_id']);
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onDelete('cascade');
        });


        $families = DB::table('attribute_families')->get();

        foreach ($families as $family) {
            DB::table('attribute_groups')
                ->insert([
                    'name'                => 'Settings',
                    'column'              => 2,
                    'is_user_defined'     => 0,
                    'position'            => 3,
                    'attribute_family_id' => $family->id,
                ]);

            $generalGroup = DB::table('attribute_groups')
                ->where('name', 'General')
                ->where('attribute_family_id', $family->id)
                ->first();

            $settingGroup = DB::table('attribute_groups')
                ->where('name', 'Settings')
                ->where('attribute_family_id', $family->id)
                ->first();

            DB::table('attribute_group_mappings')
                ->where('attribute_group_id', $generalGroup->id)
                ->whereIn('attribute_id', [5, 6, 7, 8, 26])
                ->update([
                    'attribute_group_id' => $settingGroup->id,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_group_mappings');

        Schema::dropIfExists('attribute_groups');
    }
};
