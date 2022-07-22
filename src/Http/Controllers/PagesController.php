<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Lailen\OpenAdmin\Site\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Auth\Database\Administrator;

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
        $grid->column('published_at', __('Published at'));
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
        $show = new Show(Page::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('slug', __('Slug'));
        $show->field('excerpt', __('Excerpt'));
        $show->field('content', __('Content'));
        $show->field('image', __('Image'))->image('/storage/admin/', 200, 200);
        $show->field('user_id', __('User id'));
        $show->field('published_at', __('Published at'));
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
        $form = new Form(new Page());

        $form->text('title', __('Title'))->required();
        $form->textarea('excerpt', __('Excerpt'));
        $form->ckeditor('content', __('Content'))->rules('required', ['required' => 'Content is required']);
        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));
        $form->select('user_id', __("Author"))->options(Administrator::all()->pluck('name', 'id'));
        $form->image('image', __('Image'))->thumbnail([
            'small' => [250, null],
            'medium' => [500, null],
            'full' => [1024, null],
        ]);

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
