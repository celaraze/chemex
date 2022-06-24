<?php

return [
    'labels' => [
        'title' => '设备',
        'description' => '支持多级树形结构',
        'categories' => '设备分类',
        'Import' => '导入',
        'File Help' => '导入支持xlsx、csv文件，且表格头必须使用【名称，描述】。',
    ],
    'fields' => [
        'name' => '名称',
        'description' => '描述',
        'parent_id' => '父级分类',
        'depreciation_rule_id' => '折旧规则',
        'file' => '文件',
    ],
    'options' => [
    ],
];
