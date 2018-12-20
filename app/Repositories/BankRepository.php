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
use App\Models\ScModels\BankScoreCfgModel;
use App\Models\ScModels\BankScoreModel;
use App\Models\ScModels\BankScorePoolModel;
use App\Models\ScModels\FarmerApplyModel;

class BankRepository extends BaseRepository
{
    public $bankModel = null;
    public $bankExtendModel = null;
    public $bankScoreModel = null;
    public $bankScorePoolModel = null;
    public $bankScoreCfgModel = null;
    public $farmerRepository = null;
    public $companyRepository = null;

    public function __construct(
        BankModel $bankModel,
        BankExtendModel $bankExtendModel,
        BankScoreModel $bankScoreModel,
        BankScorePoolModel $bankScorePoolModel,
        BankScoreCfgModel $bankScoreCfgModel,
        FarmerRepository $farmerRepository,
        CompanyRepository $companyRepository
    ) {
        parent::__construct();
        $this->bankModel = $bankModel;
        $this->bankExtendModel = $bankExtendModel;
        $this->bankScoreModel = $bankScoreModel;
        $this->bankScorePoolModel = $bankScorePoolModel;
        $this->bankScoreCfgModel = $bankScoreCfgModel;
        $this->farmerRepository = $farmerRepository;
        $this->companyRepository = $companyRepository;
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

    public function getFarmerApply($farmer_id = 0) {
        $where = ['bank_id' => self::$uid];
        !empty($farmer_id) && $where['id'] = $farmer_id;
        $list = $this->farmerRepository->farmerApplyModel->getRecList([DB_SELECT_ALL], $where);
        if(empty($list)) {
            return [];
        }
        $data = [];
        foreach($list as $item) {
            if(!in_array($item['status'], [FarmerApplyModel::STATUS_COMMITED, FarmerApplyModel::STATUS_PASSEDED])) {
                continue;
            }
            $farmer = $this->farmerRepository->getFarmerInfo($item['id']);
            $company = $this->companyRepository->getCompanyInfo($item['company_id']);
            $data[] = [
                'id'                  => $farmer['id'],
                'name'                => $farmer['name'],
                'id_card'             => $farmer['id_card'],
                'crop'                => $farmer['crop'],
                'company_id'          => $company['id'],
                'company_name'        => $company['name'],
                'company_sales'       => $company['sales'],
                'company_asset'       => $company['asset'],
                'company_debt'        => $company['debt'],
                'farmer_land'         => $farmer['land'],
                'farmer_productivity' => $farmer['productivity'],
                'farmer_cost'         => $farmer['cost'],
                'farmer_asset'        => $farmer['asset'],
                'apply_efficiency'    => $item['efficiency'],
                'apply_quantity'      => $item['quantity'],
                'apply_price'         => $item['price'],
                'apply_purpose'       => $item['purpose'],
                'apply_loan_amount'   => $item['loan_amount'],
                'apply_loan_month'    => $item['loan_month'],
                'status'              => $item['status'],
                'level'               => $item['level'],
                'approval_amount'     => $item['approval_amount'],
                'expect_repaid_time'  => $item['expect_repaid_time'],
                'commited_at'         => $item['commited_at'],
                'passed_at'           => $item['passed_at'],
            ];
        }
        return !empty($farmer_id) ? $data[0] : $data;
    }

    public function getScoreCfg() {
        $info = $this->bankScoreCfgModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            return $info;
        }
        $this->bankScoreCfgModel->addRec(['id' => self::$uid]);
        return $this->bankScoreCfgModel->getRecInfoById(self::$uid);
    }

    public function getAvgScore() {
        $score = array_values(array_only($this->getScore(), array_keys(BankScoreCfgModel::$score_type)));
        return round(array_sum($score) / count($score), 5);
    }

    public function getLevel($score) {
        if($score >= 0.85) {
            $level = 'A';
        } elseif($score >= 0.7) {
            $level = 'B';
        } elseif($score >= 0.6) {
            $level = 'C';
        } else {
            $level = 'D';
        }
        return $level;
    }

    public function getScore() {
        $info = $this->bankScoreModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            return $info;
        }
        $this->bankScoreModel->addRec(['id' => self::$uid]);
        return $this->bankScoreModel->getRecInfoById(self::$uid);
    }

    protected function updateScore($data) {
        $info = $this->bankScoreModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            $this->bankScoreModel->updateRecById(self::$uid, $data);
        } else {
            $data['id'] = self::$uid;
            $this->bankScoreModel->addRec($data);
        }
    }

    public function scorePool($data) {
        $serial = 0;
        $max = [];
        $min = [];
        $score = [];
        $list = $this->bankScorePoolModel->getRecList([DB_SELECT_ALL], ['bank_id' => self::$uid], ['serial' => DB_SORT_ASC, 'id' => DB_SORT_ASC]);
        foreach($list as $item) {
            $serial = max($serial, $item['serial']);
            foreach($item as $key => $value) {
                if(in_array($key, array_keys(BankScoreCfgModel::$score_type))) {
                    if(!isset($max[$key])) {
                        $max[$key] = $value;
                    } else {
                        $max[$key] = max($value, $max[$key]);
                    }
                    if(!isset($min[$key])) {
                        $min[$key] = $value;
                    } else {
                        $min[$key] = min($value, $min[$key]);
                    }
                }
            }
        }
        $cfg = $this->getScoreCfg();
        foreach($data as $key => $value) {
            if(!isset($max[$key]) || !isset($min[$key])) {
                $score[$key] = 1;
            } else {
                $cur_max = max($max[$key], $value);
                $cur_min = min($min[$key], $value);
                if($cur_max == $cur_min) {
                    $score[$key] = 1;
                }
                if(BankScoreCfgModel::$score_type[$key] == BankScoreCfgModel::TYPE_MORE) {
                    $score[$key] = ($value - $cur_min) / ($cur_max - $cur_min);
                } else {
                    $score[$key] = ($cur_max - $value) / ($cur_max - $cur_min);
                }
            }
            $score[$key] = round($score[$key] * $cfg[$key], 5);
        }
        $data['bank_id'] = self::$uid;
        $data['serial'] = $serial + 1;
        $this->bankScorePoolModel->addRec($data);
        $this->updateScore($score);
    }
}