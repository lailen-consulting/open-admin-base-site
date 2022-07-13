Base site extension for open-admin

[https://open-admin.org/](https://open-admin.org/)

Requirements: 

- Install [CKEditor open admin extension](https://open-admin.org/docs/en/extension-ckeditor) for page editor

After installing CKEditor

- `composer require lailen/site`

- `php artisan vendor:publish --provider=Lailen\\OpenAdmin\\Site\\SiteServiceProvider`

- `php artisan admin:import`

- `php artisan migrate`
