<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Lailen\OpenAdmin\Site\Helpers;
use Lailen\OpenAdmin\Site\Models\Event;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class EventsController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Event';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Event());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('starts_at', __('Starts at'));
        $grid->column('ends_at', __('Ends at'));
        $grid->column('location', __('Location'));

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
        $show = new Show(Event::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('starts_at', __('Starts at'));
        $show->field('ends_at', __('Ends at'));
        $show->field('location', __('Location'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('photo', __('Photo'))->image('/storage/' . config('site.image_prefix') . '/', config('site.posts.thumbnails.small'));;
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

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
        $form = new Form(new Event());

        $form->text('title', __('Title'))->required();
        $form->textarea('description', __('Description'));
        $form->datetime('starts_at', __('Starts at'))->default(date('Y-m-d H:i:s'))->required();
        $form->datetime('ends_at', __('Ends at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('location', __('Location'));
        $form->text('latitude', __('Latitude'));
        $form->text('longitude', __('Longitude'));

        $form->image('photo', __('Event Photo'))
            ->thumbnailFunction('small', function ($image) {
                $image->resize(config('site.events.thumbnails.small'), null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                return $image;
            })
            ->thumbnailFunction('medium', function ($image) {
                $image->resize(config('site.events.thumbnails.medium'), null, function ($constraint) {
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
            ->move('page-images');
        Helpers::addCategoriesAndTagsToForm($form);
        return $form;
    }
}
