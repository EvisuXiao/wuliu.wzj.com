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

class CompanyRepository extends BaseRepository
{
    public $companyModel = null;
    public $companyExtendModel = null;

    public function __construct(CompanyModel $companyModel, CompanyExtendModel $companyExtendModel, FarmerRepository $farmerRepository) {
        parent::__construct();
        $this->companyModel = $companyModel;
        $this->companyExtendModel = $companyExtendModel;
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
}