<?php

return [
    'labels' => [
        'title' => '配件',
        'description' => '主数据列表',
        'records' => '配件',
        'Category' => '分类',
        'Vendor' => '厂商',
        'Depreciation Rule' => '折旧规则',
        'Batch Delete' => '批量删除配件',
        'Batch Delete Confirm' => '您确定要删除选中的配件吗？',
        'Batch Force Delete' => '批量强制删除配件（不可恢复）',
        'Batch Force Delete Confirm' => '您确定要强制删除选中的配件吗？（此操作不可逆）',
        'Delete' => '删除配件',
        'Delete Confirm' => '确认删除？',
        'Delete Confirm Description' => '删除的同时将会解除所有与之关联的归属关系',
        'Track Create Update' => '归属设备',
        'Import' => '导入配件',
        'Maintenance Create' => '报告故障',
        'File Help' => '导入支持xlsx、csv文件，且表格头必填栏位【资产编号、分类、规格、厂商】，支持资产管理系统导出的Excel文件直接导入。',
        'Deleted' => '已删除',
        'Track Delete' => '解除归属',
        'Track Delete Confirm' => '确认解除与此用户的关联？'
    ],
    'fields' => [
        'asset_number_qrcode' => '二维码',
        'name' => '名称',
        'description' => '描述',
        'category' => [
            'name' => '分类',
        ],
        'vendor' => [
            'name' => '厂商',
        ],
        'device' => [
            'asset_number' => '所属设备',
        ],
        'specification' => '规格',
        'price' => '价格',
        'sn' => '配件序列号',
        'purchased' => '购入日期',
        'expired' => '过保日期',
        'depreciation' => [
            'name' => '折旧规则',
            'termination' => '报废日期',
        ],
        'asset_number' => '资产编号',
        'expiration_left_days' => '剩余保固时间',
        'depreciation_price' => '折旧后价格',
        'category_id' => '分类',
        'vendor_id' => '厂商',
        'depreciation_id' => '折旧规则',
        'device_id' => '设备',
        'ng_description' => '故障说明',
        'ng_time' => '故障发生时间',
        'file' => '文件',
    ],
];
