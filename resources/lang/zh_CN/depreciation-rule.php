<?php

return [
    'labels' => [
        'title'            => '折旧规则',
        'description'      => '为资产自定义折旧或报废规则',
        'rules'            => '折旧规则',
        'Description Help' => '周期填入最大即可，例如2年代表超过2年，5年代表超过5年，规则会优先从高到低匹配，先判断是否超过5年，如果没有超过则再判断是否超过3年，以此类推。',
        'Rules Symbol'     => '0.00 ~ 1.00 之间',
    ],
    'fields' => [
        'name'        => '名称',
        'description' => '描述',
        'rules'       => '规则',
        'number'      => '周期',
        'scale'       => '尺度',
        'ratio'       => '比率',
    ],
    'options' => [
    ],
];
