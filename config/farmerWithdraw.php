<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/6
 * Time: 下午8:04
 */
return [
    'field_title' => [
        'id'             => 'ID',
        'amount'         => '金额',
        'purpose'        => '用途',
        'interest'       => '利息',
        'repaid_at'      => '还款日',
        'status'         => '状态',
        'created_at'     => '提款时间'
    ],

    'field_attribute' => [
        'width'    => [
            'id'             => 60,
            'purpose'        => 180,
            'repaid_at'      => 160,
            'created_at'     => 160
        ],
        'sortable' => [
            'id'             => true,
            'amount'         => true,
            'interest'       => true,
            'repaid_at'      => true,
            'status'         => true,
            'created_at'     => true
        ],
    ]
];