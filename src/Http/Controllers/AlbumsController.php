<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Lailen\OpenAdmin\Site\Actions\AlbumPhotos;
use Lailen\OpenAdmin\Site\Models\Album;
use Lailen\OpenAdmin\Site\Models\Photo;
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
        $form->textarea('description', __('Description'));
        $form->datetime('time', __('Time'))->value(now());

        return $form;
    }

    public function photos($id, Content $content)
    {
        return $content
            ->title('Photos')
            ->description('Add/Edit Photos')
            ->row(function (Row $row) use ($id) {
                $row->column(7, function(Column $column) use ($id) {
                    $grid = new Grid(new Photo);
                    $grid->column('path')->image('/storage/gallery/' . $id, 100, 100);
                    $grid->column('caption');
                    $column->append(new Box('Photos', $grid->render()));
                });

                $row->column(5, function (Column $column) use ($id) {
                    $form = new \OpenAdmin\Admin\Widgets\Form();
                    $form->action(admin_url('albums/' . $id . '/photos'));

                    $form->textarea('caption', 'Caption');
                    $form->image('image', 'Image')->rules('required');
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box('New Item', $form))->style('success'));
                });
            });
    }

    public function storePhoto($id, Request $request)
    {
        $path = Str::random(16);
        $image = $request->file('image');
        $image->move(storage_path('app/public/gallery/' . $id), $path . '.jpg');


        Photo::create([
            'album_id' => $id,
            'caption' => $request->input('caption'),
            'path' => $path . '.jpg',
        ]);

        return redirect(admin_url('albums/' . $id . '/photos'));
    }
}
