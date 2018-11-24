<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Repositories\AdminRepository;
use App\Repositories\DealerRepository;

class DealerController extends Controller
{
    protected $dealerRepository = null;

    public function __construct(DealerRepository $dealerRepository) {
        parent::__construct();
        $this->dealerRepository = $dealerRepository;
    }

    public function list() {
        return $this->succReturn($this->dealerRepository->getDealerList());
    }

    public function info() {
        return $this->succReturn($this->dealerRepository->getDealerInfo(AdminRepository::getLoginUid()));
    }
}