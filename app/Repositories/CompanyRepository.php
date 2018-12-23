<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\CompanyExtendModel;
use App\Models\ScModels\CompanyModel;
use App\Models\ScModels\FarmerApplyModel;

class CompanyRepository extends BaseRepository
{
    public $companyModel = null;
    public $companyExtendModel = null;
    public $farmerRepository = null;

    public function __construct(CompanyModel $companyModel, CompanyExtendModel $companyExtendModel, FarmerRepository $farmerRepository) {
        parent::__construct();
        $this->companyModel = $companyModel;
        $this->companyExtendModel = $companyExtendModel;
        $this->farmerRepository = $farmerRepository;
    }

    public function getCompanyList() {
        $list = $this->companyModel->getRecList();
        $more = $this->companyExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }

    public function getCompanyInfo($id) {
        $info = $this->companyModel->getRecInfoById($id);
        $more = $this->companyExtendModel->getRecInfoById($id);
        return array_merge($info, $more);
    }

    public function getFarmerList() {
        $apply = $this->farmerRepository->farmerApplyModel->getRecList(['id', 'loan_amount', 'repaid_status'], ['company_id' => self::$uid, 'status' => FarmerApplyModel::STATUS_PASSEDED]);
        if(empty($apply)) {
            return [];
        }
        $farmer = $this->farmerRepository->getFarmerList(array_column($apply,'id'));
        $apply = array_column($apply, null, 'id');
        foreach($farmer as &$item) {
            $item['repaid_status'] = $apply[$item['id']]['repaid_status'];
            $item['loan_amount'] = $apply[$item['id']]['loan_amount'];
        }
        return $farmer;
    }

    public function repay($id, $type = FarmerApplyModel::REPAY_REPAID) {
        $data = [
            'repaid_status' => $type,
            'repaid_at'     => datetimeNow()
        ];
        $this->farmerRepository->farmerApplyModel->updateRecById($id, $data);
    }
}