<?php

return [
    'labels' => [
        'title' => '待办事项',
        'description' => '轻量的 TODO 功能',
        'records' => '待办',
        'User Id' => '用户',
        'Update' => '处理待办',
        'Create' => '创建待办',
        'Tag Help' => '随意打上标签，输入后按空格创建，再按回车确定。',
    ],
    'fields' => [
        'name' => '名称',
        'description' => '描述',
        'start' => '开始时间',
        'end' => '结束时间',
        'priority' => '优先级',
        'user' => [
            'name' => '负责人',
        ],
        'tags' => '标签',
        'done_description' => '完成说明',
        'emoji' => '心情',
        'user_id' => '负责人'
    ],
    'options' => [
    ],
];
