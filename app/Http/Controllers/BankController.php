<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Models\ScModels\FarmerApplyModel;
use App\Repositories\BankRepository;

class BankController extends Controller
{
    protected $bankRepository = null;

    public function __construct(BankRepository $bankRepository) {
        parent::__construct();
        $this->bankRepository = $bankRepository;
    }

    public function list() {
        return $this->succReturn($this->bankRepository->getBankList());
    }

    public function info() {
        return $this->succReturn($this->bankRepository->getBankInfo(self::$uid));
    }

    public function farmerApply() {
        $list = $this->bankRepository->getFarmerApply();
        return $this->succReturn($list);
    }

    public function farmerApplyLabel() {
        $this->label = 'farmerApply';
        return $this->label();
    }

    public function opt() {
        $data = ['status' => $this->payload['type']];
        if($this->payload['type'] == FarmerApplyModel::STATUS_PASSEDED) {
            $info = $this->payload['info'];
            $data['level'] = $info['level'];
            $data['available_amount'] = $data['approval_amount'] = $info['approval_amount'];
            $data['passed_at'] = datetimeNow();
            $data['expect_repaid_time'] = date('Y-m-d 00:00:00', strtotime(sprintf('+%d months', $info['loan_month'])));
        }
        $this->bankRepository->farmerRepository->farmerApplyModel->updateRecById($this->payload['id'], $data);
        return $this->succReturn();
    }
}