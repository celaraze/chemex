<?php

namespace App\Admin\Forms;

use App\Models\Department;
use App\Models\ImportLog;
use App\Models\ImportLogDetail;
use App\Services\LDAPService;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Dcat\EasyExcel\Excel;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;


class DepartmentImportForm extends Form
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
        $success = 0;
        $fail = 0;

        $import_log = new ImportLog();
        $import_log->item = get_class(new Department());
        $import_log->operator = Admin::user()->id;
        $import_log->save();

        if ($input['type'] == 'file') {
            $file = $input['file'];
            $file_path = public_path('uploads/' . $file);

            try {
                $rows = Excel::import($file_path)->first()->toArray();
                foreach ($rows as $row) {
                    $name = $row['名称'] ?? '未知';
                    try {
                        if (!empty($row['名称'])) {
                            $department = new Department();
                            $department->name = $row['名称'];
                            if (!empty($row['描述'])) {
                                $department->description = $row['描述'];
                            }
                            if (!empty($row['父级部门'])) {
                                $parent_department = Department::where('name', $row['父级部门'])->first();
                                if (empty($parent_department)) {
                                    $parent_department = new Department();
                                    $parent_department->name = $row['父级部门'];
                                    $parent_department->save();
                                }
                                $department->parent_id = $parent_department->id;
                            }
                            $department->save();
                            $success++;
                            ImportLogDetail::query()->create([
                                'log_id' => $import_log->id,
                                'status' => 1,
                                'log' => $row['名称'] . '：导入成功！'
                            ]);
                        } else {
                            $fail++;
                            // 导入日志写入
                            ImportLogDetail::query()->create([
                                'log_id' => $import_log->id,
                                'log' => $name . '：导入失败，缺少必要的字段：名称！'
                            ]);
                        }
                    } catch (Exception $exception) {
                        $fail++;
                        // 导入日志写入
                        ImportLogDetail::query()->create([
                            'log_id' => $import_log->id,
                            'log' => $name . '：导入失败，' . $exception->getMessage()
                        ]);
                    }
                }

                return $this->response()
                    ->success(trans('main.success') . ': ' . $success . ' ; ' . trans('main.fail') . ': ' . $fail . '，导入结果详情请至导入日志查看。')
                    ->refresh();
            } catch (IOException $exception) {
                return $this->response()
                    ->error(trans('main.file_io_error') . $exception->getMessage());
            } catch (UnsupportedTypeException $exception) {
                return $this->response()
                    ->error(trans('main.file_format') . $exception->getMessage());
            } catch (FileNotFoundException $exception) {
                return $this->response()
                    ->error(trans('main.file_none') . $exception->getMessage());
            }
        }

        if ($input['type'] == 'ldap') {
            $result = LDAPService::importUserDepartments($input['mode']);
            return $this->response()
                ->success(trans('main.success') . ': ' . $result[0] . ' ; ' . trans('main.fail') . ': ' . $result[1] . '，导入结果详情请至导入日志查看。')
                ->refresh();
        }
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this->select('type')
            ->when('file', function (Form $form) {
                $form->file('file')
                    ->help(admin_trans_label('File Help'))
                    ->accept('xlsx,csv')
                    ->autoUpload()
                    ->uniqueName();
            })
            ->when('ldap', function (Form $form) {
                $form->radio('mode')
                    ->options(['rewrite' => admin_trans_label('Rewrite'), 'merge' => admin_trans_label('Merge')])
                    ->default('merge');
            })
            ->options(['file' => admin_trans_label('File'), 'ldap' => admin_trans_label('LDAP')])
            ->required()
            ->default('file');
    }
}
