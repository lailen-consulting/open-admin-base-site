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
}
