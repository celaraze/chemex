<?php

return [
    'labels' => [
        'title'                      => '设备',
        'description'                => '主数据列表',
        'records'                    => '设备',
        'Depreciation Price'         => '折旧价格',
        'Current User'               => '当前使用者',
        'Photo Help'                 => '可以选择提供一张设备的照片作为概览。',
        'Security Password Help'     => '安全密码，可以代表BIOS密码等。',
        'Admin Password Help'        => '管理员密码，可以代表计算机管理员账户密码以及打印机管理员密码等。',
        'Depreciation Rule Help'     => '设备记录的折旧规则将优先于其分类所指定的折旧规则。',
        'Batch Delete'               => '批量删除设备',
        'Batch Delete Confirm'       => '您确定要删除选中的设备吗？',
        'Batch Delete Success'       => '批量删除设备成功！',
        'Delete'                     => '删除设备',
        'Delete Confirm'             => '确认删除？',
        'Delete Confirm Description' => '删除的同时将会解除所有与之关联的归属关系',
        'Update SSH'                 => '编辑SSH连接信息',
        'Track Create Update'        => '分配用户',
        'Maintenance Create'         => '报告故障',
        'Import'                     => '导入',
        'Update SSH Success'         => 'SSH信息配置成功！',
        'Export To Excel'            => '导出到Excel',
        'NG Description'             => '故障描述',
        'Lend Track Create'          => '借用设备',
        'File Help'                  => '导入支持xlsx、csv文件，且表格头必填栏位【名称、分类、厂商】，支持咖啡壶导出的Excel文件直接导入。',
    ],
    'fields' => [
        'qrcode'      => '二维码',
        'name'        => '名称',
        'description' => '描述',
        'category'    => [
            'name' => '分类',
        ],
        'vendor' => [
            'name' => '厂商',
        ],
        'channel' => [
            'name' => '购入途径',
        ],
        'mac'   => 'MAC',
        'ip'    => 'IP',
        'photo' => '照片',
        'user'  => [
            'name'       => '用户',
            'department' => [
                'name' => '部门',
            ],
            'department_id' => '部门',
        ],
        'price'     => '价格',
        'purchased' => '购入日期',
        'expired'   => '过保日期',
        'part'      => [
            'category' => [
                'name' => '分类',
            ],
            'name'          => '名称',
            'specification' => '规格',
            'sn'            => '序列号',
            'vendor'        => [
                'name' => '厂商',
            ],
        ],
        'software' => [
            'category' => [
                'name' => '分类',
            ],
            'name'         => '名称',
            'version'      => '版本',
            'distribution' => '发行方式',
            'vendor'       => [
                'name' => '厂商',
            ],
        ],
        'service' => [
            'name' => '名称',
        ],
        'depreciation' => [
            'name'        => '折旧规则',
            'termination' => '报废日期',
        ],
        'asset_number'         => '资产编号',
        'category_id'          => '分类',
        'vendor_id'            => '厂商',
        'depreciation_id'      => '折旧规则',
        'ng_description'       => '故障说明',
        'ng_time'              => '故障发生时间',
        'user_same'            => '用户已存在',
        'user_id'              => '用户',
        'expiration_left_days' => '保固剩余时间',
        'file'                 => '文件',
        'purchased_channel_id' => '购入途径',
        'depreciation_rule_id' => '折旧规则',
    ],
    'options' => [
    ],
];
