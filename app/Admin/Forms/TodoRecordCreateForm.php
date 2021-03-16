<?php

namespace App\Admin\Forms;

use App\Models\TodoRecord;
use App\Models\User;
use App\Support\Data;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Exception;

class TodoRecordCreateForm extends Form
{
    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     * @return JsonResponse
     */
    public function handle(array $input): JsonResponse
    {
        $name = $input['name'] ?? null;
        $start = $input['start'] ?? null;

        $priority = $input['priority'] ?? null;
        $description = $input['description'] ?? null;
        $user_id = $input['user_id'] ?? null;
        $tags = $input['tags'] ?? null;
        if (empty($name) || empty($start)) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $todo_record = new TodoRecord();
            $todo_record->name = $name;
            $todo_record->start = $start;
            $todo_record->priority = $priority;
            $todo_record->description = $description;
            $todo_record->user_id = $user_id;
            $todo_record->tags = $tags;
            $todo_record->save();
            $return = $this
                ->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $e) {
            $return = $this
                ->response()
                ->error(trans('main.fail').$e->getMessage());
        }

        return $return;
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->text('name')->required();
        $this->datetime('start')->required();
        $this->divider();
        $this->select('priority')
            ->options(Data::priority())
            ->default('normal');
        $this->textarea('description');

        $this->select('user_id', admin_trans_label('User Id'))
            ->options(User::pluck('name', 'id'));
        $this->tags('tags')
            ->help(admin_trans_label('Tag Help'));
    }
}
