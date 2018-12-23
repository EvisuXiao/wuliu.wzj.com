<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Models\ScModels\FarmerApplyModel;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    protected $companyRepository = null;

    public function __construct(CompanyRepository $companyRepository) {
        parent::__construct();
        $this->companyRepository = $companyRepository;
    }

    public function list() {
        return $this->succReturn($this->companyRepository->getCompanyList());
    }

    public function farmer() {
        return $this->succReturn($this->companyRepository->getFarmerList());
    }

    public function info($id = 0) {
        $id = $id ?: self::$uid;
        if(self::isGet()) {
            return $this->succReturn($this->companyRepository->getCompanyInfo($id));
        } else {
            $this->companyRepository->companyExtendModel->updateRec($this->payload);
            return $this->succReturn();
        }
    }

    public function repay() {
        $this->companyRepository->repay($this->payload['id'], $this->payload['opt']);
        return $this->succReturn();
    }
}