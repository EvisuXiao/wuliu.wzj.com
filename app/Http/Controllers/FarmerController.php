<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/11/3
 * Time: 5:13 PM
 */

namespace App\Http\Controllers;


use App\Repositories\FarmerRepository;

class FarmerController extends Controller
{
    protected $farmerRepository = null;

    public function __construct(FarmerRepository $farmerRepository) {
        parent::__construct();
        $this->farmerRepository = $farmerRepository;
    }

    public function list() {
        return $this->succReturn($this->farmerRepository->getFarmerList());
    }
}