<?php

use Illuminate\Support\Facades\Route;
use Lailen\OpenAdmin\Site\Http\Controllers\AlbumsController;
use Lailen\OpenAdmin\Site\Http\Controllers\MenusController;
use Lailen\OpenAdmin\Site\Http\Controllers\PagesController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostCategoriesController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostsController;
use Lailen\OpenAdmin\Site\Http\Controllers\PostTagsController;

/**
 * Menu
 */
Route::get('menus/{menu}/items', [MenusController::class, 'editItems']);
Route::get('menus/{menu}/items/{menuItem}/edit', [MenusController::class, 'editItem']);
Route::post('menus/{menu}/items', [MenusController::class, 'editItemsOrder']);
Route::post('menus/{menu}/store-item', [MenusController::class, 'storeItem']);
Route::put('menus/{menu}/items/{menuItem}/update-item', [MenusController::class, 'updateItem']);
Route::resource('menus', MenusController::class);

Route::resource('/pages', PagesController::class);
Route::resource('/posts', PostsController::class);
Route::resource('/post-categories', PostCategoriesController::class);
Route::resource('/post-tags', PostTagsController::class);

Route::resource('/albums', AlbumsController::class);
Route::get('/albums/{id}/photos', [AlbumsController::class, 'photos']);
Route::post('/albums/{id}/photos', [AlbumsController::class, 'storePhoto']);

