<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Helpers;
use Lailen\OpenAdmin\Site\Models\Post;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Tag;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Form\NestedForm;

class PostsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('user.name', __('Author'));
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
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('published_at', __('Published at'));
        $show->field('slug', __('Slug'));
        $show->field('excerpt', __('Excerpt'));
        $show->field('image', __('Image'))->image('/storage/' . config('site.image_prefix') . '/', config('site.posts.thumbnails.small'));
        $show->field('content', __('Content'));
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
        $form = new Form(new Post());

        $form->text('title', __('Title'))->required();
        // $form->text('slug', __('Slug'));
        $form->textarea('excerpt', __('Excerpt'))->required();
        
        $form->ckeditor('content', __('Content'))->options([
            'extraPlugins' => ['justify'],
            'toolbarGroups' => [
                [ 'name' => 'clipboard',   'groups' => [ 'clipboard', 'undo' ] ],
                // [ 'name' => 'editing',     'groups' => [ 'find', 'selection', 'spellchecker' ] ],
                [ 'name' => 'links' ],
                [ 'name' => 'insert' ],
                [ 'name' => 'forms' ],
                [ 'name' => 'tools' ],
                [ 'name' => 'document',	   'groups' => [ 'mode', 'document', 'doctools' ] ],
                [ 'name' => 'others' ],
                '/',
                [ 'name' => 'basicstyles', 'groups' => [ 'basicstyles', 'cleanup' ] ],
                [ 'name' => 'paragraph',   'groups' => [ 'align', 'list', 'indent', 'blocks', 'bidi' ] ],
                [ 'name' => 'styles' ],
                [ 'name' => 'colors' ],
                [ 'name' => 'about' ]
            ],
        ])->required();

        $form->image('image', __('Image'))
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
            ->move('post-images');

        $form->select('user_id', __("Author"))->options(Administrator::all()->pluck('name', 'id'));

        Helpers::addCategoriesAndTagsToForm($form);

        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));

        $form->morphMany('attachments', 'Attachments', function (NestedForm $subForm) {
            $subForm->text('title', 'Name');
            $subForm->file('location', 'Select File')->required()->move('post-attachments');
        });

        $form->saving(function (Form $form){
            if (!isset($form->user_id)) {
                $form->user_id = Auth::user()->id;
            }
            $model = $form->model();
            $model->slug = Str::slug($form->input('title'));
            $existingCount = Post::where('slug', $model->slug)->count();
            if ($existingCount && !($existingCount == 1 && Post::where('slug', $model->slug)->first()->id == $model->id)) {
                $model->slug = $model->slug . '-' . uniqid();
            }

            if (!isset($model->published_at)) {
                $model->published_at = now();
            }
        });

        return $form;
    }
}
