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

Route::resource('menus.items', MenuItemsController::class);

Route::resource('menus', MenusController::class);

Route::resource('/pages', PagesController::class);
Route::resource('/posts', PostsController::class);
Route::resource('/post-categories', PostCategoriesController::class);
Route::resource('/post-tags', PostTagsController::class);

Route::resource('/albums', AlbumsController::class);
Route::resource('/albums.photos', PhotosController::class);

Route::resource('/configs', ConfigsController::class);
Route::get('/settings', [SettingsController::class, 'index']);
Route::post('/settings', [SettingsController::class, 'updateSettings']);
