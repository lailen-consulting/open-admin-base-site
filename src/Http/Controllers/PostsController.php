<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Models\Post;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Tag;
use OpenAdmin\Admin\Auth\Database\Administrator;

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
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('published_at', __('Published at'));
        $show->field('slug', __('Slug'));
        $show->field('excerpt', __('Excerpt'));
        $show->field('image', __('Image'))->image('/storage/' . config('site.posts.image_prefix') . '/', config('site.posts.thumbnails.small'));
        $show->field('content', __('Content'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

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
        $form->textarea('excerpt', __('Excerpt'))->rules('max:100');
        $form->ckeditor('content', __('Content'))->required();

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
        $form->multipleSelect('categories','Categories')->options(Category::all()->pluck('name','id'));
        $form->multipleSelect('tags','Tags')->options(Tag::all()->pluck('name','id'));

        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));

        $form->saving(function (Form $form){
            if (!isset($form->user_id)) {
                $form->user_id = Auth::user()->id;
            }
            $model = $form->model();
            $model->slug = Str::slug($form->input('title'));


            if (!isset($model->published_at)) {
                $model->published_at = now();
            }
        });

        return $form;
    }
}
