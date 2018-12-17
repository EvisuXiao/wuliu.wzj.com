<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:15 PM
 */

namespace App\Models\ScModels;


class BankScoreCfgModel extends ScModel
{
    protected $table = 'bank_score_cfg';

    const TYPE_MORE = 1;
    const TYPE_LESS = 2;

    public static $score_type = [
        'company_sales'       => self::TYPE_MORE,
        'company_asset'       => self::TYPE_MORE,
        'company_debt'        => self::TYPE_LESS,
        'farmer_land'         => self::TYPE_MORE,
        'farmer_productivity' => self::TYPE_MORE,
        'farmer_asset'        => self::TYPE_MORE,
        'apply_efficiency'    => self::TYPE_MORE,
        'apply_quantity'      => self::TYPE_LESS,
        'apply_price'         => self::TYPE_MORE
    ];
}