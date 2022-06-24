<?php

return [
    'labels' => [
        'title' => '设备',
        'description' => '主数据列表',
        'records' => '设备',
        'Depreciation Price' => '折旧价格',
        'Current User' => '当前使用者',
        'Photo Help' => '可以选择提供一张设备的照片作为概览。',
        'Security Password Help' => '安全密码，可以代表BIOS密码等。',
        'Admin Password Help' => '管理员密码，可以代表计算机管理员账户密码以及打印机管理员密码等。',
        'Depreciation Rule Help' => '设备折旧规所指定的折旧规则。',
        'Batch Delete' => '批量删除设备',
        'Batch Force Delete' => '批量强制删除设备（不可恢复）',
        'Batch Force Delete Confirm' => '您确定要强制删除选中的设备吗？（此操作不可逆）',
        'Batch Delete Confirm' => '您确定要删除选中的设备吗？',
        'Delete' => '删除设备',
        'Delete Confirm' => '确认删除设备',
        'Delete Confirm Description' => '删除设备将会同时删除所有与之关联的信息',
        'Track Create Update' => '分配用户',
        'Batch Track Create Update' => '批量分配用户',
        'Maintenance Create' => '报告故障',
        'Import' => '导入设备模板',
        'Export To Excel' => '导出到Excel',
        'NG Description' => '故障描述',
        'Lend Track Create' => '借用设备',
        'File Help' => '导入支持xlsx、csv文件，且表格头必填栏位【资产编号、分类、厂商】，支持咖啡壶导出的Excel文件直接导入。',
        'Deleted' => '回收站',
        'Track Delete' => '解除归属',
        'Track Delete Confirm' => '确认解除与此用户的关联？',
        'No User' => '闲置'
    ],
    'fields' => [
        'asset_number_qrcode' => '二维码',
        'description' => '描述',
        'category' => [
            'name' => '分类',
        ],
        'vendor' => [
            'name' => '厂商',
        ],
        'mac' => 'MAC',
        'ip' => 'IP',
        'photo' => '照片',
        'admin_user' => [
            'name' => '用户',
            'department' => [
                'name' => '部门',
            ],
            'department_id' => '部门',
        ],
        'price' => '价格',
        'purchased' => '购入日期',
        'expired' => '过保日期',
        'part' => [
            'category' => [
                'name' => '分类',
            ],
            'asset_number' => '名称',
            'specification' => '规格',
            'sn' => '序列号',
            'vendor' => [
                'name' => '厂商',
            ],
        ],
        'software' => [
            'category' => [
                'name' => '分类',
            ],
            'name' => '名称',
            'version' => '版本',
            'distribution' => '发行方式',
            'vendor' => [
                'name' => '厂商',
            ],
        ],
        'service' => [
            'name' => '名称',
        ],
        'depreciation' => [
            'name' => '折旧规则',
            'termination' => '报废日期',
        ],
        'asset_number' => '资产编号',
        'category_id' => '分类',
        'vendor_id' => '厂商',
        'depreciation_id' => '折旧规则',
        'ng_description' => '故障说明',
        'ng_time' => '故障发生时间',
        'user_same' => '用户已存在',
        'user_id' => '用户',
        'expiration_left_days' => '保固剩余时间',
        'depreciation_price' => '折旧后价格',
        'file' => '文件',
        'depreciation_rule_id' => '折旧规则',
        'device_status' => '设备状态',
        'no_user' => '闲置'
    ],
    'options' => [
    ],
];
