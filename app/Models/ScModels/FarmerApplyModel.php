<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:15 PM
 */

namespace App\Models\ScModels;


class FarmerApplyModel extends ScModel
{
    protected $table = 'farmer_apply';

    const STATUS_UNCOMMIT = 0;
    const STATUS_COMMITED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_PASSEDED = 3;
}