<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/8
 * Time: 9:46 PM
 */

namespace Bitmyth\Wechat\Http\Controllers;

use Illuminate\Http\Request;
use Wechat\WxApi;

/**
 * Class WechatController
 * @package Bitmyth\Wechat\Http\Controllers
 */
trait LoginByWechat
{

    /**
     * @param Request $request
     */
    public function loginByWechat(Request $request)
    {
        $code = $request->get('code');
        $response = WxApi::oauthAccessToken($code);
        if ($response["code"] == 200) {
            Log::debug($response["data"]);
            $data = json_decode($response["data"]);
            $response = WxApi::userInfo($data->access_token, $data->openid);
            if ($response["code"] == 200) {
                return $this->authentecatedByWechat($response);
            }
        }
        $this->authentecatedByWechatFailed($response);
    }

    protected function authentecatedByWechat($response)
    {
        //
    }

    protected function authentecatedByWechatFailed($response)
    {
        return 'ERROR:' . $response["code"];
    }

}
