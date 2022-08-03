<?php

use Illuminate\Support\Facades\Route;
use Lailen\OpenAdmin\Site\Http\Controllers\AlbumsController;
use Lailen\OpenAdmin\Site\Http\Controllers\ConfigsController;
use Lailen\OpenAdmin\Site\Http\Controllers\MenuItemsController;
use Lailen\OpenAdmin\Site\Http\Controllers\MenusController;
use Lailen\OpenAdmin\Site\Http\Controllers\PagesController;
use Lailen\OpenAdmin\Site\Http\Controllers\PhotosController;
use Lailen\OpenAdmin\Site\Http\Controllers\CategoriesController;
use Lailen\OpenAdmin\Site\Http\Controllers\EventsController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostsController;
use Lailen\OpenAdmin\Site\Http\Controllers\TagsController;
use Lailen\OpenAdmin\Site\Http\Controllers\SettingsController;
use Lailen\OpenAdmin\Site\Http\Controllers\VideosController;

/**
 * @todo middleware hmangin module extra deuh hlek ho hi disable theih sela.
 * middleware ah khan config('site.{{module}}.enabled') false chuan
 * redirect to admin home ni ta mai sela a fuh ang ka ring
 */
Route::resource('ll_menus.items', MenuItemsController::class);

Route::resource('ll_menus', MenusController::class);

Route::resource('ll_pages', PagesController::class);
Route::resource('ll_posts', PostsController::class);
Route::resource('ll_categories', CategoriesController::class);
Route::resource('ll_tags', TagsController::class);

Route::resource('ll_albums', AlbumsController::class);
Route::resource('ll_albums.photos', PhotosController::class);

Route::resource('ll_configs', ConfigsController::class);
Route::get('ll_settings', [SettingsController::class, 'index']);
Route::post('ll_settings', [SettingsController::class, 'updateSettings']);

Route::resource('ll_videos', VideosController::class);

Route::resource('ll_events', EventsController::class);
