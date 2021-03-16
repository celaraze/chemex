<?php

return [
    'labels' => [
        'title'        => '盘点',
        'description'  => '任务的具体明细',
        'tracks'       => '盘点记录',
        'Record None'  => '没有此盘点任务',
        'Item None'    => '没有此物资',
        'Update Track' => '处理盘点',
    ],
    'fields' => [
        'check_id' => '任务ID',
        'item_id'  => '物件',
        'status'   => '状态',
        'checker'  => [
            'name' => '盘点人员',
        ],
        'description' => '描述',
    ],
    'options' => [
    ],
];
