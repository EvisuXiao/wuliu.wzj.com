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

    const STATUS_UNREPAID = 0;
    const STATUS_REPAID = 1;
    const STATUS_OVERDUE_UNREPAID = 2;
    const STATUS_OVERDUE_REPAID = 3;

    public function addWithdraw($farmer_id, $amount, $interest, $purpose = '') {
        $status = self::STATUS_UNREPAID;
        return $this->addRec(compact('farmer_id', 'amount', 'interest', 'purpose', 'status'));
    }

    public function getRecList($fields = [DB_SELECT_ALL], $where = [], $order = []) {
        $list = parent::getRecList($fields, $where, $order);
        foreach($list as &$item) {
            if(isset($item['repaid_at']) && $item['repaid_at'] == DEFAULT_TIMESTAMP) {
                $item['repaid_at'] = '';
            }
        }
        return $list;
    }
}