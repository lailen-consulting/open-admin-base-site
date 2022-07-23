<?php

namespace Lailen\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LailenSiteDefaultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ll_menu')->insert([
            'title' => 'Main Menu',
            'slug' => 'main-mneu',
        ]);

        DB::table('ll_menu_items')->insert([
            'title' => 'Home',
            'link' => '/',
            'menu_id' => 1,
            'parent_id' => 0,
            'order' => 0,
        ]);

        DB::table('ll_pages')->insert([
            'title' => 'About Us',
            'slug' => 'about-us',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'user_id' => 1,
            'published_at' => now(),
        ]);

        DB::table('ll_configs')->insert([
            [
                'title' => 'Main Navigation',
                'key' => 'mainNavigation',
                'value' => 1,
                'type' => 'single-menu',
            ],
            [
                'title' => 'Site Title',
                'key' => 'siteTitle',
                'value' => 'Lailen Site',
                'type' => 'text',
            ],
        ]);
    }
}
