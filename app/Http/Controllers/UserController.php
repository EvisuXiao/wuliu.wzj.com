<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Models\ScModels\UserModel;

class UserController extends Controller
{
    protected $userModel = null;

    public function __construct(UserModel $userModel) {
        parent::__construct();
        $this->userModel = $userModel;
    }

    public function list() {
        return $this->succReturn($this->userModel->getRecList());
    }

    public function opt() {
        if($this->payload['id'] == 1 && $this->payload['type'] == 0) {
            return $this->failReturn('不可禁用超级管理员');
        }
        $upd = $this->userModel->updateRecById($this->payload['id'], ['status' => $this->payload['type']]);
        return $upd ? $this->succReturn() : $this->failReturn();
    }

    public function reset() {
        $this->userModel->updateRecById($this->payload['id'], ['password' => '123456']);
        return $this->succReturn();
    }
}