<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\DealerExtendModel;
use App\Models\ScModels\DealerModel;

class DealerRepository extends BaseRepository
{
    protected $dealerModel = null;
    protected $dealerExtendModel = null;

    public function __construct(DealerModel $dealerModel, DealerExtendModel $dealerExtendModel) {
        $this->dealerModel = $dealerModel;
        $this->dealerExtendModel = $dealerExtendModel;
    }

    public function getDealerList() {
        $list = $this->dealerModel->getRecList();
        $more = $this->dealerExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }

    public function getDealerInfo($id) {
        $info = $this->dealerModel->getRecInfoById($id);
        $more = $this->dealerExtendModel->getRecInfoById($id);
        return array_merge($info, $more);
    }
}