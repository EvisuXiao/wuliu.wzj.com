<?php
/**
 * HTTP接口字段验证规则
 * 根据controller,action进行配置
 * 推荐采用RESTful风格的接口形式
 * 如果某字段在不同请求方式下(GET, POST, PUT, DELETE)有不同验证方式, 请用键为请求方式的数组
 * 可用__BASE__引用VALIDATE_RULE_BASE的规则, 如'required|__BASE__'
 * 可用VALIDATE_RULE_BASE指代没有显式配置的规则, 如没有配置PUT, 则默认HTTP_METHOD_PUT => '__BASE__'
 * 某方式下不需验证可以传空字符串
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/30
 * Time: 下午8:04
 */

return [
    'rules'    => [
        'user' => [
            'opt' => [
                'id'   => 'required|integer|min:1',
                'type' => 'required|integer|in:0,1'
            ]
        ],
        'admin' => [
            'register' => [
                'user'  => 'required|array',
                'info'  => 'required|array',
                'extend'  => 'required|array'
            ]
        ]
    ],
    'messages' => [

    ],
    'custom'   => [

    ]
];