<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Page;
use Lailen\OpenAdmin\Site\Models\Post;
use Lailen\OpenAdmin\Site\Models\Tag;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $posts = new InfoBox('Posts', 'file', 'success', '/admin/ll_posts', Post::count());
        $pages = new InfoBox('Pages', 'files', 'warning', '/admin/ll_pages', Page::count());

        $categories = new InfoBox('Categories', 'file', 'success', '/admin/ll_categories', Category::count());
        $tags = new InfoBox('Tags', 'tag', 'info', '/admin/ll_tags', Tag::count());

        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->row(function (Row $row) use ($posts, $pages, $categories, $tags) {
                $row->column(4, function (Column $column) use ($posts, $pages) {
                    $column->append($posts);
                    $column->append($pages);
                });

                $row->column(4, function (Column $column) use($categories, $tags) {
                    $column->append($categories);
                    $column->append($tags);
                });

                $row->column(4, function (Column $column) {
                });
            });
    }
}
