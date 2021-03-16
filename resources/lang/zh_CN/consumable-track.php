<?php

return [
    'labels' => [
        'title'       => '耗材',
        'description' => '入库领用记录',
        'tracks'      => '耗材记录',
    ],
    'fields' => [
        'consumable' => [
            'name' => '耗材',
        ],
        'operator' => '发生人',
        'user'     => [
            'name' => '用户（领用人）',
        ],
        'number'    => '数量',
        'change'    => '改变',
        'purchased' => '购入日期',
        'expired'   => '过保日期',
    ],
];
