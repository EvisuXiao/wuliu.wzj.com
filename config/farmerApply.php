<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/6
 * Time: 下午8:04
 */
return [
    'field_title' => [
        'id'                  => 'ID',
        'name'                => '姓名',
        'id_card'             => '身份证号',
        'crop'                => '农作物',
        'company_name'        => '企业名称',
        'company_sales'       => '企业销量(千吨)',
        'company_asset'       => '企业资产(亿元)',
        'company_debt'        => '企业负债(亿元)',
        'farmer_land'         => '土地面积(亩)',
        'farmer_productivity' => '产出能力(千克/亩)',
        'farmer_cost'         => '生产成本(元/亩)',
        'farmer_asset'        => '个人资产(万元)',
        'apply_efficiency'    => '人工效率(亩/人)',
        'apply_quantity'      => '订单数量(吨)',
        'apply_price'         => '单价(元/千克)',
        'apply_purpose'       => '用途',
        'apply_loan_amount'   => '合同金额(元)',
        'apply_loan_month'    => '借款周期(月)',
        'summary'             => '结算金额',
        'status'              => '状态',
        'repaid_status'       => '还款状态',
        'score'               => '信用评分',
        'level'               => '信用评级',
        'approval_amount'     => '审批金额',
        'expect_repaid_time'  => '预计还款时间',
        'repaid_at'           => '实际还款时间',
        'commited_at'         => '提交时间',
        'passed_at'           => '通过时间'
    ],

    'field_attribute' => [
        'width'    => [
            'id'                  => 60,
            'id_card'             => 120,
            'crop'                => 150,
            'company_sales'       => 130,
            'company_asset'       => 130,
            'company_debt'        => 130,
            'farmer_productivity' => 150,
            'farmer_cost'         => 150,
            'farmer_asset'        => 150,
            'apply_efficiency'    => 130,
            'apply_quantity'      => 150,
            'apply_price'         => 150,
            'apply_purpose'       => 150,
            'apply_loan_month'    => 130,
            'repaid_status'       => 130,
            'expect_repaid_time'  => 160,
            'repaid_at'           => 160,
            'commited_at'         => 160,
            'passed_at'           => 160,
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
            'repaid_at'          => true,
            'commited_at'        => true,
            'passed_at'          => true,
        ],
    ]
];