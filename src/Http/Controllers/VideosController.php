<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Lailen\OpenAdmin\Site\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Helpers;
use Lailen\OpenAdmin\Site\Models\Video;
use OpenAdmin\Admin\Auth\Database\Administrator;

class VideosController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Video';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Video());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Video::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('embed_code', __('Embed Code'));
        $show->field('thumbnail', __('Thumbnail'))->image('/storage/' . config('site.image_prefix') . '/', config('site.posts.thumbnails.small'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        Helpers::addCategoriesAndTagsToDetails($show);

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Video());

        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->textarea('embed_code', __('Embed Code'))->required();

        $form->image('thumbnail', __('Thumbnail'))
            ->thumbnailFunction('small', function ($image) {
                $image->resize(config('site.posts.thumbnails.small'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->thumbnailFunction('medium', function ($image) {
                $image->resize(config('site.posts.thumbnails.medium'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->thumbnailFunction('large', function ($image) {
                $image->resize(config('site.posts.thumbnails.large'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->uniqueName()
            ->move('video-thumbnails');

        Helpers::addCategoriesAndTagsToForm($form);

        return $form;
    }
}
