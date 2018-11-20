<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:15 PM
 */

namespace App\Models\ScModels;


class UserModel extends ScModel
{
    const STATUS_INVALID = 0;
    const STATUS_VALID = 1;
    const ROLE_ADMIN = 1;
    const ROLE_COMPANY = 2;
    const ROLE_BANK = 3;
    const ROLE_FARMER = 4;
    const ROLE_DEALER = 5;

    protected static $role_name = [
        self::ROLE_ADMIN   => 'admin',
        self::ROLE_COMPANY => 'company',
        self::ROLE_BANK    => 'bank',
        self::ROLE_FARMER  => 'farmer',
        self::ROLE_DEALER  => 'dealer'
    ];

    protected $name_field = 'username';
    protected $table = 'user';

    public function addUser($username, $password, $role, $email) {
        if($this->nameExist($username)) {
            throw new \Exception('用户名已使用');
        }
        $password = md5($password);
        $status = self::STATUS_VALID;
        return $this->addRec(compact('username', 'password', 'role', 'email', 'status'));
    }

    public function getLoginInfoByName($username) {
        return $this->getRecInfo(['id', 'username', 'password', 'role', 'email', 'status', 'logged_at'], compact('username'));
    }

    public function updateRecById($id, $data) {
        if(isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        return parent::updateRecById($id, $data);
    }

    public static function getRoleName($role) {
        return self::$role_name[$role] ?? '';
    }
}