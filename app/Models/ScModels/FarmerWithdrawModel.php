<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:15 PM
 */

namespace App\Models\ScModels;


class FarmerWithdrawModel extends ScModel
{
    protected $table = 'farmer_withdraw';

    public function addWithdraw($farmer_id, $amount, $interest, $purpose = '') {
        return $this->addRec(compact('farmer_id', 'amount', 'interest', 'purpose'));
    }
}