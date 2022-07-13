<?php

namespace Lailen\OpenAdmin\Site;

use OpenAdmin\Admin\Extension;

class Site extends Extension
{
    public $name = 'site';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Site',
        'path'  => 'site',
        'icon'  => 'icon-cogs',
        'children' => [
            [
                'title' => 'Menu',
                'path'  => 'menus',
                'icon'  => 'icon-bars',
            ],
            [
                'title' => 'Pages',
                'path'  => 'pages',
                'icon'  => 'icon-file',
            ],
            [
                'title' => 'Gallery',
                'path'  => 'albums',
                'icon'  => 'icon-images',
            ],
            [
                'title' => 'Posts',
                'path'  => 'posts',
                'icon'  => 'icon-file-alt',
            ],
            [
                'title' => 'Tags',
                'path'  => 'post-tags',
                'icon'  => 'icon-tags',
            ],
            [
                'title' => 'Categories',
                'path'  => 'post-categories',
                'icon'  => 'icon-file',
            ],
        ]
    ];
}
