<?php

return [
    'labels' => [
        'title' => '设备',
        'description' => '与用户的使用关系',
        'tracks' => '设备归属记录',
        'History Scope' => '查看历史归属记录',
        'Delete' => '解除归属',
        'Track None' => '找不到此设备归属记录！',
        'Delete Success' => '设备归属解除成功！',
        'Delete Confirm' => '确认解除与此用户的关联？',
        'Update Delete' => '归还设备',
    ],
    'fields' => [
        'device' => [
            'name' => '设备',
        ],
        'user' => [
            'name' => '用户',
        ],
        'lend_time' => '借用时间',
        'lend_description' => '借用描述',
        'plan_return_time' => '计划归还时间',
        'return_time' => '归还时间',
        'return_description' => '归还描述',
    ],
    'options' => [
    ],
];
