<?php
namespace Lailen\OpenAdmin\Site\Traits;

use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Tag;

/**
 * Category leh tag nei ho intawm tur
 */
trait HasCategoriesAndTags
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
