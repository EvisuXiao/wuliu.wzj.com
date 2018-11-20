<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午2:07
 */
namespace App\Models\ScModels;

use App\Models\BaseModel;
use Illuminate\Support\Str;

class ScModel extends BaseModel
{
    protected $connection = 'mysql';
    public $timestamps = false;

    protected $name_field = 'name';


    public function getRecInfo($fields = [DB_SELECT_ALL], $where = [], $order = [], $master = false) {
        $list = $this->getRecList($fields, $where, $order, $master);
        return !empty($list) ? $list[0] : [];
    }

    public function getRecInfoById($id, $fields = [DB_SELECT_ALL]) {
        if(!is_array($id)) {
            return $this->getRecInfo($fields, [$this->primaryKey => $id]);
        }
        return $this->getRecList($fields, ['in' => [$this->primaryKey => $id]]);
    }

    public function getRecList($fields = [DB_SELECT_ALL], $where = [], $order = []) {
        empty($order) && $order = [$this->primaryKey => DB_SORT_ASC];
        $only_field = is_string($fields) && !str_contains($fields, ',');
        $list = $this->getAll($fields, $where, $order);
        if(empty($list)) {
            return [];
        }
        return $only_field ? array_unique(array_column($list, $fields)) : $list;
    }

    public function updateRec($data, $where = []) {
        if(isset($data[$this->primaryKey])) {
            $where[$this->primaryKey] = $data[$this->primaryKey];
            unset($data[$this->primaryKey]);
        }
        $id = $this->getRecList($this->primaryKey, $where);
        if(empty($id)) {
            return 0;
        }
        return $this->updateRecById($id, $data);
    }

    public function updateRecById($id, $data) {
        if(empty($id)) {
            return 0;
        }
        if(isset($data[$this->primaryKey])) {
            unset($data[$this->primaryKey]);
        }
        if(is_array($id)) {
            return $this->updateBy($data, ['in' => [$this->primaryKey => $id]]);
        }
        return $this->updateBy($data, [$this->primaryKey => $id]);
    }

    public function deleteRec($where) {
        $id = $this->getRecList($this->primaryKey, $where);
        if(empty($id)) {
            return 0;
        }
        return $this->deleteRecById($id);
    }

    public function deleteRecById($id) {
        if(empty($id)) {
            return 0;
        }
        if(is_array($id)) {
            return $this->deleteBy(['in' => [$this->primaryKey => $id]], 100);
        }
        return $this->deleteBy([$this->primaryKey => $id]);
    }

    public function getInfoWithIndex($ids, $info_field = []) {
        if(empty($ids)) {
            return [];
        }
        $index = $this->primaryKey;
        $field = [DB_SELECT_ALL];
        if(!empty($info_field)) {
            if(is_array($info_field)) {
                if(!in_array(DB_SELECT_ALL, $info_field)) {
                    $field = array_merge([$index], $info_field);
                }
            } else {
                $field = [$index, $info_field];
            }
        }
        $list = $this->getRecInfoById($ids, $field);
        return !empty($list) ? array_column($list, is_array($info_field) ? null : $info_field, $index) : [];
    }

    protected function nameExist($name, $id = 0) {
        return !empty($this->getRecInfo($this->primaryKey, [sprintf('%s !=', $this->primaryKey) => $id, $this->name_field => $name]));
    }
}