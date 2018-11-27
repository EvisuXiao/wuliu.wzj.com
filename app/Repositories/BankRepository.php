<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\BankExtendModel;
use App\Models\ScModels\BankModel;
use App\Models\ScModels\FarmerApplyModel;

class BankRepository extends BaseRepository
{
    public $bankModel = null;
    public $bankExtendModel = null;
    public $farmerRepository = null;

    public function __construct(BankModel $bankModel, BankExtendModel $bankExtendModel, FarmerRepository $farmerRepository) {
        parent::__construct();
        $this->bankModel = $bankModel;
        $this->bankExtendModel = $bankExtendModel;
        $this->farmerRepository = $farmerRepository;
    }

    public function getBankList() {
        $list = $this->bankModel->getRecList();
        $more = $this->bankExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }

    public function getBankInfo($id) {
        $info = $this->bankModel->getRecInfoById($id);
        $more = $this->bankExtendModel->getRecInfoById($id);
        return array_merge($info, $more);
    }

    public function getFarmerApply() {
        $list = $this->farmerRepository->farmerApplyModel->getRecList([DB_SELECT_ALL], ['bank_id' => self::$uid]);
        if(empty($list)) {
            return [];
        }
        $more = $this->farmerRepository->getFarmerList(array_column($list, 'id'));
        $more = array_column($more, null, 'id');
        $data = [];
        foreach($list as $item) {
            if(!in_array($item['status'], [FarmerApplyModel::STATUS_COMMITED, FarmerApplyModel::STATUS_PASSEDED])) {
                continue;
            }
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }
}