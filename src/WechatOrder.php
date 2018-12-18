<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/9
 * Time: 3:13 PM
 */

namespace Bitmyth\Wechat;


use Wechat\WxPayApi;
use Wechat\WxPayOrderQuery;
use Log;

trait WechatOrder
{

    /**
     * 查询订单
     * @param $transaction_id
     * @return bool
     * @throws \Wechat\WxPayException
     */
    public function queryOrder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);

        Log::DEBUG("wechat query:" . json_encode($result));

        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
        ) {
            return true;
        }
        return false;
    }

//    protected function store(Request $request, $calligraphy)
//    {
//        $order = new Order();
//        $order->title = 'sponsor 1 RMB ';
//        $order->product_id = $calligraphy->id;
//        $order->amount = 100;
//        $order->uuid = $this->uuid();
//        auth()->user()->orders()->save($order);
//        return $order;
//    }

    /**
     * Generate order uuid
     * @return string
     */
    public function uuid()
    {
        return md5(uniqid(rand(), true));
    }

}
