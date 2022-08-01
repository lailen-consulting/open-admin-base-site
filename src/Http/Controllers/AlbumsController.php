<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Lailen\OpenAdmin\Site\Actions\AlbumPhotos;
use Lailen\OpenAdmin\Site\Models\Album;
use Lailen\OpenAdmin\Site\Models\Category;
use Lailen\OpenAdmin\Site\Models\Photo;
use Lailen\OpenAdmin\Site\Models\Tag;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Widgets\Box;

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
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->actions(function ($actions) {
            $actions->add(new AlbumPhotos());
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
        $form = new Form(new Album());

        $form->text('name', __('Title'));
        $form->multipleSelect('categories','Categories')->options(Category::all()->pluck('name','id'));
        $form->multipleSelect('tags','Tags')->options(Tag::all()->pluck('name','id'));
        $form->textarea('description', __('Description'));
        $form->datetime('time', __('Time'))->value(now());

        return $form;
    }

    public function photos($id, Content $content)
    {
        $grid = new Grid(new Photo);
        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($grid);
    }

    public function getPhotoForm($albumId)
    {
        $form = new \OpenAdmin\Admin\Widgets\Form();
        $form->action(admin_url('albums/' . $albumId . '/photos'));

        $form->textarea('caption', 'Caption');
        $form->image('image', 'Image')
            ->rules('required')
            ->thumbnail([
                'small' => [250, null],
                'medium' => [500, null],
                'full' => [800, null],
            ]);

        return $form;
    }

    public function storePhoto($albumId)
    {
        return $this->getPhotoForm($albumId)->store();
    }

    public function createPhoto($albumId, Content $content)
    {
        $form = $this->getPhotoForm($albumId);

        if ($this->hasHooks('alterForm')) {
            $form = $this->callHooks('alterForm', $form);
        }

        return $content
            ->title('Photo')
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body($form);
    }
}
