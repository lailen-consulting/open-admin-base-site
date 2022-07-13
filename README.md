Base site extension for open-admin

[https://open-admin.org/](https://open-admin.org/)



- add to composer.json

    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/lailen-consulting/open-admin-base-site"
        }
    ],

- `composer require lailen/site`

- `php artisan vendor:publish --provider=Lailen\\OpenAdmin\\Site\\SiteServiceProvider`

- `php artisan admin:import`

- `php artisan migrate`
