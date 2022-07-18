<?php

return [
    'labels' => [
        'title' => '软件',
        'description' => '主数据列表',
        'records' => '软件',
        'Manage Track' => '管理归属',
        'Export To Excel' => '导出到 Excel',
        'Counts Help' => '"-1"表示无限制。',
        'Batch Delete' => '批量删除软件',
        'Batch Delete Confirm' => '您确定要删除选中的软件吗？',
        'Batch Force Delete' => '批量强制删除软件（不可恢复）',
        'Batch Force Delete Confirm' => '您确定要强制删除选中的软件吗？（此操作不可逆）',
        'Delete' => '删除软件',
        'Delete Confirm' => '确认删除？',
        'Delete Confirm Description' => '删除的同时将会解除所有与之关联的归属关系',
        'Track Create Update' => '归属到设备',
        'Import' => '导入软件',
        'File Help' => '导入支持xlsx、csv文件，且表格头必填栏位【资产编号、名称、分类、厂商、版本】，支持资产管理系统导出的Excel文件直接导入。',
        'Deleted' => '已删除'
    ],
    'fields' => [
        'asset_number_qrcode' => '二维码',
        'name' => '名称',
        'description' => '描述',
        'category' => [
            'name' => '分类',
        ],
        'version' => '版本',
        'vendor' => [
            'name' => '厂商',
        ],
        'price' => '价格',
        'purchased' => '购入时间',
        'expired' => '过保时间',
        'distribution' => '发行方式',
        'counts' => '授权数量',
        'left_counts' => '剩余授权数量',
        'device' => [
            'asset_number' => '所属设备',
            'user' => [
                'name' => '用户',
            ],
        ],
        'asset_number' => '资产编号',
        'expiration_left_days' => '剩余保固时间',
        'category_id' => '分类',
        'vendor_id' => '厂商',
        'depreciation_id' => '折旧规则',
        'file' => '文件',
    ],
    'options' => [
    ],
];
