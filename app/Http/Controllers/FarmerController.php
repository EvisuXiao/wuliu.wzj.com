<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Models\ScModels\FarmerApplyModel;
use App\Repositories\AdminRepository;
use App\Repositories\FarmerRepository;

class FarmerController extends Controller
{
    protected $farmerRepository = null;

    public function __construct(FarmerRepository $farmerRepository) {
        parent::__construct();
        $this->farmerRepository = $farmerRepository;
    }

    public function list() {
        return $this->succReturn($this->farmerRepository->getFarmerList());
    }

    public function info() {
        return $this->succReturn($this->farmerRepository->getFarmerInfo());
    }
    
    public function apply() {
        if(self::isGet()) {
            return $this->succReturn($this->farmerRepository->getFarmerApply());
        } else {
            $data = $this->payload;
            unset($data['rest_amount'], $data['level'], $data['approval_amount'],
                $data['available_amount'], $data['expect_repaid_time'], $data['passed_at']);
            unset($data['available_amount']);
            $data['id'] = self::$uid;
            $data['commited_at'] = datetimeNow();
            $data['status'] = FarmerApplyModel::STATUS_COMMITED;
            self::isPost() ? $this->farmerRepository->farmerApplyModel->addRec($data) : $this->farmerRepository->farmerApplyModel->updateRec($data);
        }
        return $this->succReturn();
    }

    public function withdraw() {
        if(self::isGet()) {
            $list = $this->farmerRepository->farmerWithdrawModel->getRecList([DB_SELECT_ALL], ['farmer_id' => self::$uid]);
            return $this->succReturn($list);
        } else {
            $do = $this->farmerRepository->doWithdraw($this->payload['amount'], $this->payload['purpose']);
            return $do ? $this->succReturn() : $this->failReturn();
        }
    }

    public function withdrawLabel() {
        $this->label = 'farmerWithdraw';
        return $this->label();
    }

    public function repay() {
        $data = [
            'status'    => $this->payload['type'],
            'repaid_at' => datetimeNow()
        ];
        $this->farmerRepository->farmerWithdrawModel->updateRecById($this->payload['id'], $data);
        return $this->succReturn();
    }
}