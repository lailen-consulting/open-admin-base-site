<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Models\Post;
use Lailen\OpenAdmin\Site\Models\PostCategory;
use Lailen\OpenAdmin\Site\Models\PostTag;
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
        $show->field('image_path', __('Image'))->image('/storage/admin/', 200, 200);
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
        $form->textarea('excerpt', __('Excerpt'));
        $form->ckeditor('content', __('Content'))->required();
        $form->image('image_path', __('Image'))->thumbnail([
            'small' => [250, null],
            'medium' => [500, null],
            'full' => [800, null],
        ]);

        $form->select('user_id', __("Author"))->options(Administrator::all()->pluck('name', 'id'));
        $form->multipleSelect('categories','Categories')->options(PostCategory::all()->pluck('name','id'));
        $form->multipleSelect('tags','Tags')->options(PostTag::all()->pluck('name','id'));

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
