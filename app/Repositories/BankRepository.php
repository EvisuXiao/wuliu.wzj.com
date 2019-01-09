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

    /**
     * 获取银行列表
     * @return array
     */
    public function getBankList() {
        // 基本信息
        $list = $this->bankModel->getRecList();
        // 经营信息
        $more = $this->bankExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }

    /**
     * 获取银行信息
     * @param $id
     * @return array
     */
    public function getBankInfo($id) {
        $info = $this->bankModel->getRecInfoById($id);
        $more = $this->bankExtendModel->getRecInfoById($id);
        return array_merge($info, $more);
    }

    /**
     * 获取农户订单
     * @param int $farmer_id
     * @return array
     */
    public function getFarmerApply($farmer_id = 0) {
        $where = ['bank_id' => self::$uid];
        !empty($farmer_id) && $where['id'] = $farmer_id;
        $list = $this->farmerRepository->farmerApplyModel->getRecList([DB_SELECT_ALL], $where);
        if(empty($list)) {
            return [];
        }
        $data = [];
        // 集成农户基本信息, 经营信息, 订单信息
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
                'summary'             => $item['summary'] == 0.00 ? 0 : $item['summary'],
                'status'              => $item['status'],
                'repaid_status'       => $item['repaid_status'],
                'score'               => $item['score'],
                'level'               => $item['level'],
                'approval_amount'     => $item['approval_amount'],
                'expect_repaid_time'  => $item['expect_repaid_time'],
                'repaid_at'           => $item['repaid_at'],
                'commited_at'         => $item['commited_at'],
                'passed_at'           => $item['passed_at'],
            ];
        }
        return !empty($farmer_id) ? $data[0] : $data;
    }

    /**
     * 银行为农户结算金额
     * @param $id
     * @return float
     */
    public function summary($id) {
        // 合同金额
        $loan_amount = $this->farmerRepository->farmerApplyModel->getRecInfoById($id, 'loan_amount');
        // 提款信息
        $withdraw = $this->farmerRepository->farmerWithdrawModel->getRecList(['id', 'amount', 'interest'], ['farmer_id' => $id]);
        $total = 0;
        foreach($withdraw as $item) {
            // 提款本息合计
            $total += ($item['amount'] + $item['interest']);
        }
        // 农户结余
        $summary = $loan_amount - $total;
        $this->farmerRepository->farmerApplyModel->updateRecById($id, ['summary' => $summary]);
        return $summary;
    }

    /**
     * 银行打分配置
     * @return array
     */
    public function getScoreCfg() {
        $info = $this->bankScoreCfgModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            return $info;
        }
        $this->bankScoreCfgModel->addRec(['id' => self::$uid]);
        return $this->bankScoreCfgModel->getRecInfoById(self::$uid);
    }

    /**
     * 获取当前总分权重
     * @return float
     */
    public function getAvgScore() {
        $score = array_values(array_only($this->getScore(), array_keys(BankScoreCfgModel::$score_type)));
        return round(array_sum($score), 5);
    }

    /**
     * 评分对应评级
     * @param $score
     * @return string
     */
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

    /**
     * 获取各项打分当前权重
     * @return array
     */
    public function getScore() {
        $info = $this->bankScoreModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            return $info;
        }
        $this->bankScoreModel->addRec(['id' => self::$uid]);
        return $this->bankScoreModel->getRecInfoById(self::$uid);
    }

    /**
     * 更新分数权重
     * @param $data
     */
    protected function updateScore($data) {
        $info = $this->bankScoreModel->getRecInfoById(self::$uid);
        if(!empty($info)) {
            $this->bankScoreModel->updateRecById(self::$uid, $data);
        } else {
            $data['id'] = self::$uid;
            $this->bankScoreModel->addRec($data);
        }
    }

    /**
     * 打分池操作
     * 归一化运算
     * @param array $data 新一组评分
     */
    public function scorePool($data) {
        $serial = 0;
        $max = [];
        $min = [];
        $score = [];
        // 历史打分池
        $list = $this->bankScorePoolModel->getRecList([DB_SELECT_ALL], ['bank_id' => self::$uid], ['serial' => DB_SORT_ASC, 'id' => DB_SORT_ASC]);
        foreach($list as $item) {
            // 历史最大录入次序
            $serial = max($serial, $item['serial']);
            // 遍历打分项
            foreach($item as $key => $value) {
                if(in_array($key, array_keys(BankScoreCfgModel::$score_type))) {
                    // 该项历史最大值
                    if(!isset($max[$key])) {
                        $max[$key] = $value;
                    } else {
                        $max[$key] = max($value, $max[$key]);
                    }
                    // 该项历史最小值
                    if(!isset($min[$key])) {
                        $min[$key] = $value;
                    } else {
                        $min[$key] = min($value, $min[$key]);
                    }
                }
            }
        }
        // 银行打分基础权重
        $cfg = $this->getScoreCfg();
        // 新一组权重代入打分池
        foreach($data as $key => $value) {
            if(!isset($max[$key]) || !isset($min[$key])) {
                $score[$key] = 1;
            } else {
                // 该项当前最大值
                $cur_max = max($max[$key], $value);
                // 该项当前最小值
                $cur_min = min($min[$key], $value);
                if($cur_max == $cur_min) {
                    $score[$key] = 1;
                }
                if(BankScoreCfgModel::$score_type[$key] == BankScoreCfgModel::TYPE_MORE) {
                    // 该项如果是值越大越好, 采用(当前值 - 最小值) / (最大值 - 最小值)
                    $score[$key] = ($value - $cur_min) / ($cur_max - $cur_min);
                } else {
                    // 该项如果是值越小越好, 采用(最大值 - 当前值) / (最大值 - 最小值)
                    $score[$key] = ($cur_max - $value) / ($cur_max - $cur_min);
                }
            }
            // 结果乘基础权重
            $score[$key] = round($score[$key] * $cfg[$key], 5);
        }
        // 新一组打分数据存入打分池
        $data['bank_id'] = self::$uid;
        $data['serial'] = $serial + 1;
        $this->bankScorePoolModel->addRec($data);
        // 更新权重终值
        $this->updateScore($score);
    }
}