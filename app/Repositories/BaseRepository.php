<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午5:39
 */

namespace App\Repositories;


class BaseRepository
{
    protected static $uid = 0;

    public function __construct() {
        self::$uid = AdminRepository::getLoginUid();
    }
}