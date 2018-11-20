<?php
/**
 * Created by PhpStorm.
 * User: evisu
 * Date: 2018/7/3
 * Time: 下午2:59
 */

namespace App\Library;

use Mail;

class Utils
{
    /**
     * 获取本次请求的controller与action
     * @return array
     */
    public static function getControllerAndAction() {
        list($controller, $action) = explode('@', request()->route()->getActionName());
        $controller = strtolower($controller);
        $action = strtolower($action);
        $controller = subrstr_str($controller, '\\', 'controller');
        $controller = $controller !== false ? $controller : '';
        return [$controller, $action];
    }

    /*
     * @desc 发送邮件
     * @param $mailTitle string 邮件标题
     * @param $mailContent string 邮件内容
     * @param $mailFrom string 邮件发送方地址
     * @param $mailTo array 邮件目的地址
     *
     */
    public static function sendMail($mailTitle, $mailContent, $mailTo = [], $mailFrom = '') {
        try {
            Mail::raw($mailContent, function ($message) use ($mailTitle, $mailFrom, $mailTo) {
                empty($mailFrom) && $mailFrom = config('mail.username');
                empty($mailTo) && $mailTo = config('mail.receiver');
                $title = (isProdEnv() ? '' : '【测】') . '供应链核心企业';
                $message->from($mailFrom, $title);
                foreach($mailTo as $address) {
                    $message->to($address);
                }
                $message->subject($mailTitle);
            });
        } catch (\Exception $e) {

        }
    }
}