<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/9
 * Time: 2:09 PM
 */

namespace Bitmyth\Wechat;

use Illuminate\Support\Facades\Log;
use Wechat\WxPayApi;
use Wechat\WxPayNotify;
use Wechat\WxPayOrderQuery;

class PayNotifyCallback extends WxPayNotify
{
    //查询订单
    public function QueryOrder($transaction_id)
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


    /**
     * 重写回调处理函数
     * @param array $data
     * @param string $msg
     * @return bool|\Wechat\true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("wechat call back:" . json_encode($data));
        $notfiyOutput = array();

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }

        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }

        //TODO 根据transaction_id查询出订单，更改订单状态为已支付
        $order = Order::where('uuid', $data["out_trade_no"])->first();
        Log::info(json_encode($order));
        if ($order) {
            $order->wx_transaction_id = $data["transaction_id"];
            $order->wx_total_fee = $data["total_fee"];
            $order->status = 'paid';
            $order->save();
            $calligraphy = Calligraphy::find($order->product_id);
            $calligraphy->update([
                'publish' => true
            ]);
            Log::debug('paied successfully');
//            MessageFacade::sendBuyCompletedMessage(User::find($order->user_id), $course);
            return true;
        } else {
            Log::debug('失败 ,No order which uuid is ' . $data['out_trade_no'] . ' found!');
            return false;
        }
    }
}

