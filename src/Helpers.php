<?php

namespace Lailen\OpenAdmin\Site;

use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Tag;

class Helpers
{
    public static function addCategoriesAndTagsToForm($form)
    {
        $form->multipleSelect('categories','Categories')->options(Category::all()->pluck('name','id'));
        $form->multipleSelect('tags','Tags')->options(Tag::all()->pluck('name','id'));
    }

    public static function addCategoriesAndTagsToDetails($show)
    {
        $show->divider();
        $show->field('categories', __('Categories'))->as(function ($categories) {
            return $categories->map(function ($item){
                return $item->name;
            })->join(',');
        });
        $show->field('tags', __('Tags'))->as(function ($tags) {
            return $tags->map(function ($item){
                return $item->name;
            })->join(', ');
        });
    }

    public static function setupPhotoForm($form)
    {
        $form->textarea('caption', 'Caption');
        if ($albumId = request()->input('album_id')) {
            $form->hidden('album_id', $albumId)->value($albumId);
        }
        $form->image('image', __('Image'))
            ->thumbnailFunction('small', function ($image) {
                $image->resize(config('site.default_image_size.small'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->thumbnailFunction('medium', function ($image) {
                $image->resize(config('site.default_image_size.medium'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->thumbnailFunction('large', function ($image) {
                $image->resize(config('site.default_image_size.large'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->required()
            ->move('gallery-images')
            ->uniqueName();

        Helpers::addCategoriesAndTagsToForm($form);

        return $form;
    }
}
