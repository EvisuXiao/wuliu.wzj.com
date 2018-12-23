<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Models\ScModels\FarmerApplyModel;
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
        if(self::isGet()) {
            return $this->succReturn($this->farmerRepository->getFarmerInfo());
        } else {
            $this->farmerRepository->farmerExtendModel->updateRec($this->payload);
            return $this->succReturn();
        }
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
            $info = $this->farmerRepository->farmerApplyModel->getRecInfoById(self::$uid, ['id', 'repaid_status', 'repaid_at']);
            $list = $this->farmerRepository->farmerWithdrawModel->getRecList([DB_SELECT_ALL], ['farmer_id' => self::$uid]);
            $data = [
                'repaid_status' => $info['repaid_status'] ?? 0,
                'repaid_time'     => $info['repaid_at'] ?? '',
                'list'          => $list
            ];
            return $this->succReturn($data);
        } else {
            $do = $this->farmerRepository->doWithdraw($this->payload['amount'], $this->payload['purpose']);
            return $do ? $this->succReturn() : $this->failReturn();
        }
    }

    public function withdrawLabel() {
        $this->label = 'farmerWithdraw';
        return $this->label();
    }
}