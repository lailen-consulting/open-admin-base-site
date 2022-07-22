<?php

namespace Lailen\OpenAdmin\Site;

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

            Menu::create($menu);
        }

        $adminMenuId = Menu::where('title', 'Admin')->first()->id;
        $adminMenuOrder = Menu::where('parent_id', $adminMenuId)->count() + 1;
        Menu::create([
            'title' => 'Settings Configurations',
            'uri' => 'configs',
            'icon' => 'icon-cogs',
            'parent_id' => $adminMenuId,
            'order' => $adminMenuOrder,
        ]);

        Artisan::call('vendor:publish', ['--tag' => 'open-admin-ckeditor']);

        Artisan::call('migrate');

        /**
         * Mamawh hun atan. open-admin-ext helpers ami ka copy
         */
        // parent::createPermission('Lailen Site Management', 'ext.site', 'site/*');
    }
}
