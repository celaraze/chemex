<?php

namespace App\Services;

use App\Models\CheckRecord;
use App\Models\CheckTrack;
use App\Support\Data;
use Dcat\Admin\Admin;
use TCPDF;

class CheckService
{
    /**
     * 报告报告.
     * @param $check_id
     * @return string
     */
    public static function report($check_id): string
    {
        $check_record = CheckRecord::where('id', $check_id)->first();
        if (empty($check_record)) {
            return '未找到此盘点任务！';
        }

        $user = Admin::user();
        $pdf = new TCPDF();
        $pdf->SetCreator($user->name);
        $pdf->SetAuthor($user->name);
        $pdf->SetTitle('咖啡壶 Chemex');
        $pdf->SetSubject('盘点报告');

        // 设置页眉和页脚信息
//        $pdf->SetHeaderData(null, 30, 'LanRenKaiFA.com', $user->name, [0, 64, 255], [0, 64, 128]);
//        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(['stsongstdlight', '', '10']);
        $pdf->setFooterFont(['helvetica', '', '8']);

        // 设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        // 设置间距
        $pdf->SetMargins(15, 15, 15); //页面间隔
        $pdf->SetHeaderMargin(5); //页眉top间隔
        $pdf->SetFooterMargin(10); //页脚bottom间隔

        // 设置分页
        $pdf->SetAutoPageBreak(true, 25);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        //设置字体 stsongstdlight支持中文
        $pdf->SetFont('stsongstdlight', '', 14);

        //第一页
        $pdf->AddPage();
        $pdf->writeHTML('<div style="text-align: center"><h1>' . Data::items()[$check_record->check_item] . '盘点报告</h1></div>');
        $pdf->writeHTML('<p style="text-align: center">盘点任务创建时间：' . $check_record->created_at . '</p>');
        $pdf->writeHTML('<p style="text-align: center">盘点报告生成时间：' . date('yy-m-d H:m:i') . '</p>');
        $pdf->Ln(5); //换行符
        $pdf->writeHTML('<div style="text-align: center">盘点状态：' . Data::checkRecordStatus()[$check_record->status] . '</div>');
        $pdf->Ln(5); //换行符

        $all_counts = CheckTrack::where('check_id', $check_id)->count();
        $yes_counts = CheckTrack::where('check_id', $check_id)->where('status', 1)->count();
        $no_counts = CheckTrack::where('check_id', $check_id)->where('status', 2)->count();
        $left_counts = $all_counts - $yes_counts - $no_counts;
        $pdf->writeHTML('<div style="text-align: center">条目总数：' . $all_counts . '</div>');
        $pdf->writeHTML('<div style="text-align: center">盘盈总数：' . $yes_counts . '</div>');
        $pdf->writeHTML('<div style="text-align: center">盘亏总数：' . $no_counts . '</div>');
        $pdf->writeHTML('<div style="text-align: center">未盘总数：' . $left_counts . '</div>');

        //输出PDF
        return $pdf->Output('t.pdf', 'D'); //I输出、D下载
    }
}
