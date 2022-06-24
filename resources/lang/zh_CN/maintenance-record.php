<?php

return [
    'labels' => [
        'title' => '资产故障',
        'description' => '报告故障的设备、配件等',
        'records' => '物资故障',
        'Status Waiting' => '等待处理',
        'Status Done' => '完成',
        'Status Cancelled' => '取消',
        'Update' => '处理故障',
    ],
    'fields' => [
        'asset_number' => '资产编号',
        'ng_description' => '故障说明',
        'ok_description' => '维修说明',
        'ng_time' => '故障时间',
        'ok_time' => '维修时间',
        'status' => '状态',
    ],
    'options' => [
    ],
];
