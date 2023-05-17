<?php

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\DeviceRecord;
use Dcat\Admin\Widgets\Widget;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


class DevicePrintController extends Controller
{
    /**
     * 页面.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */


    public function tag(Request $request)
    {
            $content = $request->all();
            $ids = explode(',', $content['id'] ?? null);
            if(count($ids) > 0) {
                $data = DeviceRecord::find($ids);
                switch($content['PrintType'])
                {
                    case'A4_Print':
                        return $this->make_device_tagA4pdf($data,$content['A4_Format']);
                        break;
                    case'Tag_Print':
                        $width = $content['width'];
                        $height = $content['height'];

                        switch($content['Tag_Format'])
                        {
                            case '60x40':
                            $width = 60;
                            $height = 40;
                            break;

                            case '90x70':
                                $width = 90;
                                $height = 70;
                            break;

                            default:
                            $width = $content['width'];
                            $height = $content['height'];
                            break;
                        }

                        return $this->make_device_tagpdf($data,$width,$height);
                }
            }
    }

    /**
     * 生成A4打印机PDF.
     * @param mixed $layout 标签排版：60x40,90x70
     */
    public function make_device_tagA4pdf($data,$layout){
        $html2pdf = new HTML2PDF('P', 'A4', 'cn', true, 'utf-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setDefaultFont('stsongstdlight');
        $html2pdf->writeHTML(view('print_tag', ['item' => 'device', 'data' => $data]), false);
        $html2pdf->output('qrcode.pdf');
    }

    /**
     * 生成标签打印机用PDF.
     * @param mixed $width 标签宽度
     * @param mixed $height 标签高度
     */
    public function make_device_tagpdf($data,$width,$height){
        $html2pdf = new HTML2PDF('L', array($width,$height), 'cn', true, 'utf-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setDefaultFont('stsongstdlight');
        foreach($data as $label){
            $html2pdf->writeHTML(view('print_tag_label_pdf', ['item' => 'device','width' => $width,'height' => $height, 'data' => $label]), false);
        }
        $html2pdf->output('Chemex_Device_label_PDF_'.microtime().'.pdf','D');
    }

    public function list(Request $request)
    {
        $ids = $request->input('ids');
        $ids = explode('-', $ids);
        if (count($ids) > 0) {
            $data = DeviceRecord::find($ids);
            return view('print_list', ['item' => 'device', 'data' => $data]);
        }
    }

    public function title(): array|string|Translator|null
    {
        return admin_trans_label('title');
    }
}


