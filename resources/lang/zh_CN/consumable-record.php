<?php

return [
    'labels' => [
        'title' => '耗材',
        'description' => '主数据列表',
        'records' => '耗材',
        'In' => '入库',
        'Out' => '领用',
        'Deleted' => '已删除',
        'Batch Delete' => '批量删除耗材',
        'Batch Delete Confirm' => '您确定要删除选中的耗材吗？',
        'Batch Force Delete' => '批量强制删除耗材（不可恢复）',
        'Batch Force Delete Confirm' => '您确定要强制删除选中的耗材吗？（此操作不可逆）',
        'Delete' => '删除耗材',
        'Import' => '导入耗材',
        'Delete Confirm' => '确认删除？',
        'Delete Confirm Description' => '删除的同时将会删除所有与之关联的历史记录',
        'File Help' => '导入支持xlsx、csv文件，且表格头必填栏位【名称、规格、分类、厂商】，支持资产管理系统导出的Excel文件直接导入。',
    ],
    'fields' => [
        'name' => '名称',
        'description' => '描述',
        'specification' => '规格',
        'category' => [
            'name' => '分类',
        ],
        'vendor' => [
            'name' => '厂商',
        ],
        'price' => '价格',
        'user_id' => '用户（领用人）',
        'purchased' => '购入日期',
        'expired' => '过保日期',
        'category_id' => '分类',
        'vendor_id' => '厂商',
        'file' => '文件',
        'consumable_id' => '耗材',
        'track' => [
            'number' => '总数',
        ],
        'number' => '数量'
    ],
];
