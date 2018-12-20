<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;

use App\Models\ScModels\UserModel;

class AdminRepository extends BaseRepository
{
    const COOKIE_USER = 'userInfo';

    protected static $uid = 0;
    protected static $role = 0;
    protected static $user_info = [];

    protected $userModel = null;

    public function __construct(UserModel $userModel) {
        parent::__construct();
        $this->userModel = $userModel;
    }

    public function register($user, $info = [], $extend = []) {
        $role = $user['role'];
        $id = $this->userModel->addUser($user['username'], $user['password'], $role, $user['email']);
        if($role != UserModel::ROLE_ADMIN) {
            if(!empty($info)) {
                $info['id'] = $id;
                $this->userModel->setTable(UserModel::getRoleName($role));
                $this->userModel->addRec($info);
                if(!empty($extend)) {
                    $extend['id'] = $id;
                    $this->userModel->setTable(UserModel::getRoleName($role) . '_extend');
                    $this->userModel->addRec($extend);
                }
                $this->userModel->setTable('user');
            }
        }
    }

    /**
     * 获取用户登录信息
     * @return array
     */
    public static function getLoginInfo() {
        if(empty(self::$user_info)) {
            self::$user_info = isset($_COOKIE[self::COOKIE_USER]) ? json_decode($_COOKIE[self::COOKIE_USER], true) : [];
            !empty(self::$user_info) && self::$uid = self::$user_info['id'];
        }
        return self::$user_info ?: [];
    }

    /**
     * 用户是否登录
     * @return bool
     */
    public static function isLogin() {
        return !empty(self::getLoginInfo());
    }

    /**
     * 获取登录用户ID
     * @return int
     */
    public static function getLoginUid() {
        if(empty(self::$uid)) {
            $info = self::getLoginInfo();
            self::$uid = $info['id'] ?? 0;
        }
        return self::$uid;
    }

    public static function getRole() {
        if(empty(self::$role)) {
            $info = self::getLoginInfo();
            self::$role = $info['role'] ?? 0;
        }
        return self::$role;
    }

    public function login($username, $password) {
        if(!self::isLogin()) {
            $info = $this->userModel->getLoginInfoByName($username);
            if(empty($info)) {
                throw new \Exception('该用户不存在');
            }
            if($info['status'] == UserModel::STATUS_INVALID) {
                throw new \Exception('该用户已被禁用');
            }
            if($info['password'] != md5($password)) {
                throw new \Exception('该用户密码有误');
            }
            unset($info['password'], $info['status']);
            setcookie(self::COOKIE_USER, json_encode($info), time() + 3600, '/');
            $this->userModel->updateRecById($info['id'], ['logged_at' => datetimeNow()]);
            return $info;
        }
        return self::getLoginInfo();
    }

    public static function logout() {
        self::isLogin() && setcookie(self::COOKIE_USER, '', time() - 60, '/');
    }
}