<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/6
 * Time: 下午8:04
 */
return [
    'field_title' => [
        'id'                 => 'ID',
        'name'               => '姓名',
        'id_card'            => '身份证号',
        'crop'               => '农作物',
        'land'               => '土地面积(亩)',
        'productivity'       => '产能(千克/亩)',
        'cost'               => '生产成本(元)',
        'asset'              => '个人资产(元)',
        'quantity'           => '订单数量(千克)',
        'price'              => '单价(元/千克)',
        'purpose'            => '用途',
        'loan_amount'        => '合同金额',
        'loan_month'         => '借款周期(月)',
        'status'             => '状态',
        'level'              => '信用评级',
        'approval_amount'    => '审批金额',
        'expect_repaid_time' => '预计还款时间',
        'commited_at'        => '提交时间',
        'passed_at'          => '通过时间',

    ],

    'field_attribute' => [
        'width'    => [
            'id'                 => 60,
            'id_card'            => 120,
            'crop'               => 150,
            'land'               => 150,
            'productivity'       => 150,
            'cost'               => 150,
            'asset'              => 150,
            'quantity'           => 150,
            'price'              => 150,
            'purpose'            => 150,
            'loan_month'         => 130,
            'expect_repaid_time' => 160,
            'commited_at'        => 160,
            'passed_at'          => 160,
        ],
        'sortable' => [
            'id'                 => true,
            'land'               => true,
            'productivity'       => true,
            'cost'               => true,
            'asset'              => true,
            'quantity'           => true,
            'price'              => true,
            'expect_repaid_time' => true,
            'commited_at'        => true,
            'passed_at'          => true,
        ],
    ]
];