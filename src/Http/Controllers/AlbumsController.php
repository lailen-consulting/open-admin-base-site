<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Lailen\OpenAdmin\Site\Actions\AlbumPhotos;
use Lailen\OpenAdmin\Site\Helpers;
use Lailen\OpenAdmin\Site\Models\Album;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Photo;
use Lailen\OpenAdmin\Site\Models\Tag;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Form\NestedForm;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Show;

class AlbumsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Albums';

    public function __construct()
    {
        $this->hook("alterForm", function ($scope, $form) {
            $form->saving(function (Form $form){
                $model = $form->model();
                $model->user_id = Auth::user()->id;
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
        $grid = new Grid(new Album());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('time', __('Time'));
        $grid->column('user.name', __('Author'));
        $grid->column('created_at', __('Created at'))->display(function($createdAt) {
            return Carbon::create($createdAt)->format('dS M, Y h:i a');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($updatedAt) {
            return Carbon::create($updatedAt)->format('dS M, Y h:i a');
        });

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('name', 'Search by name');
            $filter->like('description', 'Search by description');
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
        $show = new Show(Album::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Title'));
        $show->field('description', __('Description'));
        $show->column('user.name', __('Author'));
        $show->field('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('deleted_at', __('Deleted at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        $show->photos('photos', function ($photo) {
            $photo->resource('/admin/ll_photos');
            $photo->image('image')->image('/storage/site');
            $photo->caption();
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
        $form = new Form(new Album());

        $form->text('name', __('Title'))->required();
        $form->textarea('description', __('Description'));
        $form->datetime('time', __('Time'))->value(now());
        $form->multipleSelect('categories','Categories')->options(Category::all()->pluck('name','id'));
        $form->multipleSelect('tags','Tags')->options(Tag::all()->pluck('name','id'));

        $form->hasMany('photos', 'Photos', function (NestedForm $nestedForm) {
            Helpers::setupPhotoForm($nestedForm);
        });

        return $form;
    }
}
