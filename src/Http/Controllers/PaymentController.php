<?php

namespace Bitmyth\Wechat\Http\Controllers;

use Bitmyth\Wechat\PayNotifyCallback;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Wechat\WxApi;
use Wechat\WxPayApi;
use Wechat\WxPayOrderQuery;
use Wechat\WxPayRefund;
use Wechat\WxPayUnifiedOrder;

/**
 * Class PaymentController
 * @package Bitmyth\Wechat\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @param $order
     * @param $productId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pay(Request $request, $order)
    {
        try {
            //调用统一下单API
            $ret = $this->placeUnifiedOrder($order);

            $appId = $ret['appid'];
            $timeStamp = time();
            $nonceStr = WxApi::getNonceStr();
            $prepayId = $ret['prepay_id'];
            $package = 'prepay_id=' . $prepayId;
            $signType = 'MD5';
            $values = compact(
                'appId', 'timeStamp', 'nonceStr', 'package', 'signType'
            );
            $sign = WxApi::makeSign($values);

            $data = array_merge($values, compact('sign', 'prepayId', 'order', 'appId'));

            return $this->prepayIdGenerated($data, $order);

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * 调用统一下单API
     * @param $order
     * @return mixed
     * @throws \Exception
     */
    public function placeUnifiedOrder($order)
    {
        $input = new WxPayUnifiedOrder();

        $input->SetAppid(config('wechat.mp.app_id'));
        $input->SetMch_id(config('wechat.mch.mch_id'));

        $input->SetBody('购买' . $order->title);
        $input->SetAttach("test");
        $input->SetOut_trade_no($order->uuid); //$input->SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
        $input->SetTotal_fee($order->amount);
//        $input->SetTotal_fee(1);//dev set to 1 cent
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(config('app.url') . "/wechat/payment/notify");
        $input->SetTrade_type("JSAPI");//交易类型为公众号支付
        $input->SetProduct_id("32");
        $input->SetOpenid(auth()->user()->openid);

        $result = WxPayApi::unifiedOrder($input);

        Log::debug('统一下单api返回值:' . json_encode($result));
        if ($result['return_code'] == 'FAIL') {
            throw  new \Exception(json_encode($result));
        }
        return $result;
    }

    /**
     * Prepay order successfully generated
     * @param $data
     * @param $order
     * @return mixed
     */
    protected function prepayIdGenerated($data, $order)
    {
        // TODO
    }

    /**
     * 退款
     * @param Request $request
     * @param $uuid
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Wechat\WxPayException
     */
    public function refund(Request $request, $uuid)
    {
        $input = new WxPayRefund();
        $order = $this->findOrderByUUID($uuid);

        $input->SetOut_trade_no($order->uuid);
        $input->SetOut_refund_no($order->uuid);
        //如果SetRefund_fee(0)，$result会是签名错误
        $input->SetRefund_fee($order->wx_total_fee);//单位为分 //$input->SetRefund_fee(1);
        $input->SetTotal_fee($order->wx_total_fee);//单位为分
        $input->SetOp_user_id(config('wechat.mch.mch_id'));
        $result = WxPayApi::refund($input);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $this->refunded($order);
            if ($_SERVER['SCRIPT_NAME'] != 'artisan') {
                return view('setting.refund', compact('course'));
            }
            return ['success' => true];
        } else {
            return [
                'success' => false,
                'message' => array_key_exists('err_code', $result)
                    ? $result['err_code']
                    : $result['return_msg']
            ];
        }
    }

    /**
     * @param $uuid
     * @throws \Exception
     */
    protected function findOrderByUUID($uuid)
    {
        // $order = Order::where('uuid', $uuid)->firstOrFail();
        throw new \Exception('Must implement this method ' . __METHOD__);
    }

    /**
     * @param $order
     * @throws \Exception
     */
    protected function refunded($order)
    {
        // $order->status = 'refunded';
        // $order->save();
        throw new \Exception('Must implement this method ' . __METHOD__);
    }

    /**
     * 支付结果通知
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_7
     */
    public function paymentNotify()
    {
        try {
            $notify = new PayNotifyCallback([$this, 'paid']);
            $notify->Handle(false);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTrace());

        }
    }

    /**
     * @param $data
     * @return bool
     */
    protected function paid($data)
    {
        $order = Order::where('uuid', $data["out_trade_no"])->first();
        Log::info(json_encode($order));
        if ($order) {
            $order->wx_transaction_id = $data["transaction_id"];
            $order->wx_total_fee = $data["total_fee"];
            $order->status = 'paid';
            $order->save();

            Log::debug('paid successfully');
//            MessageFacade::sendBuyCompletedMessage(User::find($order->user_id), $course);
            return true;
        } else {
            Log::debug('失败 ,No order which uuid is ' . $data['out_trade_no'] . ' found!');
            return false;
        }
    }

}
