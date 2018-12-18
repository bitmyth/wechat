<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/9
 * Time: 2:09 PM
 */

namespace Bitmyth\Wechat;

use Illuminate\Support\Facades\Log;
use Wechat\WxPayNotify;

class PayNotifyCallback extends WxPayNotify
{
    use WechatOrder;

    private $paid;

    public function __construct($paid)
    {
        $this->paid = $paid;
    }

    /**
     * 重写回调处理函数
     * @param array $data
     * @param string $msg
     * @return bool true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     * @throws \Wechat\WxPayException
     */
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("wechat call back:" . json_encode($data));

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }

        //查询订单，判断订单真实性
        if (!$this->queryOrder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }

        //TODO 根据transaction_id查询出订单，更改订单状态为已支付
        return call_user_func($this->paid, $data);
    }
}

