<?php

return [
    'labels' => [
        'title' => '审批流程',
        'description' => '流程引擎定义',
        'records' => '审批流程',
    ],
    'fields' => [
        'name' => '名称',
        'description' => '描述',
        'role' => [
            'name' => '审核角色'
        ],
        'approval_id' => '审批',
        'role_id' => '审核角色'
    ],
];
