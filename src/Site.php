<?php

namespace Lailen\OpenAdmin\Site;

// use Lailen\Seeders\LailenSiteDefaultsSeeder;
use Illuminate\Support\Facades\Artisan;
use OpenAdmin\Admin\Auth\Database\Menu;
use OpenAdmin\Admin\Extension;

class Site extends Extension
{
    public $name = 'site';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public static function import()
    {
        $lastOrder = Menu::max('order');

        $menus = [
            [
                'title' => 'Menu',
                'uri'  => 'menus',
                'icon'  => 'icon-bars',
            ],
            [
                'title' => 'Pages',
                'uri'  => 'pages',
                'icon'  => 'icon-file',
            ],
            [
                'title' => 'Gallery',
                'uri'  => 'albums',
                'icon'  => 'icon-images',
            ],
            [
                'title' => 'Posts',
                'uri'  => 'posts',
                'icon'  => 'icon-file-alt',
            ],
            [
                'title' => 'Video',
                'uri'  => 'videos',
                'icon'  => 'icon-video',
            ],
            [
                'title' => 'Tags',
                'uri'  => 'post-tags',
                'icon'  => 'icon-tags',
            ],
            [
                'title' => 'Categories',
                'uri'  => 'post-categories',
                'icon'  => 'icon-file',
            ],
            [
                'title' => 'Settings',
                'uri'  => 'settings',
                'icon'  => 'icon-cog',
            ],
        ];

        foreach ($menus as $menu) {
            $menu['parent_id'] = 0;
            $menu['order'] = $lastOrder++;
            $menu['uri'] = 'll_' . $menu['uri'];

            Menu::create($menu);
        }

        $adminMenuId = Menu::where('title', 'Admin')->first()->id;
        $adminMenuOrder = Menu::where('parent_id', $adminMenuId)->count() + 1;
        Menu::create([
            'title' => 'Settings Configurations',
            'uri' => 'll_configs',
            'icon' => 'icon-cogs',
            'parent_id' => $adminMenuId,
            'order' => $adminMenuOrder,
        ]);

        Artisan::call('vendor:publish', ['--tag' => 'open-admin-ckeditor']);
        Artisan::call('vendor:publish', ['--provider' => SiteServiceProvider::class]);

        Artisan::call('migrate');

        /**
         * Class load dik thei lo. migration ah ka dah
         */
        // Artisan::call('db:seed', ['--class' => LailenSiteDefaultsSeeder::class]);

        /**
         * Mamawh hun atan. open-admin-ext helpers ami ka copy
         */
        // parent::createPermission('Lailen Site Management', 'ext.site', 'site/*');
    }
}
