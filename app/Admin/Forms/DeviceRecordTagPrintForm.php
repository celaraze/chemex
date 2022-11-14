<?php
namespace App\Admin\Forms;

use App\Models\CustomColumn;
use App\Models\DeviceCategory;
use App\Models\DeviceRecord;
use App\Models\DeviceTrack;
use App\Models\ImportLog;
use App\Models\ImportLogDetail;
use App\Models\User;
use App\Models\VendorRecord;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use Barryvdh\DomPDF\Facade\Pdf;

class DeviceRecordTagPrintForm extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     */
    public function handle(array $input)
    {
        if(!$input['id']) {
            return $this->response()->warning('没有选择的设备资产');
        }
        $token = csrf_token();
        $data = "{
                    PrintType:'{$input['PrintType']}',
                    A4_Format:'{$input['A4_Format']}',
                    Tag_Format:'{$input['Tag_Format']}',
                    id:'{$input['id']}',
                    width:'{$input['width']}',
                    height:'{$input['height']}',
                    '_token':'{$token}'
                }";

        $url = admin_route('device.print.tag');
        return $this->response()->script(
            <<<JS
                var temp_form = document.createElement('form');
                temp_form.action = '{$url}';
                //temp_form.target = '_blank';
                temp_form.method = 'post';
                temp_form.style.display = 'none';

                var PARAMS = {$data};
                for (var x in PARAMS) {
                    var opt = document.createElement('textarea');
                    opt.name = x;
                    opt.value = PARAMS[x];
                    temp_form.appendChild(opt);
                }

                document.body.appendChild(temp_form);
                temp_form.submit();
            JS);
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $this ->radio('PrintType','打印类型')
        ->when('A4_Print',function(form $form){

            $form->radio('A4_Format','A4模板')
                 ->options([
                    '90x70' => '每页8张，每张 90x70 MM',
                    '60x40' => '每页21张，每张 60x40 MM',
                    'Custom'=> '自定义'
                    ])
                 ->disable();
                 $form->display('')->value('开发中…… | Not supported');

        })
        ->when('Tag_Print',function(form $form){

            $form->radio('Tag_Format','标签尺寸')
                 ->when('Custom',function(form $form){
                    $form->text('width','宽度');
                    $form->text('height','高度');
                 })
                 ->options([
                    '60x40' => '60x40 MM',
                    '90x70'=> '90x70 MM',
                    'Custom'=>'自定义'
                    ])
                 ->default('60x40');
        })
        ->options([
            'A4_Print'=>'使用 A4打印机',
            'Tag_Print'=>'使用 标签打印机'
        ])
        ->default('Tag_Print');

        // 设置隐藏表单，传递用户id
        $this->hidden('id')->attribute('id', 'check-Device-ids');
        //关闭按钮
        $this->disableResetButton();
        //$this->disableSubmitButton();

    }
}
