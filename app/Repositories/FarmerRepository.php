<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\FarmerExtendModel;
use App\Models\ScModels\FarmerModel;

class FarmerRepository extends BaseRepository
{
    protected $farmerModel = null;
    protected $farmerExtendModel = null;

    public function __construct(FarmerModel $farmerModel, FarmerExtendModel $farmerExtendModel) {
        $this->farmerModel = $farmerModel;
        $this->farmerExtendModel = $farmerExtendModel;
    }

    public function getFarmerList() {
        $list = $this->farmerModel->getRecList();
        $more = $this->farmerExtendModel->getInfoWithIndex(array_column($list, 'id'));
        $data = [];
        foreach($list as $item) {
            if(isset($more[$item['id']])) {
                $data[] = array_merge($item, $more[$item['id']]);
            }
        }
        return $data;
    }
}