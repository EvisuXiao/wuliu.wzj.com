<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Repositories\AdminRepository;

class AdminController extends Controller
{
    protected $adminRepository = null;

    public function __construct(AdminRepository $adminRepository) {
        $this->adminRepository = $adminRepository;
        parent::__construct();
    }

    public function register() {
        try {
            $this->adminRepository->register($this->payload['user'], $this->payload['info'], $this->payload['extend']);
            return $this->succReturn();
        } catch(\Exception $e) {
            return $this->failReturn($e->getMessage());
        }
    }

    public function login() {
        try {
            $info = $this->adminRepository->login($this->payload['username'], $this->payload['password']);
            return $this->succReturn($info);
        } catch(\Exception $e) {
            return $this->failReturn($e->getMessage());
        }
    }

    public function logout() {
        AdminRepository::logout();
        return $this->succReturn([], '注销成功');
    }
}