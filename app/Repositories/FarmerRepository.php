<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\FarmerApplyModel;
use App\Models\ScModels\FarmerExtendModel;
use App\Models\ScModels\FarmerModel;
use App\Models\ScModels\FarmerWithdrawModel;

class FarmerRepository extends BaseRepository
{
    public $farmerModel = null;
    public $farmerExtendModel = null;
    public $farmerApplyModel = null;
    public $farmerWithdrawModel = null;

    public function __construct(
        FarmerModel $farmerModel,
        FarmerExtendModel $farmerExtendModel,
        FarmerApplyModel $farmerApplyModel,
        FarmerWithdrawModel $farmerWithdrawModel
    ) {
        parent::__construct();
        $this->farmerModel = $farmerModel;
        $this->farmerExtendModel = $farmerExtendModel;
        $this->farmerApplyModel = $farmerApplyModel;
        $this->farmerWithdrawModel = $farmerWithdrawModel;
    }

    public function getFarmerList($id = []) {
        $list = $this->farmerModel->getRecInfoById($id);
        $more = $this->farmerExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }

    public function getFarmerInfo() {
        $info = $this->farmerModel->getRecInfoById(self::$uid);
        $more = $this->farmerExtendModel->getRecInfoById(self::$uid);
        return array_merge($info, $more);
    }

    public function getFarmerApply() {
        $info = $this->farmerApplyModel->getRecInfoById(self::$uid);
        return $info;
    }

    public function doWithdraw($amount, $purpose = '') {
        $rate = 0.04;
        $apply = $this->farmerApplyModel->getRecInfoById(self::$uid);
        $rest = $apply['available_amount'] - $amount;
        if(empty($apply) || $apply['status'] != FarmerApplyModel::STATUS_PASSEDED || $rest < 0) {
            return false;
        }
        $day = floor((strtotime($apply['expect_repaid_time']) - time()) / 86400);
        $interest = round($amount * $rate * $day / 360, 2);
        return $this->farmerWithdrawModel->addWithdraw(self::$uid, $amount, $interest, $purpose)
        && !empty($this->farmerApplyModel->updateRecById(self::$uid, ['available_amount' => $rest]));
    }
}