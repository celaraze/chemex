<?php

return [
    'labels' => [
        'title' => '配件',
        'description' => '排序和自定义操作',
        'columns' => '字段',
        'Name Help' => '字段名称，为保证兼容性请尽量使用英文。',
        'Nick Name Help' => '描述这个字段的名称，名称随意。',
        'Must Help' => '注意：日期和日期时间类型，将永远非必填。',
        'Delete' => '删除字段',
        'Update' => '编辑字段',
        'Delete Confirm' => '确认删除？',
        'Delete Confirm Description' => '删除的同时将会删除此字段的全部用户数据且无法恢复',
        'Custom Column Sort Delete' => '恢复默认排序',
        'Custom Column Delete Confirm' => '确定恢复默认排序？'
    ],
    'fields' => [
        'qrcode' => '二维码',
        'name' => '名称',
        'description' => '描述',
        'category' => [
            'name' => '分类',
        ],
        'vendor' => [
            'name' => '厂商',
        ],
        'device' => [
            'name' => '所属设备',
        ],
        'specification' => '规格',
        'price' => '价格',
        'purchased' => '购入日期',
        'expired' => '过保日期',
        'depreciation' => [
            'name' => '折旧规则',
        ],
        'asset_number' => '资产编号',
        'expiration_left_days' => '剩余保固时间',
        'nick_name' => '字段别名',
        'must' => '必填',
        'table_name' => '表名',
        'custom_column_id' => '自定义字段',
        'select_options' => '选项列表',
        'item' => '项',
        'new_name' => '新名称',
        'new_nick_name' => '新别名'
    ],
];
