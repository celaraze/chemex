<?php

return [
    'labels' => [
        'title' => '耗材',
        'description' => '主数据列表',
        'records' => '耗材',
        'In' => '入库',
        'Out' => '领用',
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
        'consumable_id' => '耗材',
        'number' => '总数',
    ],
];
