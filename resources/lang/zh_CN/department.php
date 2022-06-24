<?php

return [
    'labels' => [
        'title' => '组织',
        'description' => '区分用户的不同群体',
        'User' => '用户',
        'Department' => '部门',
        'Role' => '角色',
        'Permission' => '权限',
        'departments' => '部门',
        'Import' => '导入',
        'File Help' => '导入支持xlsx、csv文件，且表格头必填栏位【名称】，可选栏位【描述、父级部门】。',
        'Rewrite' => '复写',
        'Merge' => '合并',
        'File' => '文件',
        'LDAP' => 'LDAP',
    ],
    'fields' => [
        'name' => '名称',
        'description' => '描述',
        'parent' => [
            'name' => '父级部门',
        ],
        'mode' => '模式',
        'file' => '文件',
        'parent_id' => '父级部门',
        'role_id' => '部门主管'
    ],
    'options' => [
    ],
];
