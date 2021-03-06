<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/8
 * Time: 9:46 PM
 */

namespace Bitmyth\Wechat\Auth;

use Illuminate\Http\Request;
use Wechat\WxApi;
use Log;

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
                return $this->authenticatedByWechat($request, $response);
            }
        }
		$this->authenticatedByWechatFailed($request, $response);
    }

    protected function authenticatedByWechat($request, $response)
    {
        //
    }

    protected function authenticatedByWechatFailed($request, $response)
    {
        return 'ERROR:' . $response["code"];
    }

}
