<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/6
 * Time: 下午8:04
 */

return [
    'field_title' => [
        'id'         => 'ID',
        'username'   => '用户名',
        'role'       => '角色',
        'email'      => '邮箱',
        'status'     => '状态',
        'logged_at'  => '上次登录时间',
        'created_at' => '创建时间',
        'updated_at' => '修改时间'
    ],

    'field_attribute' => [
        'width'    => [
            'id'         => 60,
            'email'      => 160,
            'logged_at'  => 160,
            'created_at' => 160,
            'updated_at' => 160,
        ],
        'sortable' => [
            'id'         => true,
            'status'     => true,
            'created_at' => true,
            'updated_at' => true
        ],
    ]
];