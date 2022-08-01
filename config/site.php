<?php

return [
    'image_prefix' => env('LLSITE_IMAGE_PREFIX', 'site'),

    /**
     * Post thumbnail sizes
     */
    'posts' => [
        'thumbnails' => [
            'small' => env('LLSITE_POSTS_THUMBNAILS_SMALL', 300),
            'medium' => env('LLSITE_POSTS_THUMBNAILS_MEDIUM', 800),
            'large' => env('LLSITE_POSTS_THUMBNAILS_LARGE', 1024),
        ],
    ],
    'pages' => [
        'thumbnails' => [
            'small' => env('LLSITE_PAGES_THUMBNAILS_SMALL', 300),
            'medium' => env('LLSITE_PAGES_THUMBNAILS_MEDIUM', 800),
            'large' => env('LLSITE_PAGES_THUMBNAILS_LARGE', 1024),
        ],
    ],
    'events' => [
        'thumbnails' => [
            'small' => env('LLSITE_EVENTS_THUMBNAILS_SMALL', 300),
            'medium' => env('LLSITE_EVENTS_THUMBNAILS_MEDIUM', 800),
            'large' => env('LLSITE_EVENTS_THUMBNAILS_LARGE', 1024),
        ],
    ],
];
