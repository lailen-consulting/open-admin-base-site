<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Lailen\OpenAdmin\Site\Helpers;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Photo;
use Lailen\OpenAdmin\Site\Models\Tag;

class PhotosController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Photos';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Photo());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('/storage/' . config('site.image_prefix') . '/', 200, 200);;
        $grid->column('caption', __('Caption'));
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
        $show = new Show(Photo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'))->image('/storage/' . config('site.image_prefix') . '/', 200, 200);
        $show->field('caption', __('caption'));
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
        $albumId = request()->route('ll_album');
        $form = new Form(new Photo());

        $form->text('caption', __('Caption'));
        $form->hidden('album_id')->value($albumId);
        $form->image('image', __('Image'))->thumbnail([
            'small' => [250, null],
            'medium' => [500, null],
            'full' => [800, null],
        ])
        ->required()
        ->uniqueName()
        ->move('albums/' . $albumId . '/');

        Helpers::addCategoriesAndTagsToForm($form);

        return $form;
    }
}
