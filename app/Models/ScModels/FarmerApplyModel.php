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

    const REPAY_UNREPAID = 0;
    const REPAY_REPAID = 1;
    const REPAY_OVERDUE_UNREPAID = 2;
    const REPAY_OVERDUE_REPAID = 3;

    public function getRecList($fields = [DB_SELECT_ALL], $where = [], $order = []) {
        $list = parent::getRecList($fields, $where, $order);
        foreach($list as &$item) {
            if(isset($item['commited_at']) && $item['commited_at'] == DEFAULT_TIMESTAMP) {
                $item['commited_at'] = '';
            }
            if(isset($item['passed_at']) && $item['passed_at'] == DEFAULT_TIMESTAMP) {
                $item['passed_at'] = '';
            }
            if(isset($item['repaid_at']) && $item['repaid_at'] == DEFAULT_TIMESTAMP) {
                $item['repaid_at'] = '';
            }
            if(isset($item['expect_repaid_time']) && $item['expect_repaid_time'] == DEFAULT_TIMESTAMP) {
                $item['expect_repaid_time'] = '';
            }
        }
        return $list;
    }
}