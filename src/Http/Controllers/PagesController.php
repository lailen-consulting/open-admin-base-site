<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Lailen\OpenAdmin\Site\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Helpers;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Form\NestedForm;

class PagesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Page';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Page());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('user.name', __('Author'));
        $grid->column('published_at', __('Published at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        $grid->model()->orderBy('id', 'desc');


        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('title', 'Search by Title');
            $filter->like('content', 'Search by Content');
        });

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
        $show = new Show(Page::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('slug', __('Slug'));
        $show->field('excerpt', __('Excerpt'));
        $show->field('content', __('Content'));
        $show->field('image', __('Image'))->image('/storage/' . config('site.image_prefix') . '/', config('site.posts.thumbnails.small'));
        $show->field('user_id', __('User id'));
        $show->field('published_at', __('Published at'));
        $show->field('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('deleted_at', __('Deleted at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        $show->attachments('Attachments', function ($attachments) {
            $attachments->resource('/admin/ll_attachments');

            $attachments->id();
            $attachments->location()->downloadable('/storage/site');
            $attachments->title();
            $attachments->type();
        });

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
        $form = new Form(new Page());

        $form->text('title', __('Title'))->required();
        $form->textarea('excerpt', __('Excerpt'));
        $form->ckeditor('content', __('Content'))->rules('required', ['required' => 'Content is required']);


        $form->image('image', __('Page Image'))
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
            ->uniqueName()
            ->move('page-images');

        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));
        $form->select('user_id', __("Author"))->options(Administrator::all()->pluck('name', 'id'));

        Helpers::addCategoriesAndTagsToForm($form);

        $form->morphMany('attachments', 'Attachments', function (NestedForm $subForm) {
            $subForm->text('title', 'Name');
            $subForm->file('location', 'Select File')->required()->move('page-attachments');
        });

        $form->saving(function (Form $form){
            if (!isset($form->user_id)) {
                $form->user_id = Auth::user()->id;
            }

            $model = $form->model();
            $model->slug = Str::slug($form->input('title'));
        });


        return $form;
    }
}
