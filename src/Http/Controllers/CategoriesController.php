<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Lailen\OpenAdmin\Site\Models\Category;
use Illuminate\Support\Str;

class CategoriesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Categories';

    public function __construct()
    {
        $this->hook("alterForm", function ($scope, $form) {
            $form->saving(function (Form $form){
                $model = $form->model();
                $model->slug = Str::slug($form->input('name'));
            });
            return $form;
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('image', __('Image'))->image('/storage/admin/', 200, 200);
        $grid->column('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('deleted_at', __('Deleted at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('image', __('Image'))->image('/storage/admin/', 200, 200);
        $show->field('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('deleted_at', __('Deleted at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Category());

        $form->text('name', __('Name'))->required();
        // $form->text('slug', __('Slug'));
        $form->image('image', __('Image'));

        return $form;
    }
}
