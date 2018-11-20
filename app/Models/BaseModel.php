<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午1:58
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function addRec($data) {
        if(empty($data)) {
            return 0;
        }
        if(isset($data[0])) {
            return $this->insert($data);
        }
        return $this->insertGetId($data);
    }

    /**
     * 通用获取单条记录方法
     * @param array   $fields
     * @param array   $where
     * @param array   $order
     * @param array   $group
     * @param boolean $master 类型默认是false 读从库
     * @return array
     */
    public function getOne($fields = [DB_SELECT_ALL], $where = [], $order = [], $group = [], $master = false) {
        $list = $this->getAll($fields, $where, $order, $group, 1, $master);
        return !empty($list) ? $list[0] : [];
    }

    /**
     * 通用查询列表方法,查询全部
     * @param array $fields
     * @param array $where
     * @param array $order
     * @param array $group
     * @param int   $limit
     * @param bool  $master
     * @return array
     */
    public function getAll($fields = [DB_SELECT_ALL], $where = [], $order = [], $group = [], $limit = 0, $master = false) {
        return $this->createQuery($this, $fields, $where, $order, $group, $limit, $master)->get()->toArray();
    }

    /**
     * 查询字段分组数量
     * @param string $field
     * @param array  $where
     * @param string $sort
     * @return array
     */
    public function getGroupNum($field, $where = [], $sort = DB_SORT_ASC) {
        $num_as = 'num';
        $fields['raw'] = sprintf('%s, COUNT(%s) AS %s', $field, $field, $num_as);
        $list = $this->getAll($fields, $where, [$field => $sort], [$field]);
        return !empty($list) ? array_column($list, $num_as, $field) : [];
    }

    /**
     * 查询列表(可分页)
     * @param array $fields
     * @param array $where
     * @param array $order
     * @param array $group
     * @param int   $page_size
     * @param bool  $master
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate($fields = [], $where = [], $order = [], $group = [], $page_size = 15, $master = false) {
        return $this->createQuery($this, $fields, $where, $order, $group, 0, $master)->paginate($page_size);
    }

    /**
     * 根据条件统计总数
     * @param array $where
     * @return int
     */
    public function countBy($where = []) {
        return $this->createWhere($this, $where)->count();
    }

    /**
     * 根据条件更新数据
     * @param array $data
     * @param array $where
     * @param int   $limit
     * @param array $order
     * @return int
     */
    public function updateBy($data, $where, $limit = 0, $order = []) {
        !empty($limit) && empty($order) && $order = [$this->primaryKey => DB_SORT_DESC];
        return $this->createWhere($this, $where, $order, [], $limit)->update($data);
    }

    /**
     * 根据条件删除数据
     * @param array $where
     * @param int   $limit
     * @param array $order
     * @return int
     */
    public function deleteBy($where, $limit = 0, $order = []) {
        !empty($limit) && empty($order) && $order = [$this->primaryKey => DB_SORT_DESC];
        return $this->createWhere($this, $where, $order, [], $limit)->delete();
    }

    /**
     * @param Model $query
     * @param array $fields
     * @param array $where
     * @param array $order
     * @param array $group
     * @param int   $limit
     * @param bool  $master
     * @return Model
     */
    public function createQuery($query = null, $fields = [DB_SELECT_ALL], $where = [], $order = [], $group = [], $limit = 0, $master = false) {
        is_null($query) && $query = $this;
        if(isset($fields['raw'])) {
            $query = $this->selectRaw($fields['raw']);
            $fields = $fields['fields'] ?? [];
        }
        !empty($fields) && $query = $this->select($fields);
        // 是否读主库
        $master && $query->useWritePdo();
        return $this->createWhere($query, $where, $order, $group, $limit);
    }

    /**
     * 格式化where条件
     * @param Model $query
     * @param array $where
     * @param array $order
     * @param array $group
     * @param int   $limit
     * @return Model
     */
    public function createWhere($query = null, $where = [], $order = [], $group = [], $limit = 0) {
        is_null($query) && $query = $this;
        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $query = $query->whereIn($k, $v);
            }
            unset($where['in']);
        }
        if(isset($where['not_in'])) {
            foreach($where['not_in'] as $k => $v) {
                $query = $query->whereNotIn($k, $v);
            }
            unset($where['not_in']);
        }
        if(isset($where['raw'])) {
            foreach($where['raw'] as $k => $v) {
                $query = $query->whereRaw($v);
            }
            unset($where['raw']);
        }
        if(!empty($where)) {
            foreach($where as $k => $v) {
                $operator = '=';
                if(substr($k, -2) == ' <') {
                    $k = trim(str_replace(' <', '', $k));
                    $operator = '<';
                } elseif(substr($k, -3) == ' <=') {
                    $k = trim(str_replace(' <=', '', $k));
                    $operator = '<=';
                } elseif(substr($k, -2) == ' >') {
                    $k = trim(str_replace(' >', '', $k));
                    $operator = '>';
                } elseif(substr($k, -3) == ' >=') {
                    $k = trim(str_replace(' >=', '', $k));
                    $operator = '>=';
                } elseif(substr($k, -3) == ' !=') {
                    $k = trim(str_replace(' !=', '', $k));
                    $operator = '!=';
                } elseif(substr($k, -3) == ' <>') {
                    $k = trim(str_replace(' <>', '', $k));
                    $operator = '<>';
                } elseif(substr($k, -5) == ' like') {
                    $k = trim(str_replace(' like', '', $k));
                    $operator = 'like';
                    $v = '%' . $v . '%';
                }
                $query = $query->where($k, $operator, $v);
            }
        }
        if(!empty($order)) {
            foreach($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }
        if(!empty($group)) {
            foreach($group as $v) {
                $query = $query->groupBy($v);
            }
        }
        !empty($limit) && $query = $query->limit($limit);
        return $query;
    }
}