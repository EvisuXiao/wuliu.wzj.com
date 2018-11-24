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
        'repayment_date' => '还款日',
        'status'         => '状态',
        'created_at'     => '提款时间'
    ],

    'field_attribute' => [
        'width'    => [
            'id'             => 60,
            'purpose'        => 180,
            'repayment_date' => 160,
            'created_at'     => 160
        ],
        'sortable' => [
            'id'             => true,
            'amount'         => true,
            'interest'       => true,
            'repayment_date' => true,
            'status'         => true,
            'created_at'     => true
        ],
    ]
];