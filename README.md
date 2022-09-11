Base site extension for open-admin

[https://open-admin.org/](https://open-admin.org/)

Requirements: 

- storage folder should have `app/public/gallery`, `app/public/admin`, `app/public/menu-items`

- Setup disks

    'admin' => [
        'driver' => 'local',
        'root' => storage_path('app/public/site'),
        'url' => '/storage',
        'visibility' => 'public',
        'throw' => false,
    ],

- AppServiceProvider

-   try {
        Config::loadAllSettings();
    } catch (\Throwable $th) {
        info('migrate phawt ngai a nih maitei');
    }

After installing CKEditor

- `composer require lailen/site`

- `php artisan vendor:publish --provider=Lailen\\OpenAdmin\\Site\\SiteServiceProvider`

- `php artisan admin:import`

- `php artisan migrate`

Site Admin User siam. 
Permission pek
