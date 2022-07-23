<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ll_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key');
            $table->longText('value')->nullable();
            $table->string('type'); // textarea text number etc. input type atan
            $table->longText('options')->nullable();
            $table->timestamps();
        });

        /**
         * Seeder namespace a dik thei lo
         */
        DB::table('ll_menu_items')->insert([
            'title' => 'Home',
            'link' => '/',
            'menu_id' => 1,
            'parent_id' => 0,
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ll_menus')->insert([
            'name' => 'Main Menu',
            'slug' => 'main-menu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ll_pages')->insert([
            'title' => 'About Us',
            'slug' => 'about-us',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'user_id' => 1,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ll_configs')->insert([
            [
                'title' => 'Main Navigation',
                'key' => 'mainNavigation',
                'value' => 1,
                'type' => 'single-menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Site Title',
                'key' => 'siteTitle',
                'value' => 'Lailen Site',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ll_configs');
    }
};
