<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/9/6
 * Time: 下午8:04
 */
return [
    'field_title' => [
        'id'           => 'ID',
        'name'         => '姓名',
        'id_card'      => '身份证号',
        'crop'         => '农作物',
        'land'         => '土地面积(亩)',
        'price'        => '单价(元/千克)',
        'productivity' => '产能(千克/亩)',
        'cost'         => '生产成本(元)',
        'asset'        => '个人资产(元)'
    ],

    'field_attribute' => [
        'width'    => [
            'id'           => 60,
            'id_card'      => 120,
            'crop'         => 150,
            'land'         => 150,
            'price'        => 150,
            'productivity' => 150,
            'cost'         => 150,
            'asset'        => 150
        ],
        'sortable' => [
            'id'           => true,
            'land'         => true,
            'price'        => true,
            'productivity' => true,
            'cost'         => true,
            'asset'        => true
        ],
    ]
];