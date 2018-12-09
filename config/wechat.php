<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/7
 * Time: 10:40 AM
 */
return [
    'mp' => [
        'app_id' => env('MP_APP_ID'),//公众账号ID
        'app_secret' => env('MP_APP_SECRET')//应用密钥
    ],

    'mch' => [
        'mch_id' => env('MCH_ID'),//微信支付分配的商户号
        'key' => env('MCH_KEY'),//密钥设置
        'prepay_url' => config('app.url') . '/wechat/payment/prepay',//返回预付单URL
        'notify_url' => config('app.url') . '/wechat/payment/notify',//通知商户支付结果URL,
    ]
];
