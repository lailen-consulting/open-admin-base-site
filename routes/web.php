<?php

use Illuminate\Support\Facades\Route;
use Lailen\OpenAdmin\Site\Http\Controllers\AlbumsController;
use Lailen\OpenAdmin\Site\Http\Controllers\ConfigsController;
use Lailen\OpenAdmin\Site\Http\Controllers\MenuItemsController;
use Lailen\OpenAdmin\Site\Http\Controllers\MenusController;
use Lailen\OpenAdmin\Site\Http\Controllers\PagesController;
use Lailen\OpenAdmin\Site\Http\Controllers\PhotosController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostCategoriesController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostsController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostTagsController;
use Lailen\OpenAdmin\Site\Http\Controllers\SettingsController;

Route::resource('ll_menus.items', MenuItemsController::class);

Route::resource('ll_menus', MenusController::class);

Route::resource('ll_pages', PagesController::class);
Route::resource('ll_posts', PostsController::class);
Route::resource('ll_post-categories', PostCategoriesController::class);
Route::resource('ll_post-tags', PostTagsController::class);

Route::resource('ll_albums', AlbumsController::class);
Route::resource('ll_albums.photos', PhotosController::class);

Route::resource('ll_configs', ConfigsController::class);
Route::get('ll_settings', [SettingsController::class, 'index']);
Route::post('ll_settings', [SettingsController::class, 'updateSettings']);
